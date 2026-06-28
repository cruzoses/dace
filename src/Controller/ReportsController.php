<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Tools\PdfBuilder;
use Cake\Event\Event;

class ReportsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['downloadPdf']);
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
        $pdfOutput = $pdfBuilder->generateSimpleReport($data, $userAlias, 'ROLS');

        $this->autoRender = false;
        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout(null);
        $this->response = $this->response->withType('application/pdf');
        $this->response = $this->response->withHeader('Content-Disposition', 'attachment;filename="rols.pdf"');
        $this->response->getBody()->write($pdfOutput);

        return $this->response;
    }
}
