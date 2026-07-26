<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\IndicadorCurso $indicadorCurso
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-purple box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Editar Indicador de Curso</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'index', $indicadorCurso->curso_id],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($indicadorCurso, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->hidden('curso_id',['value' => $oCurso->id]);
                    echo $this->Form->control('indicador_id', ['label' => 'Tipo de Proceso', 'type' => 'select', 
                        'options' => $indicadores, 'empty' => true, 'class' => 'form-control select2', 'data-width' => '100%',
                        'prepend' => '<i class="fa fa-asterisk"></i>',]
                    );
                    echo $this->Form->control('desde', ['label' => 'Fecha de Inicio','type' => 'text', 
                        'class' => 'calendario', 'append' => '<i class="far fa-calendar-alt"></i>',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('hasta', ['label' => 'Fecha de Cierre', 'type' => 'text', 
                        'class' => 'calendario', 'append' => '<i class="far fa-calendar-alt"></i>',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('escala_nota', ['type' => 'select', 'options' => $aEscala,
                        'empty' => true, 'class' => 'form-control select2', 'data-width' => '100%',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('porcentaje', [
                        'type' => 'select', 'label' => 'Ponderación',
                        'options' => $aPorcentajes, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                ?>
            </div>            
            <div class="box-footer">
		        <?= $this->Form->button('<i class="fa fa-save"></i>&nbsp;Guardar',
			        ['type' => 'submit','class'=>'btn btn-success btn-flat pull-left','escape'=>false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index',$indicadorCurso->curso_id],
                    ['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
