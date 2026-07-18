<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Datos Controller
 *
 * @method \App\Model\Entity\Dato[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class DatosController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user = null)
	{
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] && $this->tienePermiso([2,3]) )
        {
            return true;
        }
		return parent::isAuthorized($user);
	}

    public function index()
    {
    }

    public function students()
    {
        $this->loadModel('Estudiantes');
        $conditions = $this->Estudiantes->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'conditions' => $conditions,
        ];
        $estudiantes = $this->paginate($this->Estudiantes, ['order' => ['Estudiantes.cedula' => 'ASC']]);

        if ($estudiantes->count() == 1) {
            return $this->redirect(['action' => 'estudiante', $estudiantes->first()->id]);
        }

        $filtros = $this->request->getQuery();
        $searchFields = $this->Estudiantes->getSearchFields();

        $this->set(compact('estudiantes', 'filtros', 'searchFields'));
    }

    public function estudiante($id)
    {
        //$this->loadModel('Estudiantes');
        $estudiante = TableRegistry::getTableLocator()->get('Estudiantes')->get($id,[
            //$estudiante = $this->Estudiantes->get($id, [
            'contain' => ['Paises', 'Estados', 'Municipios', 'Parroquias', 'Usuarios', 'EstudianteCursos', 'EstudianteProgramas', 
            'Graduandos', 'Historicos', 'NotasCursos', 'SituacionEstudiantes'],
        ]);
        $aGeneros = Configure::read('aGeneros');
        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Estudiantes ' . json_encode($estudiante->toArray()));

        $this->set(compact('aGeneros'));
        $this->set('estudiante', $estudiante);
    }

    public function rendimiento()
    {
        
    }

    public function programas($estudianteId = null)
    {
        $programas = TableRegistry::getTableLocator()->get('EstudianteProgramas')->find('all', [
            'conditions' => ['EstudianteProgramas.estudiante_id' => $estudianteId],
            'contain' => ['Estudiantes', 'Carreras', 'Programas', 'Sedes'] 
        ])
        //->where(['EstudianteProgramas.estudiante_id' => $estudianteId])
        ->toArray();

        $situacionEstudiantesTable = TableRegistry::getTableLocator()->get('SituacionEstudiantes');
        foreach ($programas as $programa) {
            $situacionEstudiantesTable->registrarDesdeMalla(
                $estudianteId,
                $programa->programa_id,
                $programa->carrera_id,
                $programa->periodo_id
            );
        }

        $this->set(compact('programas', 'estudianteId'));
        $this->set('_serialize', ['programas']); // Para que se pueda serializar a JSON si se requiere
        $this->viewBuilder()->setLayout('ajax'); // Usar un layout vacío para las llamadas AJAX
    }

    public function situacion($estudianteId = null, $programaId = null)
    {
        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($estudianteId);

        $programasTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');
        $programasQuery = $programasTable->find()
            ->where(['EstudianteProgramas.estudiante_id' => $estudianteId])
            ->contain(['Carreras', 'Programas']);

        if ($programaId) {
            $programasQuery->where(['EstudianteProgramas.programa_id' => $programaId]);
        }

        $programas = $programasQuery->toArray();

        $situacionEstudiantesTable = TableRegistry::getTableLocator()->get('SituacionEstudiantes');
        foreach ($programas as $programa) {
            $situacionEstudiantesTable->registrarDesdeMalla(
                $estudianteId,
                $programa->programa_id,
                $programa->carrera_id,
                $programa->periodo_id
            );
        }

        $situaciones = [];
        foreach ($programas as $programa) {
            $asignaturasTable = TableRegistry::getTableLocator()->get('SituacionEstudiantes');
            $asignaturas = $asignaturasTable->find()
                ->where([
                    'SituacionEstudiantes.estudiante_id' => $estudianteId,
                    'SituacionEstudiantes.programa_id' => $programa->programa_id,
                ])
                ->contain(['Asignaturas', 'Trayectos', 'Periodos'])
                ->order(['SituacionEstudiantes.programa_id' => 'ASC', 'SituacionEstudiantes.trayecto_id' => 'ASC', 'SituacionEstudiantes.asignatura_id' => 'ASC'])
                ->toArray();

            $mallasTable = TableRegistry::getTableLocator()->get('Mallas');
            $mallas = $mallasTable->find()
                ->where(['Mallas.programa_id' => $programa->programa_id])
                ->toArray();
            $mallasPorAsignatura = [];
            foreach ($mallas as $m) {
                $mallasPorAsignatura[$m->asignatura_id] = $m;
            }

            $notaMinimaPrograma = (float)$programa->programa->nota_minima;
            $totalCreditosPrograma = (int)$programa->programa->creditos;
            $totalAsignaturas = count($asignaturas);
            $totalCreditosAprobados = 0;
            $totalAsignaturasAprobadas = 0;
            $isaNumerador = 0;
            $isaDenominador = 0;
            $iraNumerador = 0;
            $iraDenominador = 0;

            foreach ($asignaturas as $asig) {
                if (empty($asig->calificacion)) {
                    continue;
                }
                $esCualitativa = $asig->has('asignatura') && (int)$asig->asignatura->calificacion === 1;
                if ($esCualitativa) {
                    $aprobada = strtoupper($asig->calificacion) === 'A';
                    $notaISA = strtoupper($asig->calificacion) === 'A' ? 20 : 0;
                } else {
                    $notaMinima = $notaMinimaPrograma;
                    if (isset($mallasPorAsignatura[$asig->asignatura_id]) && !empty($mallasPorAsignatura[$asig->asignatura_id]->nota_minima)) {
                        $notaMinima = (float)$mallasPorAsignatura[$asig->asignatura_id]->nota_minima;
                    }
                    $aprobada = (float)$asig->calificacion >= $notaMinima;
                    $notaISA = (float)$asig->calificacion;
                }
                if ($aprobada) {
                    $totalCreditosAprobados += (int)$asig->asignatura->creditos;
                    $totalAsignaturasAprobadas++;
                }
                $creditosAsig = (int)$asig->asignatura->creditos;
                $isaNumerador += $notaISA * $creditosAsig;
                $isaDenominador += $creditosAsig;

                if (!empty($asig->acumulado) && (int)$asig->acumulado > 0) {
                    $iraNumerador += (int)$asig->acumulado;
                } else {
                    $notaIRA = $esCualitativa ? $notaISA : (float)$asig->calificacion;
                    $iraNumerador += $notaIRA * $creditosAsig;
                }
                $iraDenominador += $creditosAsig;
            }

            $porcentajeAprobado = $totalCreditosPrograma > 0
                ? round(($totalCreditosAprobados / $totalCreditosPrograma) * 100, 1)
                : 0;

            $isa = $isaDenominador > 0 ? round($isaNumerador / $isaDenominador, 2) : 0;
            $ira = $iraDenominador > 0 ? round($iraNumerador / $iraDenominador, 2) : 0;

            $situaciones[] = [
                'programa' => $programa,
                'asignaturas' => $asignaturas,
                'mallasPorAsignatura' => $mallasPorAsignatura,
                'totalCreditosPrograma' => $totalCreditosPrograma,
                'totalAsignaturas' => $totalAsignaturas,
                'totalCreditosAprobados' => $totalCreditosAprobados,
                'totalAsignaturasAprobadas' => $totalAsignaturasAprobadas,
                'porcentajeAprobado' => $porcentajeAprobado,
                'isa' => $isa,
                'ira' => $ira,
            ];
        }
        $this->set('title', 'Situación');
        $this->set(compact('estudiante', 'situaciones'));
        $this->viewBuilder()->setLayout('ajax');
    }
}
