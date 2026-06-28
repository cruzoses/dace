<?php
use Cake\Core\Configure;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-exclamation-triangle"></i>&nbsp;Plantilla no encontrada</h3>
            </div>
            <div class="box-body">
                <div class="callout callout-danger">
                    <h4><i class="fa fa-warning"></i>&nbsp;Error en la aplicacion</h4>
                    <p><?= h($message) ?></p>
                </div>

                <p>Debe crear el archivo <code><?= h($file) ?></code> en la carpeta de vistas
                del controlador correspondiente.</p>

                <p>URL solicitada: <code><?= h($url) ?></code></p>

                <?php if (Configure::read('debug')): ?>
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h4 class="box-title">Ruta esperada</h4>
                    </div>
                    <div class="box-body">
                        <pre class="prettyprint" style="font-size:12px;"><?= h($file) ?></pre>
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
