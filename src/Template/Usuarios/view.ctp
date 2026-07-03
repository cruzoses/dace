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
            <dt scope="row"><?= __('Id') ?></dt>
            <dd><?= $this->Number->format($usuario->id) ?></dd>
            <dt scope="row"><?= __('Nombres') ?></dt>
            <dd><?= h($usuario->nombres) ?></dd>
            <dt scope="row"><?= __('Apellidos') ?></dt>
            <dd><?= h($usuario->apellidos) ?></dd>
            <dt scope="row"><?= __('Sexo') ?></dt>
            <dd><?= h($usuario->sexo) ?></dd>
            <dt scope="row"><?= __('Email') ?></dt>
            <dd><?= h($usuario->email) ?></dd>
            <dt scope="row"><?= __('Telefonos') ?></dt>
            <dd><?= h($usuario->telefonos) ?></dd>
            <dt scope="row"><?= __('Username') ?></dt>
            <dd><?= h($usuario->username) ?></dd>
            <dt scope="row"><?= __('Password') ?></dt>
            <dd><?= h($usuario->password) ?></dd>
            <dt scope="row"><?= __('Cedula') ?></dt>
            <dd><?= $this->Number->format($usuario->cedula) ?></dd>
            <dt scope="row"><?= __('Fecha Nacimiento') ?></dt>
            <dd><?= h($usuario->fecha_nacimiento) ?></dd>
            <dt scope="row"><?= __('Created') ?></dt>
            <dd><?= h($usuario->created) ?></dd>
            <dt scope="row"><?= __('Modified') ?></dt>
            <dd><?= h($usuario->modified) ?></dd>
            <dt scope="row"><?= __('Activo') ?></dt>
            <dd><?= $usuario->activo ? __('Yes') : __('No'); ?></dd>
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
          <h3 class="box-title"><?= __('Rols') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($usuario->rols)): ?>
          <table class="table table-hover">
              <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Nombre') ?></th>
                    <th scope="col"><?= __('Activo') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                    <th scope="col"><?= __('Modified') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
              <?php foreach ($usuario->rols as $rols): ?>
              <tr>
                    <td><?= h($rols->id) ?></td>
                    <td><?= h($rols->nombre) ?></td>
                    <td><?= h($rols->activo) ?></td>
                    <td><?= h($rols->created) ?></td>
                    <td><?= h($rols->modified) ?></td>
                      <td class="actions text-right">
                      <?= $this->Html->link(__('View'), ['controller' => 'Rols', 'action' => 'view', $rols->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'Rols', 'action' => 'edit', $rols->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'Rols', 'action' => 'delete', $rols->id], ['confirm' => __('Are you sure you want to delete # {0}?', $rols->id), 'class'=>'btn btn-danger btn-xs']) ?>
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
          <h3 class="box-title"><?= __('Auditorias') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($auditorias)): ?>
          <table class="table table-hover">
				<thead>
              <tr>
                    <th scope="col"><?= $this->Paginator->sort('id', __('Id')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('usuario_id', __('Usuario Id')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('fecha', __('Fecha')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('evento', __('Evento')) ?></th>
                    <!--th scope="col"><?= $this->Paginator->sort('detalle', __('Detalle')) ?></th-->
                    <!--th scope="col"><?= $this->Paginator->sort('host', __('Host')) ?></th-->
                    <!--th scope="col"><?= $this->Paginator->sort('agente', __('Agente')) ?></th-->
                    <th scope="col"><?= $this->Paginator->sort('created', __('Created')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('modified', __('Modified')) ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
			  </thead>
			  <tbody>
              <?php foreach ($auditorias as $auditoria): ?>
              <tr>
                    <td><?= h($auditoria->id) ?></td>
                    <td><?= h($auditoria->usuario_id) ?></td>
                    <td><?= h($auditoria->fecha) ?></td>
                    <td><?= $this->Html->link(h($auditoria->evento), '#', ['data-toggle' => 'modal', 'data-target' => '#modalDetalle', 'data-detalle' => h($auditoria->detalle), 'data-evento' => h($auditoria->evento)]) ?></td>
                    <!--td><?= h($auditoria->detalle) ?></td-->
                    <!--td><?= h($auditoria->host) ?></td-->
                    <!--td><?= h($auditoria->agente) ?></td-->
                    <td><?= h($auditoria->created) ?></td>
                    <td><?= h($auditoria->modified) ?></td>
                      <td class="actions text-center">
                      <?= $this->Html->link(__('View'), ['controller' => 'Auditorias', 'action' => 'view', $auditoria->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'Auditorias', 'action' => 'edit', $auditoria->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'Auditorias', 'action' => 'delete', $auditoria->id], ['confirm' => __('Are you sure you want to delete # {0}?', $auditoria->id), 'class'=>'btn btn-danger btn-xs']) ?>
                  </td>
              </tr>
              <?php endforeach; ?>
			  </tbody>
                <tfoot class="no-padding">
                	<tr>
                        <td colspan="7" class="text-center">
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
          <h3 class="box-title"><?= __('Docentes') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($usuario->docentes)): ?>
          <table class="table table-hover">
              <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Cedula') ?></th>
                    <th scope="col"><?= __('Nombres') ?></th>
                    <th scope="col"><?= __('Apellidos') ?></th>
                    <th scope="col"><?= __('Fecha Nacimiento') ?></th>
                    <th scope="col"><?= __('Sexo') ?></th>
                    <th scope="col"><?= __('Email') ?></th>
                    <th scope="col"><?= __('Telefonos') ?></th>
                    <th scope="col"><?= __('Departamento Id') ?></th>
                    <th scope="col"><?= __('Token') ?></th>
                    <th scope="col"><?= __('Usuario Id') ?></th>
                    <th scope="col"><?= __('Activo') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                    <th scope="col"><?= __('Modified') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
              <?php foreach ($usuario->docentes as $docentes): ?>
              <tr>
                    <td><?= h($docentes->id) ?></td>
                    <td><?= h($docentes->cedula) ?></td>
                    <td><?= h($docentes->nombres) ?></td>
                    <td><?= h($docentes->apellidos) ?></td>
                    <td><?= h($docentes->fecha_nacimiento) ?></td>
                    <td><?= h($docentes->sexo) ?></td>
                    <td><?= h($docentes->email) ?></td>
                    <td><?= h($docentes->telefonos) ?></td>
                    <td><?= h($docentes->departamento_id) ?></td>
                    <td><?= h($docentes->token) ?></td>
                    <td><?= h($docentes->usuario_id) ?></td>
                    <td><?= h($docentes->activo) ?></td>
                    <td><?= h($docentes->created) ?></td>
                    <td><?= h($docentes->modified) ?></td>
                      <td class="actions text-right">
                      <?= $this->Html->link(__('View'), ['controller' => 'Docentes', 'action' => 'view', $docentes->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'Docentes', 'action' => 'edit', $docentes->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'Docentes', 'action' => 'delete', $docentes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $docentes->id), 'class'=>'btn btn-danger btn-xs']) ?>
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
          <h3 class="box-title"><?= __('Empleados') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($usuario->empleados)): ?>
          <table class="table table-hover">
              <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Cedula') ?></th>
                    <th scope="col"><?= __('Nombres') ?></th>
                    <th scope="col"><?= __('Apellidos') ?></th>
                    <th scope="col"><?= __('Fecha Nacimiento') ?></th>
                    <th scope="col"><?= __('Sexo') ?></th>
                    <th scope="col"><?= __('Email') ?></th>
                    <th scope="col"><?= __('Telefonos') ?></th>
                    <th scope="col"><?= __('Token') ?></th>
                    <th scope="col"><?= __('Usuario Id') ?></th>
                    <th scope="col"><?= __('Activo') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                    <th scope="col"><?= __('Modified') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
              <?php foreach ($usuario->empleados as $empleados): ?>
              <tr>
                    <td><?= h($empleados->id) ?></td>
                    <td><?= h($empleados->cedula) ?></td>
                    <td><?= h($empleados->nombres) ?></td>
                    <td><?= h($empleados->apellidos) ?></td>
                    <td><?= h($empleados->fecha_nacimiento) ?></td>
                    <td><?= h($empleados->sexo) ?></td>
                    <td><?= h($empleados->email) ?></td>
                    <td><?= h($empleados->telefonos) ?></td>
                    <td><?= h($empleados->token) ?></td>
                    <td><?= h($empleados->usuario_id) ?></td>
                    <td><?= h($empleados->activo) ?></td>
                    <td><?= h($empleados->created) ?></td>
                    <td><?= h($empleados->modified) ?></td>
                      <td class="actions text-right">
                      <?= $this->Html->link(__('View'), ['controller' => 'Empleados', 'action' => 'view', $empleados->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'Empleados', 'action' => 'edit', $empleados->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'Empleados', 'action' => 'delete', $empleados->id], ['confirm' => __('Are you sure you want to delete # {0}?', $empleados->id), 'class'=>'btn btn-danger btn-xs']) ?>
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
          <h3 class="box-title"><?= __('Estudiantes') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($usuario->estudiantes)): ?>
          <table class="table table-hover">
              <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Origen') ?></th>
                    <th scope="col"><?= __('Cedula') ?></th>
                    <th scope="col"><?= __('Nombres') ?></th>
                    <th scope="col"><?= __('Apellidos') ?></th>
                    <th scope="col"><?= __('Fecha Nacimiento') ?></th>
                    <th scope="col"><?= __('Sexo') ?></th>
                    <th scope="col"><?= __('Estado Civil') ?></th>
                    <th scope="col"><?= __('Discapacitado') ?></th>
                    <th scope="col"><?= __('Etnia') ?></th>
                    <th scope="col"><?= __('Direccion') ?></th>
                    <th scope="col"><?= __('Telefonos') ?></th>
                    <th scope="col"><?= __('Email') ?></th>
                    <th scope="col"><?= __('Lugar Nacimiento') ?></th>
                    <th scope="col"><?= __('Pais Id') ?></th>
                    <th scope="col"><?= __('Estado Id') ?></th>
                    <th scope="col"><?= __('Municipio Id') ?></th>
                    <th scope="col"><?= __('Parroquia Id') ?></th>
                    <th scope="col"><?= __('Asignado') ?></th>
                    <th scope="col"><?= __('Codigo Opsu') ?></th>
                    <th scope="col"><?= __('Fecha Notas') ?></th>
                    <th scope="col"><?= __('Codigo Notas') ?></th>
                    <th scope="col"><?= __('Fecha Titulo') ?></th>
                    <th scope="col"><?= __('Codigo Titulo') ?></th>
                    <th scope="col"><?= __('Expediente') ?></th>
                    <th scope="col"><?= __('Token') ?></th>
                    <th scope="col"><?= __('Usuario Id') ?></th>
                    <th scope="col"><?= __('Activo') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                    <th scope="col"><?= __('Modified') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
              <?php foreach ($usuario->estudiantes as $estudiantes): ?>
              <tr>
                    <td><?= h($estudiantes->id) ?></td>
                    <td><?= h($estudiantes->origen) ?></td>
                    <td><?= h($estudiantes->cedula) ?></td>
                    <td><?= h($estudiantes->nombres) ?></td>
                    <td><?= h($estudiantes->apellidos) ?></td>
                    <td><?= h($estudiantes->fecha_nacimiento) ?></td>
                    <td><?= h($estudiantes->sexo) ?></td>
                    <td><?= h($estudiantes->estado_civil) ?></td>
                    <td><?= h($estudiantes->discapacitado) ?></td>
                    <td><?= h($estudiantes->etnia) ?></td>
                    <td><?= h($estudiantes->direccion) ?></td>
                    <td><?= h($estudiantes->telefonos) ?></td>
                    <td><?= h($estudiantes->email) ?></td>
                    <td><?= h($estudiantes->lugar_nacimiento) ?></td>
                    <td><?= h($estudiantes->pais_id) ?></td>
                    <td><?= h($estudiantes->estado_id) ?></td>
                    <td><?= h($estudiantes->municipio_id) ?></td>
                    <td><?= h($estudiantes->parroquia_id) ?></td>
                    <td><?= h($estudiantes->asignado) ?></td>
                    <td><?= h($estudiantes->codigo_opsu) ?></td>
                    <td><?= h($estudiantes->fecha_notas) ?></td>
                    <td><?= h($estudiantes->codigo_notas) ?></td>
                    <td><?= h($estudiantes->fecha_titulo) ?></td>
                    <td><?= h($estudiantes->codigo_titulo) ?></td>
                    <td><?= h($estudiantes->expediente) ?></td>
                    <td><?= h($estudiantes->token) ?></td>
                    <td><?= h($estudiantes->usuario_id) ?></td>
                    <td><?= h($estudiantes->activo) ?></td>
                    <td><?= h($estudiantes->created) ?></td>
                    <td><?= h($estudiantes->modified) ?></td>
                      <td class="actions text-right">
                      <?= $this->Html->link(__('View'), ['controller' => 'Estudiantes', 'action' => 'view', $estudiantes->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'Estudiantes', 'action' => 'edit', $estudiantes->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'Estudiantes', 'action' => 'delete', $estudiantes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $estudiantes->id), 'class'=>'btn btn-danger btn-xs']) ?>
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
          <h3 class="box-title"><?= __('Noticias') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($usuario->noticias)): ?>
          <table class="table table-hover">
              <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Fecha') ?></th>
                    <th scope="col"><?= __('Titulo') ?></th>
                    <th scope="col"><?= __('Contenido') ?></th>
                    <th scope="col"><?= __('Usuario Id') ?></th>
                    <th scope="col"><?= __('Activa') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                    <th scope="col"><?= __('Modified') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
              <?php foreach ($usuario->noticias as $noticias): ?>
              <tr>
                    <td><?= h($noticias->id) ?></td>
                    <td><?= h($noticias->fecha) ?></td>
                    <td><?= h($noticias->titulo) ?></td>
                    <td><?= h($noticias->contenido) ?></td>
                    <td><?= h($noticias->usuario_id) ?></td>
                    <td><?= h($noticias->activa) ?></td>
                    <td><?= h($noticias->created) ?></td>
                    <td><?= h($noticias->modified) ?></td>
                      <td class="actions text-right">
                      <?= $this->Html->link(__('View'), ['controller' => 'Noticias', 'action' => 'view', $noticias->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'Noticias', 'action' => 'edit', $noticias->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'Noticias', 'action' => 'delete', $noticias->id], ['confirm' => __('Are you sure you want to delete # {0}?', $noticias->id), 'class'=>'btn btn-danger btn-xs']) ?>
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

