<section class="content-header">
  <h1>
    Rol
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
            <dd><?= h($rol->nombre) ?></dd>
            <dt scope="row"><?= __('Id') ?></dt>
            <dd><?= $this->Number->format($rol->id) ?></dd>
            <dt scope="row"><?= __('Created') ?></dt>
            <dd><?= h($rol->created) ?></dd>
            <dt scope="row"><?= __('Modified') ?></dt>
            <dd><?= h($rol->modified) ?></dd>
            <dt scope="row"><?= __('Activo') ?></dt>
            <dd><?= $rol->activo ? __('Yes') : __('No'); ?></dd>
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
          <h3 class="box-title"><?= __('Usuarios') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($rol->usuarios)): ?>
          <table class="table table-hover">
              <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Cedula') ?></th>
                    <th scope="col"><?= __('Nombres') ?></th>
                    <th scope="col"><?= __('Apellidos') ?></th>
                    <th scope="col"><?= __('Email') ?></th>
                    <th scope="col"><?= __('Username') ?></th>
                    <th scope="col"><?= __('Password') ?></th>
                    <th scope="col"><?= __('Activo') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                    <th scope="col"><?= __('Modified') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
              <?php foreach ($rol->usuarios as $usuarios): ?>
              <tr>
                    <td><?= h($usuarios->id) ?></td>
                    <td><?= h($usuarios->cedula) ?></td>
                    <td><?= h($usuarios->nombres) ?></td>
                    <td><?= h($usuarios->apellidos) ?></td>
                    <td><?= h($usuarios->email) ?></td>
                    <td><?= h($usuarios->username) ?></td>
                    <td><?= h($usuarios->password) ?></td>
                    <td><?= h($usuarios->activo) ?></td>
                    <td><?= h($usuarios->created) ?></td>
                    <td><?= h($usuarios->modified) ?></td>
                      <td class="actions text-right">
                      <?= $this->Html->link(__('View'), ['controller' => 'Usuarios', 'action' => 'view', $usuarios->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'Usuarios', 'action' => 'edit', $usuarios->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'Usuarios', 'action' => 'delete', $usuarios->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usuarios->id), 'class'=>'btn btn-danger btn-xs']) ?>
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
