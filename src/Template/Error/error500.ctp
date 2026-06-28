<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-exclamation-triangle"></i>&nbsp;Error interno del servidor</h3>
            </div>
            <div class="box-body">
                <div class="callout callout-danger">
                    <h4><i class="fa fa-warning"></i>&nbsp;Error en la aplicacion</h4>
                    <p><?= h($message) ?></p>
                </div>

                <?php if (Configure::read('debug')): ?>
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h4 class="box-title">Depuracion</h4>
                    </div>
                    <div class="box-body">
                        <pre class="prettyprint" style="font-size:12px;"><?= h($error->getMessage()) ?></pre>

                        <p><strong>Archivo:</strong> <code><?= h($error->getFile()) ?></code></p>
                        <p><strong>Linea:</strong> <code><?= h($error->getLine()) ?></code></p>

                        <?php if (!empty($error->queryString)): ?>
                        <p><strong>SQL Query:</strong></p>
                        <pre class="prettyprint"><?= h($error->queryString) ?></pre>
                        <?php endif; ?>

                        <?php if (!empty($error->params)): ?>
                        <p><strong>Parametros SQL:</strong></p>
                        <pre class="prettyprint"><?= Debugger::dump($error->params) ?></pre>
                        <?php endif; ?>
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