<div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalDetalleTitle">Detalle</h4>
            </div>
            <div class="modal-body" id="modalDetalleBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '[data-target="#modalDetalle"]', function() {
    var detalle = $(this).data('detalle');
    $('#modalDetalleTitle').text('Detalle - ' + $(this).data('evento'));

    var body = $('#modalDetalleBody');
    body.empty();

    var jsonStart = detalle.indexOf('{');
    if (jsonStart !== -1) {
        var prefix = detalle.substring(0, jsonStart);
        var jsonStr = detalle.substring(jsonStart);
        try {
            var jsonData = JSON.parse(jsonStr);
            var html = '';
            if (prefix) {
                html += '<p><strong>' + $('<span>').text(prefix).html() + '</strong></p>';
            }
            html += '<table class="table table-bordered table-condensed table-striped">';
            html += '<tbody>';
            $.each(jsonData, function(key, value) {
                if (value !== null && typeof value === 'object') {
                    html += '<tr><th>' + key + '</th><td><pre style="margin:0">' + JSON.stringify(value, null, 2) + '</pre></td></tr>';
                } else {
                    html += '<tr><th>' + key + '</th><td>' + $('<span>').text(String(value)).html() + '</td></tr>';
                }
            });
            html += '</tbody></table>';
            body.html(html);
            return;
        } catch(e) {}
    }

    body.text(detalle);
});
</script>
