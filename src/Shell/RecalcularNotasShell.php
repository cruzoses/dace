<?php
/**
 * RecalcularNotas Shell
 *
 * Recalcula las calificaciones de cursos (históricos por defecto) usando la
 * lógica normalizada de App\Lib\NotasCalculador y actualiza
 * estudiante_cursos.calificacion.
 *
 * Uso:
 *   bin\cake recalcular_notas                (cursos cerrados)
 *   bin\cake recalcular_notas --curso=ID     (curso específico)
 *   bin\cake recalcular_notas --todos        (todos los cursos)
 *   bin\cake recalcular_notas --dry-run      (solo mostrar, no guardar)
 */
namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use App\Lib\NotasCalculador;

class RecalcularNotasShell extends Shell
{

    /**
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->setDescription(
            'Recalcula las calificaciones de cursos usando la lógica normalizada ' .
            'y actualiza estudiante_cursos.calificacion.'
        );
        $parser->addOption('curso', [
            'help' => 'ID de curso específico a recalcular (ignora el filtro de cerrado).',
            'short' => 'c',
        ]);
        $parser->addOption('todos', [
            'help' => 'Recalcula también los cursos abiertos.',
            'boolean' => true,
        ]);
        $parser->addOption('dry-run', [
            'help' => 'Solo muestra qué se actualizaría, sin guardar.',
            'boolean' => true,
        ]);

        return $parser;
    }

    /**
     * @return int
     */
    public function main()
    {
        $nCursoId = $this->param('curso') ? (int)$this->param('curso') : null;
        $bTodos = (bool)$this->param('todos');
        $bDryRun = (bool)$this->param('dry-run');

        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $ecTable = TableRegistry::getTableLocator()->get('EstudianteCursos');
        $indicadorCursosTable = TableRegistry::getTableLocator()->get('IndicadorCursos');
        $cursoNotasTable = TableRegistry::getTableLocator()->get('CursoNotas');

        $oQuery = $cursosTable->find()
            ->contain(['Asignaturas'])
            ->order(['Cursos.id' => 'ASC']);

        if ($nCursoId) {
            $oQuery->where(['Cursos.id' => $nCursoId]);
        } elseif (!$bTodos) {
            $oQuery->where(['Cursos.cerrado' => 1]);
        }

        $aCursos = $oQuery->toArray();

        if (empty($aCursos)) {
            $this->out('<info>No hay cursos para recalcular.</info>');

            return static::CODE_SUCCESS;
        }

        $nActualizados = 0;
        $nSinCambio = 0;

        foreach ($aCursos as $oCurso) {
            $nTipoCalificacion = (int)$oCurso->asignatura->calificacion;

            $aEstudiantes = $ecTable->find()
                ->where([
                    'EstudianteCursos.curso_id' => $oCurso->id,
                    'EstudianteCursos.activo' => 1,
                ])
                ->toArray();

            if (empty($aEstudiantes)) {
                $this->out("<info>[{$oCurso->id}]</info> sin estudiantes: skip");

                continue;
            }

            $aIndicadorCursoIds = $indicadorCursosTable->find()
                ->where(['curso_id' => $oCurso->id])
                ->extract('id')
                ->toArray();

            if (empty($aIndicadorCursoIds)) {
                $this->out("<info>[{$oCurso->id}]</info> sin indicadores: skip");

                continue;
            }

            $aContenidos = $cursoNotasTable->ContenidoCursos->find()
                ->where(['indicador_curso_id IN' => $aIndicadorCursoIds])
                ->contain(['IndicadorCursos'])
                ->toArray();

            if (empty($aContenidos)) {
                $this->out("<info>[{$oCurso->id}]</info> sin plan de evaluación: skip");

                continue;
            }

            $aNotas = $cursoNotasTable->find()
                ->where(['contenido_curso_id IN' => array_map(function ($o) {
                    return $o->id;
                }, $aContenidos)])
                ->toArray();

            $aNotasMap = [];
            foreach ($aNotas as $oNota) {
                $aNotasMap[(int)$oNota->estudiante_id][(int)$oNota->contenido_curso_id] = $oNota->calificacion;
            }

            $aTotales = NotasCalculador::calcularTotales($nTipoCalificacion, $aContenidos, $aNotasMap);

            $nCursoActualizados = 0;

            foreach ($aEstudiantes as $oEc) {
                $nEstId = (int)$oEc->estudiante_id;

                if (!isset($aTotales[$nEstId])) {
                    continue;
                }

                $nCursoActualizados++;
                $sNuevo = $aTotales[$nEstId]['final'];
                $sViejo = $oEc->calificacion;

                if ($bDryRun) {
                    $sMarca = ($sViejo !== $sNuevo) ? 'cambia' : 'igual';
                    $this->out("  EC #{$oEc->id} estudiante {$nEstId}: {$sViejo} -> {$sNuevo} ({$sMarca})");
                    if ($sViejo !== $sNuevo) {
                        $nActualizados++;
                    } else {
                        $nSinCambio++;
                    }

                    continue;
                }

                if ($sViejo !== $sNuevo) {
                    $oEc->calificacion = $sNuevo;
                    $oEc->responsable = 'RECALCULO';
                    if ($ecTable->save($oEc)) {
                        $nActualizados++;
                    } else {
                        $this->err("<error>Error guardando EC #{$oEc->id}</error>");
                    }
                } else {
                    $nSinCambio++;
                }
            }

            $this->out("<info>[{$oCurso->id}]</info> curso {$oCurso->asignatura->nombre} - estudiantes con total: {$nCursoActualizados}");
        }

        $this->out('');
        if ($bDryRun) {
            $this->out("<info>Resumen (dry-run):</info> {$nActualizados} a cambiar, {$nSinCambio} sin cambio.");
        } else {
            $this->out("<info>Resumen:</info> {$nActualizados} calificación(es) actualizada(s), {$nSinCambio} sin cambio.");
        }

        return static::CODE_SUCCESS;
    }
}
