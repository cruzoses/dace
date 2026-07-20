<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Reportes\SituacionAcademica;
use App\Tools\PdfBuilder;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class ReportesController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([
            'downloadPdf', 'listarParroquias', 'listarPeriodos', 'listarEstados', 'listarMunicipios', 'listarDocentes',
            'listarUsuarios', 'listarAulas', 'listarMallas', 'getProgramas', 'download']
        );
    }

	public function isAuthorized($user = null)
	{
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] && $this->tienePermiso([1,2,3]) )
        {
            return true;
        }
		return parent::isAuthorized($user);
	}    

    public function fichaEstudiante($id = null)
    {
        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($id, [
            'contain' => ['Usuarios', 'EstudianteProgramas' => ['Programas']]
        ]);

        $aGeneros = Configure::read('aGeneros');
        $aEdoCivil = Configure::read('aEstadoCivil');
        $aOrigen = Configure::read('aTipoDoc');

        $fotoPath = WWW_ROOT . 'img' . DS . 'site' . DS . 'usuario.jpg';
        if ($estudiante->has('usuario') && !empty($estudiante->usuario->foto)) {
            $ext = strtolower(pathinfo($estudiante->usuario->foto, PATHINFO_EXTENSION));
            $candidate = WWW_ROOT . 'files' . DS . 'fotos' . DS . $estudiante->cedula . '.' . $ext;
            if (file_exists($candidate)) {
                $fotoPath = $candidate;
            }
        }

        $preamble = [];

        $preamble[] = ['CÉDULA', $aOrigen[$estudiante->origen] . ' ' . $estudiante->cedula];
        $preamble[] = ['APELLIDOS', $estudiante->apellidos];
        $preamble[] = ['NOMBRES', $estudiante->nombres];
        $preamble[] = ['FECHA NAC.', $estudiante->fecha_nacimiento->format('d/m/Y')];
        $preamble[] = ['SEXO', $aGeneros[$estudiante->sexo]];
        $preamble[] = ['ESTADO CIVIL', $aEdoCivil[$estudiante->estado_civil]];
        $preamble[] = ['DISCAPACITADO', $estudiante->discapacitado ? 'SI' : 'NO'];
        $preamble[] = ['LUGAR NAC.', $estudiante->lugar_nacimiento ?? ''];
        $preamble[] = ['DIRECCIÓN', $estudiante->direccion];
        $preamble[] = ['TELÉFONOS', $estudiante->telefonos ?? ''];
        $preamble[] = ['EMAIL', $estudiante->email];

        $programs = [];

        foreach ($estudiante->estudiante_programas as $ep) {
            $programs[] = [
                'Código' => $ep->has('programa') ? $ep->programa->codigo : '',
                'Nombre del Programa' => $ep->has('programa') ? $ep->programa->nombre : '',
                'Activo' => $ep->activo ? 'Si' : 'No',
                'Registrado' => $ep->created ? $ep->created->format('d/m/Y') : '',
            ];
        }

        $pdfBuilder = new PdfBuilder();
        $pdfBuilder->setColumns([
            'Código' => ['justification' => 'center', 'width' => 60],
            'Nombre del Programa' => ['justification' => 'left', 'width' => 280],
            'Activo' => ['justification' => 'center', 'width' => 60],
            'Registrado' => ['justification' => 'center', 'width' => 80],
        ]);

        $pdfOutput = $pdfBuilder->generateFichaReport($preamble, $programs, 'FICHA DEL ESTUDIANTE - ' . strtoupper($estudiante->apellidos . ' ' . $estudiante->nombres), $fotoPath);

        $reportConfig = $this->_getReportConfig();
        $filename = 'ficha_estudiante_' . $estudiante->id . '_' . date('Ymd_His') . '.pdf';
        file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);
        $sFileName = $reportConfig['webroot'] . $filename;

        $this->set(compact('sFileName'));
        $this->set('noData', false);
        $this->render('showreport');
    }

    public function situacionAcademica($estudianteId = null, $programaId = null)
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

        $situaciones = [];
        foreach ($programas as $programa) {
            $asignaturasTable = TableRegistry::getTableLocator()->get('SituacionEstudiantes');
            $asignaturas = $asignaturasTable->find()
                ->where([
                    'SituacionEstudiantes.estudiante_id' => $estudianteId,
                    'SituacionEstudiantes.programa_id' => $programa->programa_id,
                ])
                ->contain(['Asignaturas', 'Trayectos', 'Periodos'])
                ->order(['SituacionEstudiantes.trayecto_id' => 'ASC', 'SituacionEstudiantes.asignatura_id' => 'ASC'])
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
                $iraDenominador += $creditosAsig * (int)$asig->cursada;
            }

            $porcentajeAprobado = $totalCreditosPrograma > 0
                ? round(($totalCreditosAprobados / $totalCreditosPrograma) * 100, 1)
                : 0;

            $isa = $isaDenominador > 0 ? round($isaNumerador / $isaDenominador, 5) : 0;
            $ira = $iraDenominador > 0 ? round($iraNumerador / $iraDenominador, 5) : 0;

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

        $report = new SituacionAcademica($estudiante, $situaciones);
        $result = $report->generate();

        $this->set($result);
        $this->set('estudianteId', $estudianteId);
        $this->render('/Datos/visorpdf');
    }

    public function notasCertificadas($estudianteId = null, $programaId = null)
    {
        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($estudianteId);

        $programasTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');
        $programa = $programasTable->find()
            ->where([
                'EstudianteProgramas.estudiante_id' => $estudianteId,
                'EstudianteProgramas.programa_id' => $programaId,
            ])
            ->contain(['Carreras', 'Programas'])
            ->first();

        $asignaturasTable = TableRegistry::getTableLocator()->get('SituacionEstudiantes');
        $asignaturas = $asignaturasTable->find()
            ->where([
                'SituacionEstudiantes.estudiante_id' => $estudianteId,
                'SituacionEstudiantes.programa_id' => $programaId,
            ])
            ->contain(['Asignaturas', 'Trayectos', 'Periodos'])
            ->order(['SituacionEstudiantes.trayecto_id' => 'ASC', 'SituacionEstudiantes.asignatura_id' => 'ASC'])
            ->toArray();

        $mallasTable = TableRegistry::getTableLocator()->get('Mallas');
        $mallas = $mallasTable->find()
            ->where(['Mallas.programa_id' => $programaId])
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
            if (!empty($asig->acumulado) && (int)$asig->acumulado > 0) {
                $iraNumerador += (int)$asig->acumulado;
            } else {
                $notaIRA = $esCualitativa ? $notaISA : (float)$asig->calificacion;
                $iraNumerador += $notaIRA * (int)$asig->asignatura->creditos;
            }
            $iraDenominador += (int)$asig->asignatura->creditos * (int)$asig->cursada;
        }

        $porcentajeAprobado = $totalCreditosPrograma > 0
            ? round(($totalCreditosAprobados / $totalCreditosPrograma) * 100, 2) : 0;
        $ira = $iraDenominador > 0 ? round($iraNumerador / $iraDenominador, 5) : 0;

        $resultado = [
            'totalAsignaturas' => $totalAsignaturas,
            'totalCreditosAprobados' => $totalCreditosAprobados,
            'totalAsignaturasAprobadas' => $totalAsignaturasAprobadas,
            'ira' => $ira,
            'porcentajeAprobado' => $porcentajeAprobado,
        ];

        $report = new \App\Reportes\NotasCertificadas($estudiante, $programa, $asignaturas, $mallasPorAsignatura, $resultado);
        $result = $report->generate();

        $this->set($result);
        $this->set('estudianteId', $estudianteId);
        $this->render('/Datos/visorpdf');
    }

    public function culminacionPrograma($estudianteId = null, $programaId = null)
    {
        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($estudianteId);

        $programasTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');
        $programa = $programasTable->find()
            ->where([
                'EstudianteProgramas.estudiante_id' => $estudianteId,
                'EstudianteProgramas.programa_id' => $programaId,
            ])
            ->contain(['Carreras', 'Programas'])
            ->first();

        $report = new \App\Reportes\CulminacionPrograma($estudiante, $programa);
        $result = $report->generate();

        $this->set($result);
        $this->set('estudianteId', $estudianteId);
        $this->render('/Datos/visorpdf');
    }

    public function downloadPdf()
    {
        $rolsTable = TableRegistry::getTableLocator()->get('Rols');
        $rols = $rolsTable->find('all', [
            'order' => ['Rols.nombre' => 'ASC']
        ]);

        $data = [];
        foreach ($rols as $rol) {
            $data[] = [
                'Codigo' => $rol->id,
                'Nombre' => $rol->nombre,
                'Estatus' => $rol->activo ? 'Activo' : 'Inactivo',
                'Creado' => $rol->created->format('d/m/Y'),
            ];
        }

        $userAlias = $this->getRequest()->getSession()->read('Auth.User.alias');

        $pdfBuilder = new PdfBuilder();
        $pdfBuilder->setColumns([
            'Codigo' => ['justification' => 'center', 'width' => 60],
            'Nombre' => ['justification' => 'left', 'width' => 240],
            'Estatus' => ['justification' => 'center', 'width' => 80],
            'Creado' => ['justification' => 'center', 'width' => 120],
        ]);
        
        $pdfOutput = $pdfBuilder->generateSimpleReport($data, 'REPORTE DE TIPOS DE USUARIO');

        $this->autoRender = false;
        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout(null);
        $this->response = $this->response->withType('application/pdf');
        $this->response = $this->response->withHeader('Content-Disposition', 'attachment;filename="rols.pdf"');
        $this->response->getBody()->write($pdfOutput);

        return $this->response;
    }

    public function listarSedes()
    {
        $sedesTable = TableRegistry::getTableLocator()->get('Sedes');
        $sedes = $sedesTable->find('all', [
            'order' => ['Sedes.codigo' => 'ASC']
        ]);

        $data = [];
        foreach ($sedes as $sede) {
            $data[] = [
                'Codigo' => $sede->id,
                'Nombre' => $sede->nombre,
                'Responsable' => $sede->responsable,
                'Creado' => $sede->created->format('d/m/Y'),
            ];
        }

        $noData = empty($data);
        $sFileName = '';
        if (!$noData) {
            $pdfBuilder = new PdfBuilder();

            $pdfOutput = $pdfBuilder->generateSimpleReport($data, 'LISTADO DE SEDES ACADÉMICAS');

            $reportConfig = $this->_getReportConfig();
            $filename = 'sedes_' . date('Ymd_His') . '.pdf';
            file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);
            $sFileName = $reportConfig['webroot'] . $filename;
        }
        $this->set(compact('sFileName', 'noData'));
        $this->render('showreport');
    }

    public function listarCarreras()
    {
        $carrerasTable = TableRegistry::getTableLocator()->get('Carreras');
        $carreras = $carrerasTable->find('all', [
            'contain' => ['MensionCarreras'],
            'order' => ['Carreras.id' => 'ASC']
        ]);

        $data = [];
        foreach ($carreras as $carrera) {
            $data[] = [
                'Codigo' => $carrera->codigo,
                'Nombre' => $carrera->nombre,
                'Mension' => $carrera->has('mension_carrera') ? $carrera->mension_carrera->nombre : '',
                'Titulo' => $carrera->titulo_otorgado,
                'Creado' => $carrera->created->format('d/m/Y'),
            ];
        }

        $noData = empty($data);
        $sFileName = '';
        if (!$noData) {
            $pdfBuilder = new PdfBuilder('landscape');
            $pdfBuilder->setColumns([
                'Codigo' => ['justification' => 'center', 'width' => 70],
                'Nombre' => ['justification' => 'left', 'width' => 240],
                'Mension' => ['justification' => 'left', 'width' => 150],
                'Titulo' => ['justification' => 'left', 'width' => 160],
                'Creado' => ['justification' => 'center', 'width' => 80],
            ]);

            $pdfOutput = $pdfBuilder->generateSimpleReport($data, 'LISTADO DE CARRERAS');

            $reportConfig = $this->_getReportConfig();
            $filename = 'carreras_' . date('Ymd_His') . '.pdf';
            file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);
            $sFileName = $reportConfig['webroot'] . $filename;
        }
        $this->set(compact('sFileName', 'noData'));
        $this->render('showreport');
    }

    public function listarEstados()
    {
        $estadosTable = TableRegistry::getTableLocator()->get('Estados');
        $estados = $estadosTable->find('all', [
            'contain' => ['Paises'],
            'order' => ['Paises.nombre' => 'ASC', 'Estados.nombre' => 'ASC']
        ]);

        $data = [];
        foreach ($estados as $e) {
            $data[] = [
                'Codigo' => $e->id,
                'Pais' => $e->paise->nombre,
                'Nombre' => $e->nombre,
                'Creado' => $e->created->format('d/m/Y'),
            ];
        }

        $noData = empty($data);
        $sFileName = '';
        if (!$noData) {
            $pdfBuilder = new PdfBuilder();
            $pdfBuilder->setColumns([
                'Codigo' => ['justification' => 'center', 'width' => 50],
                'Pais' => ['justification' => 'left', 'width' => 180],
                'Nombre' => ['justification' => 'left', 'width' => 200],
                'Creado' => ['justification' => 'center', 'width' => 70],
            ]);

            $pdfOutput = $pdfBuilder->generateSimpleReport($data, 'LISTADO DE ESTADOS');

            $reportConfig = $this->_getReportConfig();
            $filename = 'estados_' . date('Ymd_His') . '.pdf';
            file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);
            $sFileName = $reportConfig['webroot'] . $filename;
        }
        $this->set(compact('sFileName', 'noData'));
        $this->render('showreport');
    }

    public function listarMunicipios()
    {
        $municipiosTable = TableRegistry::getTableLocator()->get('Municipios');
        $municipios = $municipiosTable->find('all', [
            'contain' => ['Estados'],
            'order' => ['Estados.nombre' => 'ASC', 'Municipios.nombre' => 'ASC']
        ]);

        $data = [];
        foreach ($municipios as $m) {
            $data[] = [
                'Codigo' => $m->id,
                'Estado' => $m->estado->nombre,
                'Nombre' => $m->nombre,
                'Creado' => $m->created->format('d/m/Y'),
            ];
        }

        $noData = empty($data);
        $sFileName = '';
        if (!$noData) {
            $pdfBuilder = new PdfBuilder();
            $pdfBuilder->setColumns([
                'Codigo' => ['justification' => 'center', 'width' => 50],
                'Estado' => ['justification' => 'left', 'width' => 180],
                'Nombre' => ['justification' => 'left', 'width' => 200],
                'Creado' => ['justification' => 'center', 'width' => 70],
            ]);

            $pdfOutput = $pdfBuilder->generateSimpleReport($data, 'LISTADO DE MUNICIPIOS');

            $reportConfig = $this->_getReportConfig();
            $filename = 'municipios_' . date('Ymd_His') . '.pdf';
            file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);
            $sFileName = $reportConfig['webroot'] . $filename;
        }
        $this->set(compact('sFileName', 'noData'));
        $this->render('showreport');
    }

    public function listarParroquias()
    {
        $parroquiasTable = TableRegistry::getTableLocator()->get('Parroquias');
        $parroquias = $parroquiasTable->find('all', [
            'contain' => ['Municipios'],
            'order' => ['Municipios.nombre' => 'ASC', 'Parroquias.nombre' => 'ASC']
        ]);

        $data = [];
        foreach ($parroquias as $p) {
            $data[] = [
                'Codigo' => $p->id,
                'Municipio' => $p->municipio->nombre,
                'Nombre' => $p->nombre,
                'Creado' => $p->created->format('d/m/Y'),
            ];
        }

        $noData = empty($data);
        $sFileName = '';
        if (!$noData) {
            $pdfBuilder = new PdfBuilder();
            $pdfBuilder->setColumns([
                'Codigo' => ['justification' => 'center', 'width' => 50],
                'Municipio' => ['justification' => 'left', 'width' => 180],
                'Nombre' => ['justification' => 'left', 'width' => 200],
                'Creado' => ['justification' => 'center', 'width' => 70],
            ]);

            $pdfOutput = $pdfBuilder->generateSimpleReport($data, 'LISTADO DE PARROQUIAS');

            $reportConfig = $this->_getReportConfig();
            $filename = 'parroquias_' . date('Ymd_His') . '.pdf';
            file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);
            $sFileName = $reportConfig['webroot'] . $filename;
        }
        $this->set(compact('sFileName', 'noData'));
        $this->render('showreport');
    }

    public function listarPeriodos()
    {
        $periodosTable = TableRegistry::getTableLocator()->get('Periodos');
        $periodos = $periodosTable->find('all', [
            'order' => ['Periodos.lapso' => 'DESC', 'Periodos.codigo' => 'ASC']
        ]);

        $data = [];
        foreach ($periodos as $p) {
            $data[] = [
                'Codigo' => $p->codigo,
                'Nombre' => $p->nombre,
                'Año' => $p->lapso,
                'Inicio' => $p->inicio->format('d/m/Y'),
                'Cierre' => $p->cierre->format('d/m/Y'),
                'Activo' => $p->activo ? 'Si' : 'No',
            ];
        }

        $noData = empty($data);
        $sFileName = '';
        if (!$noData) {
            $pdfBuilder = new PdfBuilder();
            $pdfBuilder->setColumns([
                'Codigo' => ['justification' => 'center', 'width' => 60],
                'Nombre' => ['justification' => 'left', 'width' => 180],
                'Año' => ['justification' => 'center', 'width' => 60],
                'Inicio' => ['justification' => 'center', 'width' => 80],
                'Cierre' => ['justification' => 'center', 'width' => 80],
                'Activo' => ['justification' => 'center', 'width' => 50],
            ]);

            $pdfOutput = $pdfBuilder->generateSimpleReport($data, 'LISTADO DE PERIODOS ACADÉMICOS');

            $reportConfig = $this->_getReportConfig();
            $filename = 'periodos_' . date('Ymd_His') . '.pdf';
            file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);
            $sFileName = $reportConfig['webroot'] . $filename;
        }
        $this->set(compact('sFileName', 'noData'));
        $this->render('showreport');
    }

    public function listarAsignaturas()
    {
        $asignaturasTable = TableRegistry::getTableLocator()->get('Asignaturas');
        $asignaturas = $asignaturasTable->find('all', [
            'contain' => ['GrupoAsignaturas'],
            'order' => ['Asignaturas.codigo' => 'ASC']
        ]);

        $data = [];
        foreach ($asignaturas as $a) {
            $data[] = [
                'Codigo' => $a->codigo,
                'Grupo' => $a->has('grupo_asignatura') ? $a->grupo_asignatura->nombre : '',
                'Nombre' => $a->nombre,
                'Creditos' => $a->creditos,
            ];
        }

        $noData = empty($data);
        $sFileName = '';
        if (!$noData) {
            $pdfBuilder = new PdfBuilder();
            $pdfBuilder->setColumns([
                'Codigo' => ['justification' => 'center', 'width' => 60],
                'Grupo' => ['justification' => 'left', 'width' => 140],
                'Nombre' => ['justification' => 'left', 'width' => 220],
                'Creditos' => ['justification' => 'center', 'width' => 80],
            ]);

            $pdfOutput = $pdfBuilder->generateSimpleReport($data, 'LISTADO DE ASIGNATURAS');

            $reportConfig = $this->_getReportConfig();
            $filename = 'asignaturas_' . date('Ymd_His') . '.pdf';
            file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);
            $sFileName = $reportConfig['webroot'] . $filename;
        }
        $this->set(compact('sFileName', 'noData'));
        $this->render('showreport');
    }

    public function listarAulas()
    {
        $aulasTable = TableRegistry::getTableLocator()->get('Aulas');
        $sedesTable = TableRegistry::getTableLocator()->get('Sedes');

        $sede_id = $this->request->getQuery('sede_id');

        if ($sede_id === null) {
            $sedes = $sedesTable->find('list', [
                'keyField' => 'id',
                'valueField' => 'nombre',
                'order' => ['Sedes.nombre' => 'ASC']
            ])->toArray();

            $this->set(compact('sedes'));
            $this->render('aulas');
            return;
        }

        $conditions = [];
        if ($sede_id !== '') {
            $conditions['Aulas.sede_id'] = (int)$sede_id;
        }

        $aulas = $aulasTable->find('all', [
            'conditions' => $conditions,
            'order' => ['Aulas.codigo' => 'ASC']
        ]);

        $sedeNombre = '';
        if ($sede_id !== '') {
            $sede = $sedesTable->get((int)$sede_id);
            $sedeNombre = $sede->nombre;
        }

        $data = [];
        foreach ($aulas as $aula) {
            $data[] = [
                'Codigo' => $aula->codigo,
                'Nombre' => $aula->nombre,
                'Capacidad' => $aula->capacidad,
                'Ubicacion' => $aula->ubicacion,
                'Condicion' => $aula->condicion ? 'Activo' : 'Inactivo',
                'Creado' => $aula->created->format('d/m/Y'),
            ];
        }

        $noData = empty($data);
        $sFileName = '';
        if (!$noData) {
            $pdfBuilder = new PdfBuilder();
            $pdfBuilder->setColumns([
                'Codigo' => ['justification' => 'center', 'width' => 60],
                'Nombre' => ['justification' => 'left', 'width' => 160],
                'Capacidad' => ['justification' => 'center', 'width' => 60],
                'Ubicacion' => ['justification' => 'left', 'width' => 160],
                'Condicion' => ['justification' => 'center', 'width' => 60],
                'Creado' => ['justification' => 'center', 'width' => 80],
            ]);

            $titulo = 'LISTADO DE AULAS';
            if ($sedeNombre) {
                $titulo .= ' - ' . strtoupper($sedeNombre);
            }

            $pdfOutput = $pdfBuilder->generateSimpleReport($data, $titulo);

            $reportConfig = $this->_getReportConfig();
            $filename = 'aulas_' . date('Ymd_His') . '.pdf';
            file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);
            $sFileName = $reportConfig['webroot'] . $filename;
        }
        $this->set(compact('sFileName', 'noData'));
        $this->render('showreport');
    }

    public function listarUsuarios()
    {
        $usuariosTable = TableRegistry::getTableLocator()->get('Usuarios');
        $usuarios = $usuariosTable->find('all', [
            'order' => ['Usuarios.id' => 'ASC']
            //'order' => ['Usuarios.apellidos' => 'ASC', 'Usuarios.nombres' => 'ASC']
        ]);

        $data = [];
        $i = 1;
        foreach ($usuarios as $u) {
            $data[] = [
                'No.' => $i++,
                'Cedula' => $u->cedula,
                'Nombres' => $u->nombres,
                'Apellidos' => $u->apellidos,
                'F.Nacimiento' => $u->fecha_nacimiento->format('d/m/Y'),
                'Activo' => $u->activo ? 'Si' : 'No',
            ];
        }

        $noData = empty($data);
        $sFileName = '';
        if (!$noData) {
            $pdfBuilder = new PdfBuilder();
            $pdfBuilder->setColumns([
                'No.' => ['justification' => 'center', 'width' => 40],
                'Cedula' => ['justification' => 'center', 'width' => 60],
                'Nombres' => ['justification' => 'left', 'width' => 140],
                'Apellidos' => ['justification' => 'left', 'width' => 140],
                'F.Nacimiento' => ['justification' => 'center', 'width' => 80],
                'Activo' => ['justification' => 'center', 'width' => 60],
            ]);

            $pdfOutput = $pdfBuilder->generateSimpleReport($data, 'LISTADO DE USUARIOS');

            $reportConfig = $this->_getReportConfig();
            $filename = 'usuarios_' . date('Ymd_His') . '.pdf';
            file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);
            $sFileName = $reportConfig['webroot'] . $filename;
        }
        $this->set(compact('sFileName', 'noData'));
        $this->render('showreport');
    }

    public function listarDocentes()
    {
        $docentesTable = TableRegistry::getTableLocator()->get('Docentes');
        $docentes = $docentesTable->find('all', [
            'order' => ['Docentes.id' => 'ASC']
            //'order' => ['Docentes.apellidos' => 'ASC', 'Docentes.nombres' => 'ASC']
        ]);

        $data = [];
        $i = 1;
        foreach ($docentes as $d) {
            $data[] = [
                'No.' => $i++,
                'Cedula' => $d->cedula,
                'Nombres' => $d->nombres,
                'Apellidos' => $d->apellidos,
                'F.Nacimiento' => $d->fecha_nacimiento->format('d/m/Y'),
                'Sexo' => $d->sexo === 'M' ? 'Masculino' : ($d->sexo === 'F' ? 'Femenino' : $d->sexo),
            ];
        }

        $noData = empty($data);
        $sFileName = '';
        if (!$noData) {
            $pdfBuilder = new PdfBuilder();
            $pdfBuilder->setColumns([
                'No.' => ['justification' => 'center', 'width' => 40],
                'Cedula' => ['justification' => 'center', 'width' => 60],
                'Nombres' => ['justification' => 'left', 'width' => 130],
                'Apellidos' => ['justification' => 'left', 'width' => 130],
                'F.Nacimiento' => ['justification' => 'center', 'width' => 80],
                'Sexo' => ['justification' => 'center', 'width' => 60],
            ]);

            $pdfOutput = $pdfBuilder->generateSimpleReport($data, 'LISTADO DE DOCENTES');

            $reportConfig = $this->_getReportConfig();
            $filename = 'docentes_' . date('Ymd_His') . '.pdf';
            file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);
            $sFileName = $reportConfig['webroot'] . $filename;
        }
        $this->set(compact('sFileName', 'noData'));
        $this->render('showreport');
    }

    public function listarMallas()
    {
        $mallasTable = TableRegistry::getTableLocator()->get('Mallas');
        $carrerasTable = TableRegistry::getTableLocator()->get('Carreras');

        $carrera_id = $this->request->getQuery('carrera_id');

        if ($carrera_id === null) {
            $carreras = $carrerasTable->find('list', [
                'keyField' => 'id',
                'valueField' => 'codename',
                'order' => ['Carreras.id' => 'ASC']
            ])->where(['Carreras.activa' => 1])->toArray();

            $trayectosTable = TableRegistry::getTableLocator()->get('Trayectos');
            $trayectos = $trayectosTable->find('list', [
                'keyField' => 'id',
                'valueField' => 'codigo',
                'order' => ['Trayectos.id' => 'ASC']
            ])->where(['Trayectos.activo' => 1])->toArray();

            $this->set(compact('carreras', 'trayectos'));
            $this->render('mallas');
            return;
        }

        $conditions = ['Mallas.carrera_id' => (int)$carrera_id];
        $programa_id = $this->request->getQuery('programa_id');
        if ($programa_id !== '' && $programa_id !== null) {
            $conditions['Mallas.programa_id'] = (int)$programa_id;
        }
        $trayecto_id = $this->request->getQuery('trayecto_id');
        if ($trayecto_id !== '' && $trayecto_id !== null) {
            $conditions['Mallas.trayecto_id'] = (int)$trayecto_id;
        }

        $mallas = $mallasTable->find('all', [
            'conditions' => $conditions,
            'contain' => ['Carreras', 'Programas', 'Trayectos', 'Asignaturas'],
            'order' => ['Programas.nombre' => 'ASC', 'Trayectos.id' => 'ASC', 'Asignaturas.nombre' => 'ASC']
        ]);

        $carreraNombre = '';
        if ($carrera_id !== '') {
            $carrera = $carrerasTable->get((int)$carrera_id);
            $carreraNombre = $carrera->nombre;
        }

        $programaNombre = '';
        if ($programa_id !== '' && $programa_id !== null) {
            $programasTable = TableRegistry::getTableLocator()->get('Programas');
            $programa = $programasTable->get((int)$programa_id);
            $programaNombre = $programa->nombre;
        }

        $trayectoNombre = '';
        if ($trayecto_id !== '' && $trayecto_id !== null) {
            $trayectosTable = TableRegistry::getTableLocator()->get('Trayectos');
            $trayecto = $trayectosTable->get((int)$trayecto_id);
            $trayectoNombre = $trayecto->codigo;
        }

        $data = [];
        $totalCreditos = 0;
        $i = 1;
        foreach ($mallas as $m) {
            $creditos = $m->has('asignatura') ? (int)$m->asignatura->creditos : 0;
            $totalCreditos += $creditos;
            $data[] = [
                'No.' => $i++,
                'Trayecto' => $m->has('trayecto') ? $m->trayecto->codigo : '',
                'Codigo' => $m->has('asignatura') ? $m->asignatura->codigo : '',
                'Asignatura' => $m->has('asignatura') ? $m->asignatura->nombre : '',
                'Creditos' => $creditos,
            ];
        }

        $noData = empty($data);
        $sFileName = '';
        if (!$noData) {
            $totalAsignaturas = count($data);

            $pdfBuilder = new PdfBuilder();
            $pdfBuilder->setColumns([
                'No.' => ['justification' => 'center', 'width' => 30],
                'Trayecto' => ['justification' => 'center', 'width' => 65],
                'Codigo' => ['justification' => 'center', 'width' => 65],
                'Asignatura' => ['justification' => 'left', 'width' => 270],
                'Creditos' => ['justification' => 'center', 'width' => 60],
            ]);

            $summary = [[
                'No.' => '',
                'Trayecto' => '',
                'Codigo' => '',
                'Asignatura' => 'Total de Asignaturas: ' . $totalAsignaturas,
                'Creditos' => '',
            ], [
                'No.' => '',
                'Trayecto' => '',
                'Codigo' => '',
                'Asignatura' => 'Total de Créditos',
                'Creditos' => $totalCreditos,
            ]];

            $titulo = 'LISTADO DE MALLAS CURRICULARES';
            if ($carreraNombre) {
                $titulo .= ' - ' . strtoupper($carreraNombre);
            }
            if ($programaNombre) {
                $titulo .= ' / ' . strtoupper($programaNombre);
            }
            if ($trayectoNombre) {
                $titulo .= ' / TRAYECTO ' . $trayectoNombre;
            }

            $pdfOutput = $pdfBuilder->generateReportWithSummary($data, $summary, $titulo);

            $reportConfig = $this->_getReportConfig();
            $filename = 'mallas_' . date('Ymd_His') . '.pdf';
            file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);
            $sFileName = $reportConfig['webroot'] . $filename;
        }
        $this->set(compact('sFileName', 'noData'));
        $this->render('showreport');
    }

    public function listarProgramas()
    {
        $programasTable = TableRegistry::getTableLocator()->get('Programas');
        $programas = $programasTable->find('all', [
            'contain' => ['Carreras'],
            'order' => ['Carreras.nombre' => 'ASC', 'Programas.nombre' => 'ASC']
        ]);

        $data = [];
        foreach ($programas as $p) {
            $data[] = [
                'Codigo' => $p->codigo,
                'Nombre' => $p->nombre,
                'Creditos' => $p->creditos,
            ];
        }

        $noData = empty($data);
        $sFileName = '';
        if (!$noData) {
            $pdfBuilder = new PdfBuilder();
            $pdfBuilder->setColumns([
                'Codigo' => ['justification' => 'center', 'width' => 80],
                'Nombre' => ['justification' => 'left', 'width' => 340],
                'Creditos' => ['justification' => 'center', 'width' => 80],
            ]);

            $pdfOutput = $pdfBuilder->generateSimpleReport($data, 'LISTADO DE PROGRAMAS');

            $reportConfig = $this->_getReportConfig();
            $filename = 'programas_' . date('Ymd_His') . '.pdf';
            file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);
            $sFileName = $reportConfig['webroot'] . $filename;
        }
        $this->set(compact('sFileName', 'noData'));
        $this->render('showreport');
    }

    public function getProgramas()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $programasTable = TableRegistry::getTableLocator()->get('Programas');
        $carrera_id = $this->request->getQuery('carrera_id');

        $programas = [];
        if ($carrera_id) {
            $programas = $programasTable->find('list', ['limit' => 200])
                ->where(['carrera_id' => $carrera_id, 'activo' => 1])
                ->toArray();
        }

        $this->set(compact('programas'));
        $this->set('_serialize', ['programas']);
    }

    public function download()
    {
        $file = $this->request->getQuery('file');
        if (!$file) {
            throw new \Cake\Http\Exception\NotFoundException();
        }
        $path = TMP . 'reportes' . DS . basename($file);
        if (!file_exists($path)) {
            throw new \Cake\Http\Exception\NotFoundException();
        }
        $this->autoRender = false;
        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout(null);
        $this->response = $this->response->withType('application/pdf');
        $this->response = $this->response->withHeader('Content-Disposition', 'inline;filename="' . $file . '"');
        $this->response->getBody()->write(file_get_contents($path));
        return $this->response;
    }

    private function _getReportConfig()
    {
        $dir = WWW_ROOT . 'files' . DS . 'reportes';
        $webroot = $this->request->getAttribute('webroot') . 'files/reportes/';

        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        if (!is_dir($dir) || !is_writable($dir)) {
            $dir = TMP . 'reportes';
            $webroot = $this->request->getAttribute('webroot') . 'reportes/download?file=';
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
        }

        return ['path' => $dir, 'webroot' => $webroot];
    }
}
