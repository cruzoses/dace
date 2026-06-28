<?php
use Cake\Core\Configure;
use Cake\Utility\Inflector;

$class = isset($controller) ? Inflector::camelize($controller) : (isset($mailer) ? Inflector::camelize($mailer) : '');
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-exclamation-triangle"></i>&nbsp;Metodo no encontrado</h3>
            </div>
            <div class="box-body">
                <div class="callout callout-danger">
                    <h4><i class="fa fa-warning"></i>&nbsp;Error en la aplicacion</h4>
                    <p>
                        La accion <strong><?= h($action) ?></strong> no esta definida en el controlador
                        <strong><?= h($class) ?></strong>.
                    </p>
                </div>

                <p>Debe crear el metodo <code><?= h($action) ?>()</code> en el controlador
                <code><?= h($class) ?></code>.</p>

                <?php if (Configure::read('debug')): ?>
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h4 class="box-title">Ejemplo de codigo</h4>
                    </div>
                    <div class="box-body">
                        <pre class="prettyprint" style="font-size:12px;">
&lt;?php
namespace App\Controller;

use App\Controller\AppController;

class <?= h($class) ?> extends AppController
{
    public function <?= h($action) ?>()
    {

    }
}
                        </pre>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <?= $this->Html->link('<i class="fa fa-home"></i>&nbsp;Ir al inicio', '/', ['class' => 'btn btn-default', 'escape' => false]) ?>
            </div>
        </div>
    </div>
</div>
