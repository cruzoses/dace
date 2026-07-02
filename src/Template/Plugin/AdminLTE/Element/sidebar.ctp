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
			<?php if( $this->Permiso->tiene([1,2,3,4]) ) : ?>
				<!-- Arcivos -->
				<li class="treeview">
					<a href="#"><i class="fa fa-archive"></i><span>Archivos</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="/docentes"><i class="fa fa-circle-o"></i>&nbsp;Docentes</a>
						</li>
						<li>
							<a href="/estudiantes"><i class="fa fa-circle-o"></i>&nbsp;Estudiantes</a>
						</li>
					</ul>
				</li>
				<!-- Datos -->
				<li class="active treeview">
					<a href="#"><i class="fa fa-database"></i><span>Datos</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="<?= $this->Url->build(['controller' => 'docentes','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Docentes
							</a>
						</li>
						<li>
							<a href="<?php echo $this->Url->build('/buscarestudiante');?>">
								<i class="fa fa-circle-o"></i>&nbsp;Estudiantes
							</a>
						</li>
					</ul>
				</li>
				<!-- Definiciones -->
				<li class="treeview">
					<a href="#"><i class="fa  fa-wrench"></i><span>Definiciones</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="<?= $this->Url->build(['controller' => 'aulas','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Aulas
							</a>
						</li>
						<li>
							<a href="<?= $this->Url->build(['controller' => 'asignaturas','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Asignaturas
							</a>
						</li>
						<li>
							<a href="<?= $this->Url->build(['controller' => 'carreras','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Carreras
							</a>
						</li>
						<li>
							<a href="<?= $this->Url->build(['controller' => 'cursos','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Cursos
							</a>
						</li>
						<li>
							<a href="<?= $this->Url->build(['controller' => 'horarios','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Horarios
							</a>
						</li>
						<li>
							<a href="<?= $this->Url->build(['controller' => 'grupo_asignaturas','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Grupo de Asignaturas
							</a>
						</li>
						<li>
							<a href="<?= $this->Url->build(['controller' => 'asignatura_programas','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Malla Curricular
							</a>
						</li>
						<li>
							<a href="<?= $this->Url->build(['controller' => 'ofertas','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Oferta Acad&eacute;mica
							</a>
						</li>
						<li>
							<a href="<?= $this->Url->build(['controller' => 'periodos','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Periodos
							</a>
						</li>
						<li>
							<a href="<?= $this->Url->build(['controller' => 'programas','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Programas (P.N.F)
							</a>
						</li>
						<li>
							<a href="<?= $this->Url->build(['controller' => 'subsistemas','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Sistemas de Estudio
							</a>
						</li>
						<li>
							<a href="<?= $this->Url->build(['controller' => 'trayectos','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Trayectos
							</a>
						</li>
						<li class="treeview">
        					<a href="#"><i class="fa fa-circle-o"></i> <span>Zonas Geogr&aacute;ficas</span>
            					<span class="pull-right-container">
                					<i class="fa fa-angle-left pull-right"></i>
              					</span>
          					</a>
          					<ul class="treeview-menu">
            					<li>
									<a href="<?= $this->Url->build(['controller' => 'estados','action' => 'index'])?>">
										<i class="fa fa-circle-o"></i>&nbsp;Estados
									</a>
								</li>
            					<li>
									<a href="<?= $this->Url->build(['controller' => 'municipios','action' => 'index'])?>">
										<i class="fa fa-circle-o"></i>&nbsp;Municipios
									</a>
								</li>
								<li>
									<a href="<?= $this->Url->build(['controller' => 'parroquias','action' => 'index'])?>">
										<i class="fa fa-circle-o"></i>&nbsp;Parroquias
									</a>
								</li>
          					</ul>
        				</li>
					</ul>
				</li>
				<!-- Gestión Docente -->
				<li class="treeview">
					<a href="#"><i class="fa fa-sitemap"></i><span>Gestión Docente</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="/gestionacademica"><i class="fa fa-circle-o"></i>&nbsp;Avance de Gestión</a>
						</li>
					</ul>
				</li>
			<?php endif; ?>
			<?php if( $this->Permiso->tiene([1,2]) ) : ?>
				<!-- Institucion -->
				<li class="treeview">
					<a href="#"><i class="fa fa-home"></i><span>Instituci&oacute;n</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="<?= $this->Url->build(['controller' => 'firmas','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Firmas
							</a>
						</li>
						<li>
							<a href="<?= $this->Url->build(['controller' => 'sedes','action' => 'index'])?>">
								<i class="fa fa-circle-o"></i>&nbsp;Sedes
							</a>
						</li>
					</ul>
				</li>
				<!-- Procesos -->
				<?php if( $this->Permiso->tiene([1,2]) ) : ?>
					<li class="treeview">
						<a href="#"><i class="fa  fa-cogs"></i><span>Procesos</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li class="treeview">
								<a href="#"><i class="fa fa-circle-o"></i><span>Activar Carga de Notas</span>
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>
								</a>
								<ul class="treeview-menu">
									<li>
										<a href="<?php echo $this->Url->build('/carga_notas')?>">
											<i class="fa fa-circle-o"></i>&nbsp;Per&iacute;odo
										</a>
									</li>
									<li>
										<a href="<?php echo $this->Url->build('/programas/calificar')?>">
											<i class="fa fa-circle-o"></i>&nbsp;Programa
										</a>
									</li>
								</ul>
							</li>
							<li>
								<?php echo $this->Html->link('<i class="fa fa-circle-o"></i>&nbsp;Crear Histórico',
									array('controller' => 'procesos','action' => 'index'),
									array('escape' => false));
								?>
							</li>
							<li>
								<?php echo $this->Html->link('<i class="fa fa-circle-o"></i>&nbsp;Calcular Indices',
									array('controller' => 'procesos','action' => 'calcularindice'),
									array('escape' => false));
								?>
							</li>
						</ul>
					</li>
				<?php endif; ?>
				<!-- Promociones -->
				<li class="treeview">
					<a href="#"><i class="fa fa-graduation-cap"></i><span>Promociones</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="/actos/index"><i class="fa fa-circle-o"></i>&nbsp;Acto de Grado</a>
						</li>
						<li>
							<a href="/actos/actas"><i class="fa fa-circle-o"></i>&nbsp;Libro de Actas</a>
						</li>
					</ul>
				</li>
			<?php endif; ?>
			<?php if( $this->Permiso->tiene([1,2,3]) ) : ?>
				<!-- P.N.F. Avanzado -->				
				<?php echo $this->Element('menupnfa');?>
			<?php endif; ?>
			<?php if( $this->Permiso->tiene([1,2,3]) ) : ?>
				<!-- Reportes -->
				<li class="treeview">
					<a href="#"><i class="fa fa-print"></i><span>Reportes</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="/reportes/actadenotas"><i class="fa fa-circle-o"></i>&nbsp;Acta de Notas</a>
						</li>
						<li>
							<a href="/reportes/listamaterias"><i class="fa fa-circle-o"></i>&nbsp;Asignaturas</a>
						</li>
						<li>
							<a href="/reportes/listarmallas"><i class="fa fa-circle-o"></i>&nbsp;Asignaturas X Programa</a>
						</li>
						<li>
							<a href="/reportes/listacarreras"><i class="fa fa-circle-o"></i>&nbsp;Carreras</a>
						</li>
						<li>
							<a href="/reportes/cursos"><i class="fa fa-circle-o"></i>&nbsp;Cursos</a>
						</li>
						<li class="treeview">							
        					<a href="#"><i class="fa fa-circle-o"></i><span>Estad&iacute;sticas</span>
            					<span class="pull-right-container">
                					<i class="fa fa-angle-left pull-right"></i>
              					</span>
          					</a>
          					<ul class="treeview-menu">
							  	<li>
									<a href="<?php echo $this->Url->build('/reportes/nuevoingreso')?>">
										<i class="fa fa-circle-o"></i>&nbsp;Nuevo Ingreso
									</a>
								</li>
            					<li>
									<a href="<?php echo $this->Url->build('/reportes/inscripcion')?>">
										<i class="fa fa-circle-o"></i>&nbsp;Inscripci&oacute;n
									</a>
								</li>
            					<li>
									<a href="<?php echo $this->Url->build('/reportes/estudiantes')?>">
										<i class="fa fa-circle-o"></i>&nbsp;Estudiantes x Programa
									</a>
								</li>
          					</ul>
        				</li>
						<li>
							<a href="/reportes/actodegrado"><i class="fa fa-circle-o"></i>&nbsp;Graduandos</a>
						</li>
						<li>
							<a href="/reportes/actasdegrado"><i class="fa fa-circle-o"></i>&nbsp;Libro de Actas</a>
						</li>
						<li>
							<a href="/reportes/ofertas">
								<i class="fa fa-circle-o"></i>&nbsp;Ofertas
							</a>
						</li>
						<li>
							<a href="/reportes/listaperiodos">
								<i class="fa fa-circle-o"></i>&nbsp;Per&iacute;odos
							</a>
						</li>
						<li>
							<a href="/reportes/listaprogramas">
								<i class="fa fa-circle-o"></i>&nbsp;Programas
							</a>
						</li>
					</ul>
				</li>
				<!-- Seguridad -->
				<?php if( $this->Permiso->tiene([1,2]) ) : ?>
					<li class="treeview">
						<a href="#"><i class="fa fa-lock"></i> <span>Seguridad</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<?php if( $this->Permiso->tiene(1) ) : ?>								
								<li>
									<a href="/rols"><i class="fa fa-circle-o"></i>&nbsp;Tipo de Usuarios</a>
								</li>
							<?php endif; ?>
							<li><a href="/usuarios"><i class="fa fa-circle-o"></i>&nbsp;Usuarios del Sistema</a></li>
							<?php if( $this->Permiso->tiene([1,2]) ) : ?>
								<li>
									<a href="/reportes/tipousuarios"><i class="fa fa-circle-o"></i>&nbsp;Reporte de Roles</a>
								</li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>
			<?php endif; ?>
			<?php if( $this->Permiso->tiene(5) ) : ?>
				<?php $nId = $this->request->getSession()->read('UsuarioActivo.Docente.0.id') ?>
				<!-- Lista -->
				<li class="treeview">
					<a href="#"><i class="fa fa-archive"></i><span>Cursos</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<?php echo $this->Html->link('<i class="fa fa-circle-o"></i>&nbsp;Cursos Asignados',
								array('controller' => 'Docentes', 'action' => 'cursosasignados','?' => array(
									'DocenteId' => $this->request->getSession()->read('UsuarioActivo.Docente.0.id')
								)),
								array('escape' => false)
							)?>
						</li>
					</ul>
				</li>
			<?php endif; ?>
			<?php if( $this->Permiso->tiene([6,7]) ) : ?>
				<?php echo $this->Element('Sace/menupnfa');?>
			<?php endif; ?>
			<?php if( $this->Permiso->tiene(8) ) : ?>
				<li class="treeview">
					<a href="#"><i class="fa fa-archive"></i><span>Archivos</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="/docentes"><i class="fa fa-circle-o"></i>&nbsp;Docentes</a>
						</li>
					</ul>
				</li>
				<li class="treeview">
					<a href="#"><i class="fa fa-archive"></i><span>Reportes</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="/reportes/inscripcion"><i class="fa fa-circle-o"></i>&nbsp;Inscripción</a>
						</li>
					</ul>
				</li>											
			<?php endif; ?>
			<?php if( $this->Permiso->tiene(10) ) : ?>
				<li class="treeview">
					<a href="#"><i class="fa fa-archive"></i><span>Datos</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="/consultaestudiante"><i class="fa fa-circle-o"></i>&nbsp;Estudiantes</a>
						</li>
					</ul>
				</li>
			<?php endif; ?>
            <li class="treeview">
                <a href="#"><i class="fa fa-user"></i>&nbsp;<span>Mis Datos</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
					<li>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i>&nbsp;Cambiar Contraseña',
							array('controller'=>'Usuarios','action'=>'cambiaclave'),array('escape'=>false));
						?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i>&nbsp;Perfil de Usuario',
							array('controller'=>'usuarios','action'=>'perfil'),array('escape'=>false));
						?>
                    </li>
                </ul>
            </li>
		<?php endif; ?>
		<?php if( !isset($userActivo) ) : ?>
			<li class="treeview">
				<a href="#"><i class="fa fa-lock"></i> <span>Seguridad</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">							
					<li>
						<?= $this->Html->link("<i class=\"fa fa-circle-o\"></i>&nbsp;Tipo de Usuarios",['controller' => 'Rols', 'action' => 'index'],['escape' => false]) ?>
					</li>
					<li><a href="/usuarios"><i class="fa fa-circle-o"></i>&nbsp;Usuarios del Sistema</a></li>
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
