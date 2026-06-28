<section class="content-header">
  <h1>
    Carrera
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
            <dt scope="row"><?= __('Codigo') ?></dt>
            <dd><?= h($carrera->codigo) ?></dd>
            <dt scope="row"><?= __('Nombre') ?></dt>
            <dd><?= h($carrera->nombre) ?></dd>
            <dt scope="row"><?= __('Mension Carrera') ?></dt>
            <dd><?= $carrera->has('mension_carrera') ? $this->Html->link($carrera->mension_carrera->id, ['controller' => 'MensionCarreras', 'action' => 'view', $carrera->mension_carrera->id]) : '' ?></dd>
            <dt scope="row"><?= __('Titulo Otorgado') ?></dt>
            <dd><?= h($carrera->titulo_otorgado) ?></dd>
            <dt scope="row"><?= __('Id') ?></dt>
            <dd><?= $this->Number->format($carrera->id) ?></dd>
            <dt scope="row"><?= __('Created') ?></dt>
            <dd><?= h($carrera->created) ?></dd>
            <dt scope="row"><?= __('Modified') ?></dt>
            <dd><?= h($carrera->modified) ?></dd>
            <dt scope="row"><?= __('Activa') ?></dt>
            <dd><?= $carrera->activa ? __('Yes') : __('No'); ?></dd>
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
          <h3 class="box-title"><?= __('Sedes') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($carrera->sedes)): ?>
          <table class="table table-hover">
              <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Codigo') ?></th>
                    <th scope="col"><?= __('Nombre') ?></th>
                    <th scope="col"><?= __('Direccion') ?></th>
                    <th scope="col"><?= __('Telefonos') ?></th>
                    <th scope="col"><?= __('Responsable') ?></th>
                    <th scope="col"><?= __('Activa') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                    <th scope="col"><?= __('Modified') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
              <?php foreach ($carrera->sedes as $sedes): ?>
              <tr>
                    <td><?= h($sedes->id) ?></td>
                    <td><?= h($sedes->codigo) ?></td>
                    <td><?= h($sedes->nombre) ?></td>
                    <td><?= h($sedes->direccion) ?></td>
                    <td><?= h($sedes->telefonos) ?></td>
                    <td><?= h($sedes->responsable) ?></td>
                    <td><?= h($sedes->activa) ?></td>
                    <td><?= h($sedes->created) ?></td>
                    <td><?= h($sedes->modified) ?></td>
                      <td class="actions text-right">
                      <?= $this->Html->link(__('View'), ['controller' => 'Sedes', 'action' => 'view', $sedes->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'Sedes', 'action' => 'edit', $sedes->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'Sedes', 'action' => 'delete', $sedes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sedes->id), 'class'=>'btn btn-danger btn-xs']) ?>
                  </td>
              </tr>
              <?php endforeach; ?>
          </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <i class="fa fa-share-alt"></i>
          <h3 class="box-title"><?= __('Cursos') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($carrera->cursos)): ?>
          <table class="table table-hover">
              <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Sede Id') ?></th>
                    <th scope="col"><?= __('Periodo Id') ?></th>
                    <th scope="col"><?= __('Carrera Id') ?></th>
                    <th scope="col"><?= __('Programa Id') ?></th>
                    <th scope="col"><?= __('Trayecto Id') ?></th>
                    <th scope="col"><?= __('Docente Id') ?></th>
                    <th scope="col"><?= __('Seccion') ?></th>
                    <th scope="col"><?= __('Cupos') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
              <?php foreach ($carrera->cursos as $cursos): ?>
              <tr>
                    <td><?= h($cursos->id) ?></td>
                    <td><?= h($cursos->sede_id) ?></td>
                    <td><?= h($cursos->periodo_id) ?></td>
                    <td><?= h($cursos->carrera_id) ?></td>
                    <td><?= h($cursos->programa_id) ?></td>
                    <td><?= h($cursos->trayecto_id) ?></td>
                    <td><?= h($cursos->docente_id) ?></td>
                    <td><?= h($cursos->seccion) ?></td>
                    <td><?= h($cursos->cupos) ?></td>
                      <td class="actions text-right">
                      <?= $this->Html->link(__('View'), ['controller' => 'Cursos', 'action' => 'view', $cursos->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'Cursos', 'action' => 'edit', $cursos->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'Cursos', 'action' => 'delete', $cursos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cursos->id), 'class'=>'btn btn-danger btn-xs']) ?>
                  </td>
              </tr>
              <?php endforeach; ?>
          </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <i class="fa fa-share-alt"></i>
          <h3 class="box-title"><?= __('Programas') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($carrera->programas)): ?>
          <table class="table table-hover">
              <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Codigo') ?></th>
                    <th scope="col"><?= __('Nombre') ?></th>
                    <th scope="col"><?= __('Carrera Id') ?></th>
                    <th scope="col"><?= __('Subsistema Id') ?></th>
                    <th scope="col"><?= __('Nota Minima') ?></th>
                    <th scope="col"><?= __('Creditos') ?></th>
                    <th scope="col"><?= __('Activo') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                    <th scope="col"><?= __('Modified') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
              <?php foreach ($carrera->programas as $programas): ?>
              <tr>
                    <td><?= h($programas->id) ?></td>
                    <td><?= h($programas->codigo) ?></td>
                    <td><?= h($programas->nombre) ?></td>
                    <td><?= h($programas->carrera_id) ?></td>
                    <td><?= h($programas->subsistema_id) ?></td>
                    <td><?= h($programas->nota_minima) ?></td>
                    <td><?= h($programas->creditos) ?></td>
                    <td><?= h($programas->activo) ?></td>
                    <td><?= h($programas->created) ?></td>
                    <td><?= h($programas->modified) ?></td>
                      <td class="actions text-right">
                      <?= $this->Html->link(__('View'), ['controller' => 'Programas', 'action' => 'view', $programas->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'Programas', 'action' => 'edit', $programas->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'Programas', 'action' => 'delete', $programas->id], ['confirm' => __('Are you sure you want to delete # {0}?', $programas->id), 'class'=>'btn btn-danger btn-xs']) ?>
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
