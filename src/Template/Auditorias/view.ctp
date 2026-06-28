<section class="content-header">
  <h1>
    Auditoria
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
            <dt scope="row"><?= __('Usuario') ?></dt>
            <dd><?= $auditoria->has('usuario') ? $this->Html->link($auditoria->usuario->alias, ['controller' => 'Usuarios', 'action' => 'view', $auditoria->usuario->id]) : '' ?></dd>
            <dt scope="row"><?= __('Evento') ?></dt>
            <dd><?= h($auditoria->evento) ?></dd>
            <dt scope="row"><?= __('Host') ?></dt>
            <dd><?= h($auditoria->host) ?></dd>
            <dt scope="row"><?= __('Agente') ?></dt>
            <dd><?= h($auditoria->agente) ?></dd>
            <dt scope="row"><?= __('Id') ?></dt>
            <dd><?= $this->Number->format($auditoria->id) ?></dd>
            <dt scope="row"><?= __('Fecha') ?></dt>
            <dd><?= h($auditoria->fecha) ?></dd>
            <dt scope="row"><?= __('Created') ?></dt>
            <dd><?= h($auditoria->created) ?></dd>
            <dt scope="row"><?= __('Modified') ?></dt>
            <dd><?= h($auditoria->modified) ?></dd>
          </dl>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <i class="fa fa-text-width"></i>
          <h3 class="box-title"><?= __('Detalle') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <?= $this->Text->autoParagraph($auditoria->detalle); ?>
        </div>
      </div>
    </div>
  </div>
</section>
