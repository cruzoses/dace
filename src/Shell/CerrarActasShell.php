<?php
/**
 * CerrarActas Shell
 *
 * Cierra el acta de notas de cursos de forma masiva reutilizando
 * App\Lib\CierreActas (misma lógica que CursoNotasController::cerrarActa).
 *
 * Uso:
 *   bin\cake cerrar_actas --periodo=ID              (todos los cursos del periodo)
 *   bin\cake cerrar_actas --curso=ID                (un curso específico)
 *   bin\cake cerrar_actas --periodo=ID --dry-run    (solo reportar, no guardar)
 *   bin\cake cerrar_actas --curso=ID --recalcular   (procesar aunque ya esté cerrado)
 */
namespace App\Shell;

use App\Lib\CierreActas;
use Cake\Console\Shell;

class CerrarActasShell extends Shell
{

    /**
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->setDescription(
            'Cierra el acta de notas de cursos (por periodo o por curso) usando la ' .
            'misma lógica de CursoNotasController::cerrarActa y actualiza ' .
            'estudiante_cursos.calificacion y responsable.'
        );
        $parser->addOption('periodo', [
            'help' => 'ID del periodo: cierra todos los cursos pendientes de ese periodo.',
            'short' => 'p',
        ]);
        $parser->addOption('curso', [
            'help' => 'ID del curso a cerrar.',
            'short' => 'c',
        ]);
        $parser->addOption('recalcular', [
            'help' => 'Procesa también cursos ya cerrados.',
            'boolean' => true,
        ]);
        $parser->addOption('dry-run', [
            'help' => 'Solo muestra qué se cerraría, sin guardar.',
            'boolean' => true,
        ]);

        return $parser;
    }

    /**
     * @return int
     */
    public function main()
    {
        $nPeriodo = $this->param('periodo') ? (int)$this->param('periodo') : null;
        $nCurso = $this->param('curso') ? (int)$this->param('curso') : null;
        $bRecalcular = (bool)$this->param('recalcular');
        $bDryRun = (bool)$this->param('dry-run');

        if (!$nPeriodo && !$nCurso) {
            $this->err('<error>Debe indicar --periodo=ID o --curso=ID.</error>');
            $this->out($this->OptionParser->help());

            return static::CODE_ERROR;
        }

        if ($nPeriodo && $nCurso) {
            $this->err('<error>Solo puede indicar uno de --periodo o --curso.</error>');

            return static::CODE_ERROR;
        }

        if ($nCurso) {
            $aResultado = CierreActas::cerrarCurso($nCurso, $bDryRun, $bRecalcular);
            $this->_mostrarCurso($aResultado);

            return static::CODE_SUCCESS;
        }

        $aResultado = CierreActas::cerrarPeriodo($nPeriodo, $bDryRun, $bRecalcular);
        $this->_mostrarPeriodo($aResultado);

        return static::CODE_SUCCESS;
    }

    /**
     * @param array $aR
     * @return void
     */
    protected function _mostrarCurso(array $aR)
    {
        $sMarca = ($aR['estado'] === 'OK' || $aR['estado'] === 'PARCIAL') ? '<info>OK</info>' : '<error>' . $aR['estado'] . '</error>';
        $this->out("Curso #{$aR['curso_id']} - {$aR['asignatura']} - Sección {$aR['seccion']} ({$aR['periodo']})");
        $this->out("  Docente: {$aR['docente']}");
        $this->out("  Estado: {$sMarca}" . ($aR['motivo'] ? " - {$aR['motivo']}" : ''));
        $this->out("  Estudiantes: {$aR['estudiantes']} | Evaluaciones: {$aR['evaluaciones']} | Con total: {$aR['con_total']} | Sin total: {$aR['sin_total']} | Actualizados: {$aR['actualizados']}");

        if ($this->param('dry-run') && !empty($aR['detalle'])) {
            $this->out('  Detalle (dry-run):');
            foreach ($aR['detalle'] as $d) {
                $this->out("    Estudiante #{$d['estudiante_id']}: {$d['anterior']} -> {$d['final']}");
            }
        }
        $this->out('');
    }

    /**
     * @param array $aResultado
     * @return void
     */
    protected function _mostrarPeriodo(array $aResultado)
    {
        $oP = $aResultado['periodo'];
        $this->out("<info>Periodo {$oP['codigo']} - {$oP['nombre']}</info>");

        foreach ($aResultado['cursos'] as $aR) {
            $this->_mostrarCurso($aR);
        }

        $s = $aResultado['resumen'];
        $this->out('<info>Resumen:</info>');
        $this->out("  Cursos totales: {$s['total_cursos']}");
        $this->out("  Cerrados ahora: {$s['cerrados']}");
        $this->out("  Ya cerrados: {$s['ya_cerrados']}");
        $this->out("  Saltados (sin datos): {$s['saltados']}");
        $this->out("  Calificaciones actualizadas: {$s['actualizados']}");
        $this->out("  Estudiantes involucrados: {$s['estudiantes']}");
    }
}
