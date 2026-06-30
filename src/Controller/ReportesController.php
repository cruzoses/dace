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
        $this->Auth->allow(['downloadPdf', 'listarParroquias', 'listarEstados', 'listarMunicipios']);
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

        $dir = WWW_ROOT . 'files' . DS . 'reportes';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = 'estados_' . date('Ymd_His') . '.pdf';
        $filePath = $dir . DS . $filename;
        file_put_contents($filePath, $pdfOutput);

        $this->set('sFileName', $this->request->getAttribute('webroot') . 'files/reportes/' . $filename);
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

        $dir = WWW_ROOT . 'files' . DS . 'reportes';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = 'municipios_' . date('Ymd_His') . '.pdf';
        $filePath = $dir . DS . $filename;
        file_put_contents($filePath, $pdfOutput);

        $this->set('sFileName', $this->request->getAttribute('webroot') . 'files/reportes/' . $filename);
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

        $dir = WWW_ROOT . 'files' . DS . 'reportes';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = 'parroquias_' . date('Ymd_His') . '.pdf';
        $filePath = $dir . DS . $filename;
        file_put_contents($filePath, $pdfOutput);

        $this->set('sFileName', $this->request->getAttribute('webroot') . 'files/reportes/' . $filename);
        $this->render('showreport');
    }

}
