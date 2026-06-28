<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class AuditoriasComponent extends Component
{
    protected $Auditorias;

    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->Auditorias = TableRegistry::getTableLocator()->get('Auditorias');
    }

    public function registrar($evento, $detalle = null)
    {
        $controller = $this->getController();
        $request = $controller->getRequest();
        $userId = $request->getSession()->read('Auth.User.id');

        $auditoria = $this->Auditorias->newEntity([
            'usuario_id' => $userId,
            'fecha' => date('Y-m-d H:i:s'),
            'evento' => $evento,
            'detalle' => $detalle,
            'host' => $request->clientIp(),
            'agente' => $request->getEnv('HTTP_USER_AGENT'),
        ]);

        $this->Auditorias->save($auditoria);
    }
}
