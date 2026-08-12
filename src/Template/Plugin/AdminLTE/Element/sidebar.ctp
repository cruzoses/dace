<section class="sidebar">
    <div class="user-panel">
    	<div class="pull-left image">
			<?= $this->Html->image('logos/logouptbal.png',
				['class' => 'img-circle', 'width' => '48', 'height' => '48', 'alt' => 'User Image']);
			?>
        </div>
        <div class="pull-left info">
        	<p> <?= isset($userActivo) ? $userActivo['alias'] : "" ?></p>
			<?php if( isset($userActivo) && $userActivo['activo'] == 1 ) :?>
          		<a href="#"><i class="fa fa-circle text-success"></i>&nbsp;Online</a>
			<?php elseif( isset($userActivo) && $userActivo['activo'] != 1) : ?>
				<a href="#"><i class="fa fa-circle text-danger"></i>&nbsp;Suspendido</a>
			<?php else : ?>
				<a href="#"><i class="fa fa-circle text-danger"></i>&nbsp;Programador</a>
			<?php endif; ?>
        </div>
    </div>
    <form action="#" method="get" class="sidebar-form">
    	<div class="input-group">
        	<input type="text" name="q" class="form-control" placeholder="Buscar...">
          	<span class="input-group-btn">
            	<button type="submit" name="search" id="search-btn" class="btn btn-flat">
					<i class="fa fa-search"></i>
              	</button>
            </span>
        </div>
    </form>
    <ul class="sidebar-menu" data-widget="tree">
    	<li class="header text-center">OPCIONES</li>
		<?php if( isset($userActivo) && $userActivo['activo'] && isset($userActivo['rols']) && !empty($userActivo['rols']) ) : ?>
			<?php if( $this->Permiso->tiene([1,2,3]) ) : ?>
				<?= $this->Element('Menus/level123');?>
			<?php endif; ?>
			<?php if( $this->Permiso->tiene([1,2]) ) : ?>
				<?= $this->Element('Menus/level12');?>
			<?php endif; ?>
			<?php if( $this->Permiso->tiene([1,2,3]) ) : ?>
				<!-- P.N.F. Avanzado -->				
				<?= $this->Element('menupnfa');?>
			<?php endif; ?>
			<?php if( $this->Permiso->tiene([1,2,3]) ) : ?>
				<?= $this->Element('Menus/reportes');?>
				<!-- Seguridad -->
				<?php if( $this->Permiso->tiene([1,2]) ) : ?>
					<li class="treeview">
						<a href="#"><i class="fa fa-lock"></i> <span>Seguridad</span>
							<span class="pull-right-container">
								<i class="fas fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<?php if( $this->Permiso->tiene(1) ) : ?>								
								<li>
									<a href="<?= $this->Url->build(['controller' => 'rols','action' => 'index'])?>">
										<i class="far fa-dot-circle"></i>&nbsp;Tipos de Usuario
									</a>
								</li>
							<?php endif; ?>
							<li>
								<a href="<?= $this->Url->build(['controller' => 'usuarios','action' => 'index'])?>">
									<i class="far fa-dot-circle"></i>&nbsp;Usuarios del Sistema
								</a>
							</li>
							<?php if( $this->Permiso->tiene([1,2]) ) : ?>
								<li>
									<a href="/reportes/tipousuarios"><i class="far fa-dot-circle"></i>&nbsp;Reporte de Roles</a>
								</li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>
			<?php endif; ?>
			<?php if( $this->Permiso->tiene(5) ) : ?>
				<?= $this->Element('Menus/level5');?>
			<?php endif; ?>
			<?php if( $this->Permiso->tiene([6,7]) ) : ?>
				<?= $this->Element('Sace/menupnfa');?>
			<?php endif; ?>
			<?php if( $this->Permiso->tiene(8) ) : ?>
				<li class="treeview">
					<a href="#"><i class="fa fa-archive"></i><span>&nbsp;Archivos</span>
						<span class="pull-right-container">
							<i class="fas fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="/docentes"><i class="far fa-dot-circle"></i>&nbsp;Docentes</a>
						</li>
					</ul>
				</li>
				<li class="treeview">
					<a href="#"><i class="fa fa-archive"></i><span>Reportes</span>
						<span class="pull-right-container">
							<i class="fas fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="/reportes/inscripcion"><i class="far fa-dot-circle"></i>&nbsp;Inscripción</a>
						</li>
					</ul>
				</li>											
			<?php endif; ?>
			<?php if( $this->Permiso->tiene(9) ) : ?>
				<?= $this->Element('Estudiantes/opciones') ?>
			<?php endif; ?>			
			<?php if( $this->Permiso->tiene(10) ) : ?>
				<li class="treeview">
					<a href="#"><i class="fa fa-archive"></i><span>&nbsp;Datos</span>
						<span class="pull-right-container">
							<i class="fas fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="/consultaestudiante"><i class="far fa-dot-circle"></i>&nbsp;Estudiantes</a>
						</li>
					</ul>
				</li>
			<?php endif; ?>
            <li class="treeview">
                <a href="#"><i class="fa fa-user"></i>&nbsp;<span>Mis Datos</span>
                    <span class="pull-right-container">
                        <i class="fas fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
					<li>
						<a href="<?= $this->Url->build(['controller' => 'usuarios','action' => 'cambiaclave'])?>">
							<i class="far fa-dot-circle"></i>&nbsp;Cambiar Contraseña
						</a>
					</li>
                    <li>
						<a href="<?= $this->Url->build(['controller' => 'usuarios','action' => 'perfil'])?>">
							<i class="far fa-dot-circle"></i>&nbsp;Perfil de Usuario
						</a>
                    </li>
                </ul>
            </li>
		<?php endif; ?>
		<?php if( !isset($userActivo) ) : ?>
			<li class="treeview">
				<a href="#"><i class="fa fa-lock"></i> <span>&nbsp;Seguridad</span>
					<span class="pull-right-container">
						<i class="fas fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">							
					<li>
						<?= $this->Html->link("<i class=\"fa fa-circle-o\"></i>&nbsp;Tipo de Usuarios",['controller' => 'rols', 'action' => 'index'],['escape' => false]) ?>
					</li>
					<?= $this->Html->link("<i class=\"fa fa-circle-o\"></i>&nbsp;Tipo de Usuarios",['controller' => 'usuarios', 'action' => 'index'],['escape' => false]) ?>
				</ul>
			</li>
		<?php endif; ?>
		<li class="active">
			<?= $this->Html->link('<i class="fa fa-power-off text-danger"></i>&nbsp;Cerrar Sesión',
				['controller' => 'usuarios','action' => 'logout'],['escape'=>false]);
			?>
		</li>
    </ul>
</section>
