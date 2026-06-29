<?php
use Cake\Core\Configure;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-database"></i>&nbsp;Error de base de datos</h3>
            </div>
            <div class="box-body">
                <div class="callout callout-danger">
                    <h4><i class="fa fa-warning"></i>&nbsp;Error en la conexion a la base de datos</h4>
                    <p>
                        Ha ocurrido un error de PDO al intentar ejecutar la consulta.
                    </p>
                </div>

                <?php if (isset($message)): ?>
                <p><strong>Mensaje:</strong> <?= h($message) ?></p>
                <?php endif; ?>

                <?php if (isset($code)): ?>
                <p><strong>Codigo:</strong> <?= h($code) ?></p>
                <?php endif; ?>

                <?php if (isset($error) && Configure::read('debug')): ?>
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h4 class="box-title">Detalle del error</h4>
                    </div>
                    <div class="box-body">
                        <pre class="prettyprint" style="font-size:12px;"><?= h($error) ?></pre>
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
