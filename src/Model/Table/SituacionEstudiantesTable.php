<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * SituacionEstudiantes Model
 *
 * @property \App\Model\Table\EstudiantesTable&\Cake\ORM\Association\BelongsTo $Estudiantes
 * @property \App\Model\Table\ProgramasTable&\Cake\ORM\Association\BelongsTo $Programas
 * @property \App\Model\Table\AsignaturasTable&\Cake\ORM\Association\BelongsTo $Asignaturas
 * @property \App\Model\Table\PeriodosTable&\Cake\ORM\Association\BelongsTo $Periodos
 *
 * @method \App\Model\Entity\SituacionEstudiante get($primaryKey, $options = [])
 * @method \App\Model\Entity\SituacionEstudiante newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SituacionEstudiante[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SituacionEstudiante|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SituacionEstudiante saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SituacionEstudiante patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SituacionEstudiante[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SituacionEstudiante findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SituacionEstudiantesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('situacion_estudiantes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estudiantes', [
            'foreignKey' => 'estudiante_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Programas', [
            'foreignKey' => 'programa_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Asignaturas', [
            'foreignKey' => 'asignatura_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Trayectos', [
            'foreignKey' => 'trayecto_id',
        ]);
        $this->belongsTo('Periodos', [
            'foreignKey' => 'periodo_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('trayecto_id')
            ->allowEmptyString('trayecto_id');

        $validator
            ->scalar('seccion')
            ->maxLength('seccion', 20)
            ->allowEmptyString('seccion');

        $validator
            ->scalar('calificacion')
            ->maxLength('calificacion', 5)
            ->allowEmptyString('calificacion');

        $validator
            ->integer('cursada')
            ->allowEmptyString('cursada');

        $validator
            ->integer('acumulado')
            ->allowEmptyString('acumulado');

        $validator
            ->scalar('responsable')
            ->maxLength('responsable', 50)
            ->allowEmptyString('responsable');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['estudiante_id'], 'Estudiantes'));
        $rules->add($rules->existsIn(['programa_id'], 'Programas'));
        $rules->add($rules->existsIn(['asignatura_id'], 'Asignaturas'));
        $rules->add($rules->existsIn(['trayecto_id'], 'Trayectos'));
        $rules->add($rules->existsIn(['periodo_id'], 'Periodos'));

        return $rules;
    }

    public function registrarDesdeMalla($estudianteId, $programaId, $carreraId, $periodoId)
    {
        \Cake\Log\Log::write('debug', 'registrarDesdeMalla INICIO: est=' . $estudianteId . ' prog=' . $programaId . ' carr=' . $carreraId . ' per=' . $periodoId);

        $existe = $this->find()
            ->where([
                'estudiante_id' => $estudianteId,
                'programa_id' => $programaId,
            ])
            ->count();

        \Cake\Log\Log::write('debug', 'registrarDesdeMalla registros existentes: ' . $existe);

        if ($existe > 0) {
            \Cake\Log\Log::write('debug', 'registrarDesdeMalla YA EXISTEN registros, se omite');
            return;
        }

        $mallasTable = TableRegistry::getTableLocator()->get('Mallas');
        $mallas = $mallasTable->find()
            ->where([
                'carrera_id' => $carreraId,
                'programa_id' => $programaId,
            ])
            ->toArray();

        \Cake\Log\Log::write('debug', 'registrarDesdeMalla mallas encontradas: ' . count($mallas));

        if (empty($mallas)) {
            \Cake\Log\Log::write('debug', 'registrarDesdeMalla NO HAY MALLAS para carrera=' . $carreraId . ' programa=' . $programaId);
            return;
        }

        foreach ($mallas as $malla) {
            $situacion = $this->newEntity();
            $situacion->estudiante_id = $estudianteId;
            $situacion->programa_id = $programaId;
            $situacion->asignatura_id = $malla->asignatura_id;
            $situacion->trayecto_id = $malla->trayecto_id;
            $situacion->periodo_id = $periodoId;
            $situacion->cursada = 1;
            $situacion->acumulado = 1;
            $result = $this->save($situacion, ['checkRules' => false]);
            if (!$result) {
                \Cake\Log\Log::write('error', 'registrarDesdeMalla SAVE FALLÓ: est=' . $estudianteId . ' prog=' . $programaId . ' asig=' . $malla->asignatura_id . ' errors=' . json_encode($situacion->getErrors()));
            }
        }

        \Cake\Log\Log::write('debug', 'registrarDesdeMalla FIN');
    }

    public function sincronizarDesdeHistorico($estudianteId, $programaId)
    {
        $asignaturasPrograma = $this->find()
            ->where([
                'estudiante_id' => $estudianteId,
                'programa_id' => $programaId,
            ])
            ->extract('asignatura_id')
            ->toList();

        $programasTable = TableRegistry::getTableLocator()->get('Programas');
        $programa = $programasTable->get($programaId);
        $programaNotaMinima = (int)$programa->nota_minima;

        $mallasTable = TableRegistry::getTableLocator()->get('Mallas');
        $mallasPrograma = $mallasTable->find()
            ->where(['programa_id' => $programaId])
            ->toArray();
        $asignaturasEnMalla = [];
        foreach ($mallasPrograma as $m) {
            $asignaturasEnMalla[$m->asignatura_id] = $m;
        }

        $todasAsignaturasIds = array_unique(array_merge($asignaturasPrograma, array_keys($asignaturasEnMalla)));

        $asignaturasTable = TableRegistry::getTableLocator()->get('Asignaturas');
        $asignaturasMap = [];
        if (!empty($todasAsignaturasIds)) {
            $asignaturasData = $asignaturasTable->find()
                ->where(['id IN' => $todasAsignaturasIds])
                ->toArray();
            foreach ($asignaturasData as $a) {
                $asignaturasMap[$a->id] = $a;
            }
        }

        $tablanotasTable = TableRegistry::getTableLocator()->get('Historicos');

        $notas = $tablanotasTable->find()
            ->where([
                'estudiante_id' => $estudianteId,
                'asignatura_id IN' => $todasAsignaturasIds,
            ])
            ->toArray();

        if (empty($notas)) {
            return 0;
        }

        $porAsignatura = [];
        foreach ($notas as $nota) {
            $porAsignatura[$nota->asignatura_id][] = $nota;
        }

        $actualizados = 0;
        foreach ($porAsignatura as $asignaturaId => $listaNotas) {
            $ultima = null;
            $maxNota = -1;
            foreach ($listaNotas as $n) {
                $nota = $this->calificacionANumero($n->calificacion);
                if ($nota > $maxNota) {
                    $maxNota = $nota;
                    $ultima = $n;
                }
            }

            if (!$ultima) {
                continue;
            }

            $cursada = count($listaNotas);

            $acumulado = 0;
            $creditosAsig = isset($asignaturasMap[$asignaturaId]) ? (int)$asignaturasMap[$asignaturaId]->creditos : 1;
            foreach ($listaNotas as $n) {
                $val = strtoupper(trim($n->calificacion));
                if ($val === 'A') {
                    $acumulado += 20 * $creditosAsig;
                } elseif ($val === 'R') {
                    $acumulado += 0;
                } else {
                    $acumulado += (int)$n->calificacion * $creditosAsig;
                }
            }

            $filaMalla = isset($asignaturasEnMalla[$asignaturaId]) ? $asignaturasEnMalla[$asignaturaId] : null;

            if ($filaMalla && !empty($filaMalla->nota_minima)) {
                $notaMinima = (int)$filaMalla->nota_minima;
            } elseif (isset($asignaturasMap[$asignaturaId]) && !empty($asignaturasMap[$asignaturaId]->nota_minima)) {
                $notaMinima = (int)$asignaturasMap[$asignaturaId]->nota_minima;
            } else {
                $notaMinima = $programaNotaMinima;
            }

            $calificacionActual = strtoupper(trim($ultima->calificacion));
            $aprobada = ($calificacionActual === 'A' || (int)$calificacionActual >= $notaMinima);

            if (!$aprobada && isset($asignaturasMap[$asignaturaId])) {
                $asignatura = $asignaturasMap[$asignaturaId];
                if (!empty($asignatura->convalidacion)) {
                    $codigos = $this->parseConvalidacion($asignatura->convalidacion);
                    $notaConvalidada = $this->buscarConvalidacion($estudianteId, $codigos, $programaNotaMinima);
                    if ($notaConvalidada !== null) {
                        $ultima->calificacion = $notaConvalidada['calificacion'];
                        $ultima->seccion = $notaConvalidada['seccion'];
                        $ultima->responsable = $notaConvalidada['responsable'];
                        $ultima->periodo_id = $notaConvalidada['periodo_id'];
                    }
                }
            }

            $situacion = $this->find()
                ->where([
                    'estudiante_id' => $estudianteId,
                    'programa_id' => $programaId,
                    'asignatura_id' => $asignaturaId,
                ])
                ->first();

            if ($situacion) {
                $situacion->calificacion = $ultima->calificacion;
                $situacion->seccion = $ultima->seccion;
                $situacion->responsable = $ultima->responsable;
                $situacion->periodo_id = $ultima->periodo_id;
                $situacion->cursada = $cursada;
                $situacion->acumulado = $acumulado;
                $this->save($situacion, ['checkRules' => false]);
                $actualizados++;
            } elseif ($filaMalla) {
                $nueva = $this->newEntity();
                $nueva->estudiante_id = $estudianteId;
                $nueva->programa_id = $programaId;
                $nueva->asignatura_id = $asignaturaId;
                $nueva->trayecto_id = $filaMalla->trayecto_id;
                $nueva->periodo_id = $ultima->periodo_id;
                $nueva->seccion = $ultima->seccion;
                $nueva->calificacion = $ultima->calificacion;
                $nueva->responsable = $ultima->responsable;
                $nueva->cursada = $cursada;
                $nueva->acumulado = $acumulado;
                $this->save($nueva, ['checkRules' => false]);
                $actualizados++;
            }
        }

        $sinNotas = $this->find()
            ->where([
                'estudiante_id' => $estudianteId,
                'programa_id' => $programaId,
                'calificacion IS' => null,
            ])
            ->toArray();

        if (!empty($sinNotas)) {
            foreach ($sinNotas as $situacion) {
                $asignaturaId = $situacion->asignatura_id;

                if (isset($porAsignatura[$asignaturaId])) {
                    continue;
                }

                if (!isset($asignaturasMap[$asignaturaId])) {
                    continue;
                }

                $asignatura = $asignaturasMap[$asignaturaId];
                if (empty($asignatura->convalidacion)) {
                    continue;
                }

                $codigos = $this->parseConvalidacion($asignatura->convalidacion);
                $notaConvalidada = $this->buscarConvalidacion($estudianteId, $codigos, $programaNotaMinima);

                if ($notaConvalidada !== null) {
                    $situacion->calificacion = $notaConvalidada['calificacion'];
                    $situacion->seccion = $notaConvalidada['seccion'];
                    $situacion->responsable = $notaConvalidada['responsable'];
                    $situacion->periodo_id = $notaConvalidada['periodo_id'];
                    $this->save($situacion, ['checkRules' => false]);
                    $actualizados++;
                }
            }
        }

        return $actualizados;
    }

    private function parseConvalidacion($texto)
    {
        $texto = trim($texto);
        if (empty($texto)) {
            return [];
        }
        $partes = preg_split('/\s*[;,]\s*|\s+o\s+/', $texto, -1, PREG_SPLIT_NO_EMPTY);
        $codigos = [];
        foreach ($partes as $p) {
            $codigos[] = strtoupper(trim($p));
        }
        return array_filter($codigos);
    }

    private function esAprobada($calificacion, $notaMinima)
    {
        $val = strtoupper(trim($calificacion));
        if ($val === 'A') {
            return true;
        }
        if ($val === 'R' || $val === 'IN' || $val === '') {
            return false;
        }
        return (int)$val >= (int)$notaMinima;
    }

    private function calificacionANumero($calificacion)
    {
        $val = strtoupper(trim($calificacion));
        if ($val === 'A') {
            return 20;
        }
        if ($val === 'R' || $val === 'IN' || $val === '') {
            return 0;
        }
        return (int)$val;
    }

    private function buscarConvalidacion($estudianteId, array $codigosAlternativos, $programaNotaMinima)
    {
        if (empty($codigosAlternativos)) {
            return null;
        }

        $asignaturasTable = TableRegistry::getTableLocator()->get('Asignaturas');
        $tablanotasTable = TableRegistry::getTableLocator()->get('Historicos');
        $mallasTable = TableRegistry::getTableLocator()->get('Mallas');

        $alternativas = $asignaturasTable->find()
            ->where(['UPPER(codigo) IN' => $codigosAlternativos])
            ->toArray();

        if (empty($alternativas)) {
            return null;
        }

        foreach ($alternativas as $alt) {
            $filaMalla = $mallasTable->find()
                ->where(['asignatura_id' => $alt->id])
                ->order(['id' => 'ASC'])
                ->first();

            if ($filaMalla && !empty($filaMalla->nota_minima)) {
                $notaMinimaAlt = (int)$filaMalla->nota_minima;
            } elseif (!empty($alt->nota_minima)) {
                $notaMinimaAlt = (int)$alt->nota_minima;
            } else {
                $notaMinimaAlt = (int)$programaNotaMinima;
            }

            $notasAlt = $tablanotasTable->find()
                ->where([
                    'estudiante_id' => $estudianteId,
                    'asignatura_id' => $alt->id,
                ])
                ->toArray();

            if (empty($notasAlt)) {
                continue;
            }

            $ultimaAlt = null;
            $maxNotaAlt = -1;
            foreach ($notasAlt as $na) {
                $nota = $this->calificacionANumero($na->calificacion);
                if ($nota > $maxNotaAlt) {
                    $maxNotaAlt = $nota;
                    $ultimaAlt = $na;
                }
            }

            if ($ultimaAlt && $this->esAprobada($ultimaAlt->calificacion, $notaMinimaAlt)) {
                return [
                    'calificacion' => $ultimaAlt->calificacion,
                    'seccion' => $ultimaAlt->seccion,
                    'responsable' => $ultimaAlt->responsable,
                    'periodo_id' => $ultimaAlt->periodo_id,
                ];
            }
        }

        return null;
    }
}
