<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Tools\PdfBuilder;
use Cake\Event\Event;

class ReportesController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['downloadPdf', 'listarParroquias', 'listarEstados', 'listarMunicipios', 'download']);
    }

    public function downloadPdf()
    {
        $this->loadModel('Rols');

        $rols = $this->Rols->find('all', [
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
        $this->loadModel('Sedes');

        $sedes = $this->Sedes->find('all', [
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

        $pdfBuilder = new PdfBuilder();

        $pdfOutput = $pdfBuilder->generateSimpleReport($data, 'LISTADO DE SEDES ACADÉMICAS');

        $reportConfig = $this->_getReportConfig();
        $filename = 'sedes_' . date('Ymd_His') . '.pdf';
        file_put_contents($reportConfig['path'] . DS . $filename, $pdfOutput);

        $this->set('sFileName', $reportConfig['webroot'] . $filename);
        $this->render('showreport');
    }

    public function listarCarreras()
    {
        $this->loadModel('Carreras');

        $carreras = $this->Carreras->find('all', [
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

        $this->set('sFileName', $reportConfig['webroot'] . $filename);
        $this->render('showreport');
    }

    public function listarEstados()
    {
        $this->loadModel('Estados');

        $estados = $this->Estados->find('all', [
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

        $this->set('sFileName', $reportConfig['webroot'] . $filename);
        $this->render('showreport');
    }

    public function listarMunicipios()
    {
        $this->loadModel('Municipios');

        $municipios = $this->Municipios->find('all', [
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

        $this->set('sFileName', $reportConfig['webroot'] . $filename);
        $this->render('showreport');
    }

    public function listarParroquias()
    {
        $this->loadModel('Parroquias');

        $parroquias = $this->Parroquias->find('all', [
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

        $this->set('sFileName', $reportConfig['webroot'] . $filename);
        $this->render('showreport');
    }

    public function listarAsignaturas()
    {
        $this->loadModel('Asignaturas');

        $asignaturas = $this->Asignaturas->find('all', [
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

        $this->set('sFileName', $reportConfig['webroot'] . $filename);
        $this->render('showreport');
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
