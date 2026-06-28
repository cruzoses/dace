<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;

class ReportesController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['tipousuariosPdf', 'tipousuariosPdfEz']);
    }

    public function isAuthorized($user)
    {
        return $this->tienePermiso([1,2,3,4]);
    }

    public function index()
    {
    }

    public function actadenotas()
    {
    }

    public function listamaterias()
    {
    }

    public function listarmallas()
    {
    }

    public function listacarreras()
    {
    }

    public function cursos()
    {
    }

    public function nuevoingreso()
    {
    }

    public function inscripcion()
    {
    }

    public function estudiantes()
    {
    }

    public function actodegrado()
    {
    }

    public function actasdegrado()
    {
    }

    public function ofertas()
    {
    }

    public function listaperiodos()
    {
    }

    public function listaprogramas()
    {
    }

    public function tipousuarios()
    {
        $this->loadModel('Rols');
        $rols = $this->Rols->find('all', [
            'order' => ['Rols.nombre' => 'ASC']
        ]);
        $this->set(compact('rols'));
    }

    public function tipousuariosPdf()
    {
        $this->loadModel('Rols');
        $rols = $this->Rols->find('all', [
            'order' => ['Rols.nombre' => 'ASC']
        ]);
        $this->set(compact('rols'));

        $user = $this->getRequest()->getSession()->read('Auth.User');
        $nombreUsuario = $this->_getUserName($user);

        $this->viewBuilder()->setClassName('CakePdf.Pdf');
        $this->viewBuilder()->disableAutoLayout();
        $this->viewBuilder()->setOptions(['pdfConfig' => [
            'engine' => [
                'className' => 'App.ReporteTcpdf',
                'headerImage' => WWW_ROOT . 'img' . DS . 'site' . DS . 'cintillo.png',
                'uniName' => Configure::read('Universidad.Nombre'),
                'reportTitle' => 'REPORTE DE TIPOS DE USUARIOS',
                'userName' => $nombreUsuario,
            ],
            'filename' => 'tipos_usuarios.pdf',
        ]]);
        $this->response = $this->response->withType('pdf');
    }

    public function tipousuariosPdfEz()
    {
        $this->loadModel('Rols');
        $rols = $this->Rols->find('all', [
            'order' => ['Rols.nombre' => 'ASC']
        ]);

        $user = $this->getRequest()->getSession()->read('Auth.User');
        $nombreUsuario = $this->_getUserName($user);
        $uniName = Configure::read('Universidad.Nombre');

        $pdf = new \Cezpdf('LETTER', 'portrait');
        $pdf->ezSetCmMargins(2.8, 1.5, 1.5, 1.5);

        $header = $pdf->openObject();
        $pdf->saveState();

        $imagePath = WWW_ROOT . 'img' . DS . 'site' . DS . 'cintillo.png';
        if (file_exists($imagePath)) {
            $pdf->addPngFromFile($imagePath, 30, 740, 540, 30);
        }

        $siglas = Configure::read('Universidad.Siglas');
        $pdf->addText(40, 722, 12, $siglas);
        $title = 'REPORTE DE TIPOS DE USUARIOS';
        $pdf->addText(306 - ($pdf->getTextWidth(12, $title) / 2), 722, 12, $title);
        $pdf->addText(484, 730, 8, 'Fecha: ' . date('d-m-Y'));
        $pdf->addText(484, 720, 8, 'Hora: ' . date('h:i a'));

        $pdf->line(40, 713, 570, 713);

        $pdf->restoreState();
        $pdf->closeObject();
        $pdf->addObject($header, 'all');

        $data = [];
        foreach ($rols as $rol) {
            $data[] = [
                'codigo' => $rol->id,
                'nombre' => $rol->nombre,
                'estatus' => $rol->activo ? 'Activo' : 'Inactivo',
                'creado' => $rol->created->format('d/m/Y'),
            ];
        }

        $cols = [
            'codigo' => 'Codigo',
            'nombre' => 'Nombre del Rol',
            'estatus' => 'Estatus',
            'creado' => 'Creado',
        ];

        $options = [
            'fontSize' => 8,
            'cols' => [
                'codigo' => ['justification' => 'center', 'width' => 40],
                'nombre' => ['justification' => 'left', 'width' => 280],
                'estatus' => ['justification' => 'center', 'width' => 80],
                'creado' => ['justification' => 'center', 'width' => 100],
            ],
        ];

        $pdf->ezTable($data, $cols, '', $options);

        $pdf->addText(40, 50, 6, 'Generado por: ' . $nombreUsuario . '    ' . date('d/m/Y h:i A'));
        $pdf->line(40, 42, 570, 42);

        $content = $pdf->ezOutput();

        $this->response = $this->response->withType('pdf');
        $this->response = $this->response->withStringBody($content);
        $this->response = $this->response->withDownload('tipos_usuarios_ez.pdf');

        return $this->response;
    }

    private function _getUserName($user)
    {
        $nombre = 'Desconocido';
        if (is_array($user)) {
            $nombre = $user['alias'] ?? '';
            if (empty($nombre)) {
                $nombre = trim(($user['nombre'] ?? '') . ' ' . ($user['apellido'] ?? ''));
            }
            if (empty($nombre)) {
                $nombre = $user['username'] ?? 'Desconocido';
            }
        }
        return strtoupper($nombre);
    }
}
