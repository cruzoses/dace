<section class="content-header">
  <h1>
    Mension Carrera
    <small><?php echo __('View'); ?></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo $this->Url->build(['action' => 'index']); ?>"><i class="fa fa-dashboard"></i> <?php echo __('Home'); ?></a></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <i class="fa fa-info"></i>
          <h3 class="box-title"><?php echo __('Information'); ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <dl class="dl-horizontal">
            <dt scope="row"><?= __('Nombre') ?></dt>
            <dd><?= h($mensionCarrera->nombre) ?></dd>
            <dt scope="row"><?= __('Id') ?></dt>
            <dd><?= $this->Number->format($mensionCarrera->id) ?></dd>
            <dt scope="row"><?= __('Created') ?></dt>
            <dd><?= h($mensionCarrera->created) ?></dd>
            <dt scope="row"><?= __('Modified') ?></dt>
            <dd><?= h($mensionCarrera->modified) ?></dd>
            <dt scope="row"><?= __('Activa') ?></dt>
            <dd><?= $mensionCarrera->activa ? __('Yes') : __('No'); ?></dd>
          </dl>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <i class="fa fa-share-alt"></i>
          <h3 class="box-title"><?= __('Carreras') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($mensionCarrera->carreras)): ?>
          <table class="table table-hover">
              <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Codigo') ?></th>
                    <th scope="col"><?= __('Nombre') ?></th>
                    <th scope="col"><?= __('Mension Carrera Id') ?></th>
                    <th scope="col"><?= __('Titulo Otorgado') ?></th>
                    <th scope="col"><?= __('Activa') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                    <th scope="col"><?= __('Modified') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
              <?php foreach ($mensionCarrera->carreras as $carreras): ?>
              <tr>
                    <td><?= h($carreras->id) ?></td>
                    <td><?= h($carreras->codigo) ?></td>
                    <td><?= h($carreras->nombre) ?></td>
                    <td><?= h($carreras->mension_carrera_id) ?></td>
                    <td><?= h($carreras->titulo_otorgado) ?></td>
                    <td><?= h($carreras->activa) ?></td>
                    <td><?= h($carreras->created) ?></td>
                    <td><?= h($carreras->modified) ?></td>
                      <td class="actions text-right">
                      <?= $this->Html->link(__('View'), ['controller' => 'Carreras', 'action' => 'view', $carreras->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'Carreras', 'action' => 'edit', $carreras->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'Carreras', 'action' => 'delete', $carreras->id], ['confirm' => __('Are you sure you want to delete # {0}?', $carreras->id), 'class'=>'btn btn-danger btn-xs']) ?>
                  </td>
              </tr>
              <?php endforeach; ?>
          </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
