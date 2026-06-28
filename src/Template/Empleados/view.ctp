<section class="content-header">
  <h1>
    Empleado
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
            <dt scope="row"><?= __('Nombres') ?></dt>
            <dd><?= h($empleado->nombres) ?></dd>
            <dt scope="row"><?= __('Apellidos') ?></dt>
            <dd><?= h($empleado->apellidos) ?></dd>
            <dt scope="row"><?= __('Sexo') ?></dt>
            <dd><?= h($empleado->sexo) ?></dd>
            <dt scope="row"><?= __('Email') ?></dt>
            <dd><?= h($empleado->email) ?></dd>
            <dt scope="row"><?= __('Telefonos') ?></dt>
            <dd><?= h($empleado->telefonos) ?></dd>
            <dt scope="row"><?= __('Token') ?></dt>
            <dd><?= h($empleado->token) ?></dd>
            <dt scope="row"><?= __('Usuario') ?></dt>
            <dd><?= $empleado->has('usuario') ? $this->Html->link($empleado->usuario->alias, ['controller' => 'Usuarios', 'action' => 'view', $empleado->usuario->id]) : '' ?></dd>
            <dt scope="row"><?= __('Id') ?></dt>
            <dd><?= $this->Number->format($empleado->id) ?></dd>
            <dt scope="row"><?= __('Cedula') ?></dt>
            <dd><?= $this->Number->format($empleado->cedula) ?></dd>
            <dt scope="row"><?= __('Fecha Nacimiento') ?></dt>
            <dd><?= h($empleado->fecha_nacimiento) ?></dd>
            <dt scope="row"><?= __('Created') ?></dt>
            <dd><?= h($empleado->created) ?></dd>
            <dt scope="row"><?= __('Modified') ?></dt>
            <dd><?= h($empleado->modified) ?></dd>
            <dt scope="row"><?= __('Activo') ?></dt>
            <dd><?= $empleado->activo ? __('Yes') : __('No'); ?></dd>
          </dl>
        </div>
      </div>
    </div>
  </div>

</section>
