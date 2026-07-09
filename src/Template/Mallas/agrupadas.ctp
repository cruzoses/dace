<?php
/**
 * @var \App\Model\Entity\Malla[] $mallasAgrupadas
 * @var \App\View\AppView $this
 */
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-sace box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Mallas Agrupadas</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" id="goSearch" title="Buscar">
                        <i class="fa fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <?= $this->Html->link('<i class="fa fa-times"></i>',
                        ['action' => 'homepage'], ['class'=>'btn btn-box-tool','escape' => false])
                    ?>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <div class="oculto" id="buscar">
                    <?= $this->element('search_form', [
                        'title' => 'Buscar Mallas Agrupadas',
                        'searchFields' => $searchFields,
                        'filtros' => $filtros,
                        'url' => ['controller' => 'Mallas', 'action' => 'agrupadas'],
                        'closeUrl' => ['action' => 'agrupadas'],
                    ]);?>
                </div>
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th scope="col">Carrera</th>
                            <th scope="col">Programa</th>
                            <th scope="col">Trayecto</th>
                            <th scope="col" class="text-center">Cant. Asignaturas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mallasAgrupadas as $malla): ?>
                            <tr>
                                <td><?= h($malla->carrera_codigo) ?></td>
                                <td><?= h($malla->programa_codigo) ?></td>
                                <td><?= h($malla->trayecto_codigo) ?></td>
                                <td class="text-center"><?= $this->Number->format($malla->total_asignaturas) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="no-padding">
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="paginator">
                                    <ul class="pagination pagination-sm">
                                        <?= $this->Paginator->first('<i class="fa fa-angle-double-left"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                        <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                        <?= $this->Paginator->numbers(['before' => '','after' => '']) ?>
                                        <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                        <?= $this->Paginator->last('<i class="fa fa-angle-double-right"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                    </ul>
                                    <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">
                <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;'.__('Go Back'),
                    ['action' => 'homepage'], ['class'=>'btn bg-maroon pull-right','escape' => false])
                ?>
            </div>
        </div>
    </div>
</div>

<?php $this->start('script'); ?>
<?= $this->Html->script('mallas') ?>
<script>
var MALLAS_PROGRAMAS_URL = '<?= $this->Url->build(['controller' => 'Mallas', 'action' => 'getProgramas']) ?>';
$(document).ready(initMallasBuscar);
</script>
<?php $this->end(); ?>
