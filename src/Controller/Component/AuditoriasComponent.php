<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Log\Log;
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

        $detalle = (string)$detalle;
        if (function_exists('mb_strcut')) {
            $detalle = mb_strcut($detalle, 0, 65000, 'UTF-8');
        } elseif (strlen($detalle) > 65000) {
            $detalle = substr($detalle, 0, 65000);
        }

        $auditoria = $this->Auditorias->newEntity([
            'usuario_id' => $userId,
            'fecha' => date('Y-m-d H:i:s'),
            'evento' => $evento,
            'detalle' => $detalle,
            'host' => $request->clientIp(),
            'agente' => $request->getEnv('HTTP_USER_AGENT'),
        ]);

        try {
            $this->Auditorias->save($auditoria);
        } catch (\Exception $e) {
            Log::error('No se pudo registrar la auditoría: ' . $e->getMessage());
        }
    }
}
