<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Tools\ExcelBuilder;
use Cake\Event\Event;

class ArchivosController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['exportarEstados', 'exportarMunicipios', 'exportarParroquias']);
    }

    public function exportarEstados()
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

        $excel = new ExcelBuilder();
        $excel->setColumns([
            'Codigo' => ['justification' => 'center'],
            'Pais' => ['justification' => 'left'],
            'Nombre' => ['justification' => 'left'],
            'Creado' => ['justification' => 'center'],
        ]);
        $excel->setFileName('estados');

        $content = $excel->generateExcel($data, 'LISTADO DE ESTADOS');

        $dir = WWW_ROOT . 'files' . DS . 'excel';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = $excel->getFileName() . '_' . date('Ymd_His') . '.xlsx';
        $filePath = $dir . DS . $filename;
        file_put_contents($filePath, $content);

        $this->autoRender = false;
        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout(null);
        $this->response = $this->response->withType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response = $this->response->withHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $this->response->getBody()->write($content);

        return $this->response;
    }

    public function exportarMunicipios()
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

        $excel = new ExcelBuilder();
        $excel->setColumns([
            'Codigo' => ['justification' => 'center'],
            'Estado' => ['justification' => 'left'],
            'Nombre' => ['justification' => 'left'],
            'Creado' => ['justification' => 'center'],
        ]);
        $excel->setFileName('municipios');

        $content = $excel->generateExcel($data, 'LISTADO DE MUNICIPIOS');

        $dir = WWW_ROOT . 'files' . DS . 'excel';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = $excel->getFileName() . '_' . date('Ymd_His') . '.xlsx';
        $filePath = $dir . DS . $filename;
        file_put_contents($filePath, $content);

        $this->autoRender = false;
        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout(null);
        $this->response = $this->response->withType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response = $this->response->withHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $this->response->getBody()->write($content);

        return $this->response;
    }

    public function exportarParroquias()
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

        $excel = new ExcelBuilder();
        $excel->setColumns([
            'Codigo' => ['justification' => 'center'],
            'Municipio' => ['justification' => 'left'],
            'Nombre' => ['justification' => 'left'],
            'Creado' => ['justification' => 'center'],
        ]);
        $excel->setFileName('parroquias');

        $content = $excel->generateExcel($data, 'LISTADO DE PARROQUIAS');

        $dir = WWW_ROOT . 'files' . DS . 'excel';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = $excel->getFileName() . '_' . date('Ymd_His') . '.xlsx';
        $filePath = $dir . DS . $filename;
        file_put_contents($filePath, $content);

        $this->autoRender = false;
        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout(null);
        $this->response = $this->response->withType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response = $this->response->withHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $this->response->getBody()->write($content);

        return $this->response;
    }
}
