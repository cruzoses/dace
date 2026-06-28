<section class="content-header">
  <h1>
    Sede
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
            <dd><?= h($sede->codigo) ?></dd>
            <dt scope="row"><?= __('Nombre') ?></dt>
            <dd><?= h($sede->nombre) ?></dd>
            <dt scope="row"><?= __('Telefonos') ?></dt>
            <dd><?= h($sede->telefonos) ?></dd>
            <dt scope="row"><?= __('Responsable') ?></dt>
            <dd><?= h($sede->responsable) ?></dd>
            <dt scope="row"><?= __('Id') ?></dt>
            <dd><?= $this->Number->format($sede->id) ?></dd>
            <dt scope="row"><?= __('Created') ?></dt>
            <dd><?= h($sede->created) ?></dd>
            <dt scope="row"><?= __('Modified') ?></dt>
            <dd><?= h($sede->modified) ?></dd>
            <dt scope="row"><?= __('Activa') ?></dt>
            <dd><?= $sede->activa ? __('Yes') : __('No'); ?></dd>
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
          <h3 class="box-title"><?= __('Direccion') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <?= $this->Text->autoParagraph($sede->direccion); ?>
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
          <?php if (!empty($sede->carreras)): ?>
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
              <?php foreach ($sede->carreras as $carreras): ?>
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
  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <i class="fa fa-share-alt"></i>
          <h3 class="box-title"><?= __('Cursos') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($sede->cursos)): ?>
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
              <?php foreach ($sede->cursos as $cursos): ?>
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
          <h3 class="box-title"><?= __('Estudiante Programas') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($sede->estudiante_programas)): ?>
          <table class="table table-hover">
              <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Estudiante Id') ?></th>
                    <th scope="col"><?= __('Sede Id') ?></th>
                    <th scope="col"><?= __('Programa Id') ?></th>
                    <th scope="col"><?= __('Fecha Egreso') ?></th>
                    <th scope="col"><?= __('Cohorte') ?></th>
                    <th scope="col"><?= __('Indice') ?></th>
                    <th scope="col"><?= __('Culminado') ?></th>
                    <th scope="col"><?= __('Activo') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                    <th scope="col"><?= __('Modified') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
              <?php foreach ($sede->estudiante_programas as $estudianteProgramas): ?>
              <tr>
                    <td><?= h($estudianteProgramas->id) ?></td>
                    <td><?= h($estudianteProgramas->estudiante_id) ?></td>
                    <td><?= h($estudianteProgramas->sede_id) ?></td>
                    <td><?= h($estudianteProgramas->programa_id) ?></td>
                    <td><?= h($estudianteProgramas->fecha_egreso) ?></td>
                    <td><?= h($estudianteProgramas->cohorte) ?></td>
                    <td><?= h($estudianteProgramas->indice) ?></td>
                    <td><?= h($estudianteProgramas->culminado) ?></td>
                    <td><?= h($estudianteProgramas->activo) ?></td>
                    <td><?= h($estudianteProgramas->created) ?></td>
                    <td><?= h($estudianteProgramas->modified) ?></td>
                      <td class="actions text-right">
                      <?= $this->Html->link(__('View'), ['controller' => 'EstudianteProgramas', 'action' => 'view', $estudianteProgramas->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'EstudianteProgramas', 'action' => 'edit', $estudianteProgramas->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'EstudianteProgramas', 'action' => 'delete', $estudianteProgramas->id], ['confirm' => __('Are you sure you want to delete # {0}?', $estudianteProgramas->id), 'class'=>'btn btn-danger btn-xs']) ?>
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
