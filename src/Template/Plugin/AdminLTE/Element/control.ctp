    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
    <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-cog"></i></a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane" id="control-sidebar-home-tab">
    	<h3 class="control-sidebar-heading text-center">Datos Básicos</h3>
        <ul class="control-sidebar-menu">
            <li>
                <a href="<?= $this->Url->build(['controller' => 'sedes', 'action' => 'index']) ?>">
                    <i class="menu-icon fas fa-school bg-green"></i>
                    <div class="menu-info">
                        <h4 class="control-sidebar-subheading">Datos de Sedes</h4>
                        <p>Sedes Académicas</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'empleados', 'action' => 'index']) ?>">
                    <i class="menu-icon fa fa-user bg-fuchsia"></i>
                    <div class="menu-info">
                        <h4 class="control-sidebar-subheading">Control de Estudio</h4>
                        <p>Analista de P.N.F</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'usuarios', 'action' => 'index']) ?>">
                    <i class="menu-icon fa fa-user bg-purple"></i>
                    <div class="menu-info">
                        <h4 class="control-sidebar-subheading">Usuarios Registrados</h4>
                        <p>Datos de Usuarios</p>
                    </div>
                </a>
            </li>
		</ul>
		<h3 class="control-sidebar-heading text-center">Ubicaci&oacute;n Geogr&aacute;fica</h3>
		<ul class="control-sidebar-menu">
          	<li>
            	<a href="<?= $this->Url->build(['controller' => 'paises', 'action' => 'index']) ?>">
              		<i class="menu-icon fa fa-globe bg-orange"></i>
              		<div class="menu-info">
                		<h4 class="control-sidebar-subheading">Paises</h4>
                		<p>Datos de Paises</p>
              		</div>
            	</a>
          	</li>
          	<li>
            	<a href="<?= $this->Url->build(['controller' => 'estados', 'action' => 'index']) ?>">
              		<i class="menu-icon fa fa-globe bg-light-blue"></i>
              		<div class="menu-info">
                		<h4 class="control-sidebar-subheading">Estados</h4>
                		<p>Datos de Estados</p>
              		</div>
            	</a>
          	</li>
          	<li>
            	<a href="<?= $this->Url->build(['controller' => 'municipios', 'action' => 'index']) ?>">
              		<i class="menu-icon fa fa-globe bg-red"></i>
              		<div class="menu-info">
                		<h4 class="control-sidebar-subheading">Municipios</h4>
                		<p>Datos de Municipios</p>
              		</div>
            	</a>
          	</li>
          	<li>
            	<a href="<?= $this->Url->build(['controller' => 'parroquias', 'action' => 'index']) ?>">
              		<i class="menu-icon fa fa-globe bg-teal"></i>
              		<div class="menu-info">
                		<h4 class="control-sidebar-subheading">Parroquias</h4>
                		<p>Datos de Parroquias</p>
              		</div>
            	</a>
          	</li>
        </ul>
    </div>
    <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
    <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
            <h3 class="control-sidebar-heading">General Settings</h3>

            <div class="form-group">
                <label class="control-sidebar-subheading">
                    Report panel usage
                    <input type="checkbox" class="pull-right" checked>
                </label>
                <p>Some information about this general settings option</p>
            </div>
            <div class="form-group">
                <label class="control-sidebar-subheading">
                    Allow mail redirect
                    <input type="checkbox" class="pull-right" checked>
                </label>
                <p>Other sets of options are available</p>
            </div>
            <div class="form-group">
                <label class="control-sidebar-subheading">
                    Expose author name in posts
                    <input type="checkbox" class="pull-right" checked>
                </label>
                <p>Allow the user to show his name in blog posts</p>
            </div>
            <h3 class="control-sidebar-heading">Chat Settings</h3>
            <div class="form-group">
                <label class="control-sidebar-subheading">
                    Show me as online
                    <input type="checkbox" class="pull-right" checked>
                </label>
            </div>
            <div class="form-group">
                <label class="control-sidebar-subheading">
                    Turn off notifications
                    <input type="checkbox" class="pull-right">
                </label>
            </div>
            <div class="form-group">
                <label class="control-sidebar-subheading">
                    Delete chat history
                    <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                </label>
            </div>
        </form>
    </div>
</div>
  