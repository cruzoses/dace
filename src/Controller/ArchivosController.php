<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Tools\ExcelBuilder;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class ArchivosController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['exportarEstados', 'exportarMunicipios', 'exportarParroquias', 'exportarPeriodos', 'exportarDocentes']);
    }

    public function exportarEstados()
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

    public function exportarPeriodos()
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
                'Nota_Minima' => $p->nota_minima,
                'Inicio' => $p->inicio->format('d/m/Y'),
                'Cierre' => $p->cierre->format('d/m/Y'),
                'Califica' => $p->califica ? 'Si' : 'No',
                'Activo' => $p->activo ? 'Si' : 'No',
            ];
        }

        $excel = new ExcelBuilder();
        $excel->setColumns([
            'Codigo' => ['justification' => 'center'],
            'Nombre' => ['justification' => 'left'],
            'Año' => ['justification' => 'center'],
            'Nota_Minima' => ['justification' => 'center'],
            'Inicio' => ['justification' => 'center'],
            'Cierre' => ['justification' => 'center'],
            'Califica' => ['justification' => 'center'],
            'Activo' => ['justification' => 'center'],
        ]);
        $excel->setFileName('periodos');

        $content = $excel->generateExcel($data, 'LISTADO DE PERIODOS ACADÉMICOS');

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

    public function exportarDocentes()
    {
        $docentesTable = TableRegistry::getTableLocator()->get('Docentes');
        $docentes = $docentesTable->find('all', [
            'contain' => ['Departamentos', 'Usuarios'],
            'order' => ['Docentes.apellidos' => 'ASC', 'Docentes.nombres' => 'ASC']
        ]);

        $data = [];
        foreach ($docentes as $d) {
            $data[] = [
                'Cédula' => $d->cedula,
                'Nombres' => $d->nombres,
                'Apellidos' => $d->apellidos,
                'fecha_nacimiento' => $d->fecha_nacimiento ? $d->fecha_nacimiento->format('d/m/Y') : '',
                'Sexo' => $d->sexo,
                'Correo' => $d->email,
                // 'Departamento' => $d->departamento->nombre,
                // 'Usuario' => $d->has('usuario') ? $d->usuario->username : 'N/A',
                //  'Creado' => $d->created->format('d/m/Y'),
            ];
        }

        $excel = new ExcelBuilder();
        $excel->setColumns([
            'Cédula' => ['justification' => 'center'],
            'Nombres' => ['justification' => 'left'],
            'Apellidos' => ['justification' => 'left'],
            'Fecha_Nacimiento' => ['justification' => 'center'],
            'Sexo' => ['justification' => 'center'],
            'Correo' => ['justification' => 'left'],
            //'Departamento' => ['justification' => 'left'],
            //'Usuario' => ['justification' => 'center'],
            //'Creado' => ['justification' => 'center'],
        ]);
        $excel->setFileName('docentes');

        $content = $excel->generateExcel($data, 'LISTADO DE DOCENTES');

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

