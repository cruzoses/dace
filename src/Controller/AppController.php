<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
*/
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3/en/controllers.html#the-app-controller
*/
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
    */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('Auditorias');
        $this->loadComponent('Auth', [
            'authorize' => ['Controller'],
            'authenticate' => [
                'Form' => [
                    'userModel' => 'Usuarios',
                    'fields' => [
                        'username' => 'username',
                        'password' => 'password'
                    ],
                    'finder' => 'auth'
                ]
            ],
            'loginAction' => ['controller' => 'Usuarios', 'action' => 'login'],
            'authError' => false, //'Ingrese sus datos',
            'loginRedirect' => ['controller' => 'Pages','action' => 'display'],
            'logoutRedirect' => ['controller' => 'Pages','action' => 'display'],
            'unauthorizedRedirect' => $this->referer()
        ]);

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3/en/controllers/components/security.html
        */
        //$this->loadComponent('Security');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);        
        $userActivo = $this->getRequest()->getSession()->read('Auth.User');
        //$userActivo = $this->Auth->user();
        if ( $userActivo ) 
        {
            $this->viewBuilder()->setLayout("admin");
        } else {
            $this->viewBuilder()->setLayout("default");
        }
        //$this->set('userActivo', $this->Auth->user());
        $this->set(compact('userActivo'));

        $aPermisosId = [];
        $aPermisosNombre = [];
        if (!empty($userActivo['rols'])) {
            foreach ($userActivo['rols'] as $rol) {
                $aPermisosId[] = (int)$rol['id'];
                $aPermisosNombre[] = $rol['nombre'];
            }
        }
        $this->Auth->allow(['display','keepalive','homepage']);
        $this->set(compact('aPermisosId', 'aPermisosNombre'));
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\EventInterface $event The beforeRender event.
     * @return \Cake\Http\Response|null|void
    */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        if (!array_key_exists('_serialize', $this->viewVars) && in_array($this->response->getType(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }        
        $this->viewBuilder()->setTheme('AdminLTE');
        if ($this->viewBuilder()->getClassName() === null) {
            $this->viewBuilder()->setClassName('AdminLTE.AdminLTE');
        }
    }

    /**
     * 
    */
    public function isAuthorized($user = null)
    {        
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] && $this->tienePermiso(1) )
        {
            return true;
        }
        $this->Flash->error(__('Woopsie, you are not authorized to access this area.'));
        return false;
    }

    public function buscar()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            unset($data['_csrfToken']);
            $url = array_map('trim', array_filter($data));
            return $this->redirect(['action' => 'index', '?' => $url]);
        }
        return $this->redirect(['action' => 'index']);
    }

    public function keepalive()
    {
        $this->autoRender = false;
        $this->response = $this->response->withType('application/json');

        if ($this->Auth->user()) {
            $this->getRequest()->getSession()->write('_keepalive', time());
            $this->response = $this->response->withStringBody(json_encode(['status' => 'ok']));
        } else {
            $this->response = $this->response->withStringBody(json_encode(['status' => 'expired']));
        }
        return $this->response;
    }

    public function homepage()
    {
        return $this->redirect($this->Auth->redirectUrl());
    }

    /**
     * Verifica si el usuario autenticado tiene al menos uno de los roles especificados.
     *
     * @param int|string|array $nRol ID del rol, nombre del rol o arreglo mixto de IDs/nombres.
     * @return bool
    */
    public function tienePermiso($nRol)
    {
        $user = $this->getRequest()->getSession()->read('Auth.User');
        if (empty($user) || empty($user['rols'])) {
            return false;
        }

        $roles = (array)$nRol;

        foreach ($user['rols'] as $rol) {
            foreach ($roles as $r) {
                if (is_numeric($r)) {
                    if ((int)$rol['id'] === (int)$r) {
                        return true;
                    }
                } else {
                    if (strcasecmp($rol['nombre'], $r) === 0) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function convertDate($date)
    {
        $parts = explode('/', $date);
        if (count($parts) === 3) 
        {
            if (strlen($parts[2]) === 4) {
                return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            }
            if (strlen($parts[0]) === 4) {
                return $date;
            }
        }        
        return $date;
    }

    public function generateToken()
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';
        for ($i = 0; $i < 6; $i++) {
            $token .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $token;
    }

    public function escaladenotas( $nValor )
    {
        switch ($nValor) {
            case $nValor == 1 || $nValor <= 5:
                $sNota = 1;
                break;
            case $nValor == 6 || $nValor <= 10:
                $sNota = 2;
                break;
            case $nValor == 11 || $nValor <= 15:
                $sNota = 3;
                break;
            case $nValor == 16 || $nValor <= 20:
                $sNota = 4;
                break;
            case $nValor == 21 || $nValor <= 25:
                $sNota = 5;
                break;
            case $nValor == 26 || $nValor <= 30:
                $sNota = 6;
                break;
            case $nValor == 31 || $nValor <= 35:
                $sNota = 7;
                break;
            case $nValor == 36 || $nValor <= 40:
                $sNota = 8;
                break;
            case $nValor == 41 || $nValor <= 45:
                $sNota = 9;
                break;
            case $nValor == 46 || $nValor <= 50:
                $sNota = 10;
                break;
            case $nValor == 51 || $nValor <= 55:
                $sNota = 11;
                break;
            case $nValor == 56 || $nValor <= 60:                
                $sNota = 12;
                break;
            case $nValor == 61 || $nValor <= 65:
                $sNota = 13;
                break;
            case $nValor == 66 || $nValor <= 70:
                $sNota = 14;
                break;
            case $nValor == 71 || $nValor <= 75:
                $sNota = 15;
                break;
            case $nValor == 76 || $nValor <= 80:
                $sNota = 16;
                break;
            case $nValor == 81 || $nValor <= 85:
                $sNota = 17;
                break;
            case $nValor == 86 || $nValor <= 90:
                $sNota = 18;
                break;
            case $nValor == 91 || $nValor <= 95:
                $sNota = 19;
                break;
            case $nValor == 96 || $nValor <= 100:
                $sNota = 20;
                break;           
            default:
                $sNota = "";
                break;
        }
        return $sNota;
    }

    /**
     * Obtiene los datos necesarios para el Acta de Notas de un curso.
     *
     * @param int $nCursoId
     * @param string|null $sProceso 'T1','T2','T3','S1','S2' o null/TODAS para todos
     * @return array|false false si no hay datos, array con curso, estudiantes, evaluaciones, grillas
     */
    protected function _obtenerDatosActa($nCursoId, $sProceso = null)
    {
        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $oCurso = $cursosTable->get($nCursoId, [
            'contain' => ['Asignaturas', 'Periodos', 'Docentes'],
        ]);

        if (!$oCurso) {
            return false;
        }

        $nFrecuencia = (int)$oCurso->asignatura->frecuencia;
        $nTipoCalificacion = (int)$oCurso->asignatura->calificacion;

        $aEstudiantes = TableRegistry::getTableLocator()->get('EstudianteCursos')->find()
            ->where([
                'EstudianteCursos.curso_id' => $nCursoId,
                'EstudianteCursos.activo' => 1,
            ])
            ->contain(['Estudiantes'])
            ->order(['Estudiantes.apellidos' => 'ASC', 'Estudiantes.nombres' => 'ASC'])
            ->toArray();

        if (empty($aEstudiantes)) {
            return false;
        }

        $aIndicadorCursoIds = TableRegistry::getTableLocator()->get('IndicadorCursos')->find()
            ->where(['curso_id' => $nCursoId])
            ->extract('id')
            ->toArray();

        if (empty($aIndicadorCursoIds)) {
            return false;
        }

        $aIndicadores = TableRegistry::getTableLocator()->get('IndicadorCursos')->find()
            ->where(['id IN' => $aIndicadorCursoIds])
            ->toArray();

        $aEvaluaciones = TableRegistry::getTableLocator()->get('CursoNotas')
            ->ContenidoCursos->find()
            ->where(['indicador_curso_id IN' => $aIndicadorCursoIds])
            ->contain(['IndicadorCursos'])
            ->order(['IndicadorCursos.id' => 'ASC', 'ContenidoCursos.fecha' => 'ASC'])
            ->toArray();

        if (empty($aEvaluaciones)) {
            return false;
        }

        if ($sProceso !== null && $sProceso !== 'TODAS') {
            $aFiltradas = [];
            foreach ($aIndicadores as $oInd) {
                if ($this->_mapearProceso($oInd->id, $nFrecuencia) === $sProceso) {
                    $aFiltradas[] = $oInd->id;
                }
            }
            $aEvaluaciones = array_values(array_filter($aEvaluaciones, function ($o) use ($aFiltradas) {
                return in_array($o->indicador_curso_id, $aFiltradas);
            }));
        }

        if (empty($aEvaluaciones)) {
            return false;
        }

        $aNotas = TableRegistry::getTableLocator()->get('CursoNotas')->find()
            ->where(['contenido_curso_id IN' => array_map(function ($o) { return $o->id; }, $aEvaluaciones)])
            ->toArray();

        $aNotasMap = [];
        foreach ($aNotas as $oNota) {
            $nEstId = (int)$oNota->estudiante_id;
            $nContId = (int)$oNota->contenido_curso_id;
            $aNotasMap[$nEstId][$nContId] = $oNota->calificacion;
        }

        $aGrillas = [];
        foreach ($aEstudiantes as $oEc) {
            $nEstId = (int)$oEc->estudiante_id;
            $oEst = $oEc->estudiante;
            $aRow = [
                'cedula' => $oEst->cedula,
                'apellidos' => $oEst->apellidos,
                'nombres' => $oEst->nombres,
                'notas' => [],
                'total' => '',
                'final' => '',
            ];

            if ($nTipoCalificacion === 1) {
                $nA = 0;
                $nR = 0;
                foreach ($aEvaluaciones as $oCont) {
                    $sVal = $aNotasMap[$nEstId][$oCont->id] ?? '';
                    $aRow['notas'][$oCont->id] = $sVal;
                    $sVal = strtoupper(trim($sVal));
                    if ($sVal === 'A') $nA++;
                    elseif ($sVal === 'R') $nR++;
                }
                $sResultado = ($nA + $nR === 0) ? '' : ($nA >= $nR ? 'A' : 'R');
                $aRow['total'] = $sResultado;
                $aRow['final'] = $sResultado;
            } else {
                $nTotalNat = 0;
                $nTotalNorm = 0;
                $bCompleto = false;
                $bMixto = false;
                $nPrimeraEscala = 0;

                foreach ($aEvaluaciones as $oCont) {
                    $sVal = $aNotasMap[$nEstId][$oCont->id] ?? '';
                    $aRow['notas'][$oCont->id] = $sVal;

                    if (trim($sVal) === '') continue;

                    $nNota = (float)$sVal;
                    $nEscala = (int)$oCont->indicador_curso->escala_nota;
                    $nPonderacion = (int)$oCont->ponderacion;
                    $nMaxNota = ($nEscala == 2 || $nEscala == 3) ? $nPonderacion : 20;

                    $bCompleto = true;

                    if ($nPrimeraEscala === 0) {
                        $nPrimeraEscala = $nEscala;
                    } elseif ($nEscala !== $nPrimeraEscala) {
                        $bMixto = true;
                    }

                    $nTotalNat += $nNota * ($nPonderacion / 100);

                    $nNorm = $this->_normalizarNota($nNota, $nEscala, $nMaxNota);
                    $nTotalNorm += $nNorm * ($nPonderacion / 100);
                }

                if ($bCompleto) {
                    $aRow['total'] = number_format($nTotalNat, 2, '.', '');
                    if (!$bMixto && $nPrimeraEscala === 1) {
                        $aRow['final'] = (string)round($nTotalNat);
                    } else {
                        $aRow['final'] = (string)$this->_convertirAEscala20($nTotalNorm);
                    }
                }
            }

            $aGrillas[] = $aRow;
        }

        $sProcesoLabel = 'TODAS LAS EVALUACIONES';
        if ($sProceso !== null && $sProceso !== 'TODAS') {
            $sProcesoLabel = $sProceso;
        }

        return [
            'curso' => $oCurso,
            'evaluaciones' => $aEvaluaciones,
            'grillas' => $aGrillas,
            'frecuencia' => $nFrecuencia,
            'tipo_calificacion' => $nTipoCalificacion,
            'proceso_label' => $sProcesoLabel,
        ];
    }

    private function _mapearProceso($nIndicadorCursoId, $nFrecuencia)
    {
        $oInd = TableRegistry::getTableLocator()->get('IndicadorCursos')->get($nIndicadorCursoId);
        $aIndicadores = TableRegistry::getTableLocator()->get('IndicadorCursos')->find()
            ->where(['curso_id' => $oInd->curso_id])
            ->order(['IndicadorCursos.id' => 'ASC'])
            ->extract('id')
            ->toArray();

        $nIdx = array_search($nIndicadorCursoId, $aIndicadores);

        switch ((int)$nFrecuencia) {
            case 1:
                return 'TRIMESTRE';
            case 2:
                $aMap = ['S1' => 0, 'S2' => 1];
                foreach ($aMap as $sKey => $nIdxMap) {
                    if ($nIdx === $nIdxMap) return $sKey;
                }
                return 'S1';
            case 3:
                $aMap = ['T1' => 0, 'T2' => 1, 'T3' => 2];
                foreach ($aMap as $sKey => $nIdxMap) {
                    if ($nIdx === $nIdxMap) return $sKey;
                }
                return 'T1';
            default:
                return 'TRIMESTRE';
        }
    }

    private function _normalizarNota($nNota, $nEscala, $nPorcentaje = 100)
    {
        switch ((int)$nEscala) {
            case 1: return ($nNota / 20) * 100;
            case 2: return ($nNota / $nPorcentaje) * 100;
            case 3: return $nNota;
            default: return 0;
        }
    }

    private function _convertirAEscala20($nValor)
    {
        $nValor = max(1, min(100, round($nValor)));
        if ($nValor <= 5)  return 1;
        if ($nValor <= 10) return 2;
        if ($nValor <= 15) return 3;
        if ($nValor <= 20) return 4;
        if ($nValor <= 25) return 5;
        if ($nValor <= 30) return 6;
        if ($nValor <= 35) return 7;
        if ($nValor <= 40) return 8;
        if ($nValor <= 45) return 9;
        if ($nValor <= 50) return 10;
        if ($nValor <= 55) return 11;
        if ($nValor <= 60) return 12;
        if ($nValor <= 65) return 13;
        if ($nValor <= 70) return 14;
        if ($nValor <= 75) return 15;
        if ($nValor <= 80) return 16;
        if ($nValor <= 85) return 17;
        if ($nValor <= 90) return 18;
        if ($nValor <= 95) return 19;
        return 20;
    }

}
