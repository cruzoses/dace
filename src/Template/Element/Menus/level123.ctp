<!-- Arcivos -->
<li class="treeview">
	<a href="#"><i class="fa fa-archive"></i>&nbsp;<span>Archivos</span>
		<span class="pull-right-container">
		<i class="fas fa-angle-left pull-right"></i>
	    </span>
	</a>
	<ul class="treeview-menu">
		<li>
			<a href="<?= $this->Url->build(['controller' => 'docentes','action' => 'index'])?>">
				<i class="far fa-dot-circle"></i>&nbsp;Docentes
			</a>
		</li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'estudiantes','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Estudiantes
            </a>
        </li>
    </ul>
</li>
<!-- Datos -->
<li class="active treeview">
    <a href="#"><i class="fa fa-database"></i>&nbsp;<span>Datos</span>
        <span class="pull-right-container">
            <i class="fas fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="<?= $this->Url->build(['controller' => 'datos','action' => 'facilitadores'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Docentes
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'datos','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Estudiantes
            </a>
        </li>
    </ul>
</li>
<!-- Definiciones -->
<li class="treeview">
    <a href="#"><i class="fa  fa-wrench"></i><span>&nbsp;Definiciones</span>
        <span class="pull-right-container">
            <i class="fas fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="<?= $this->Url->build(['controller' => 'aulas','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Aulas
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'asignaturas','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Asignaturas
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'carreras','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Carreras
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'cursos','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Cursos
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'horarios','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Horarios
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'grupo_asignaturas','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Grupo de Asignaturas
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'mallas','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Malla Curricular
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'periodos','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Periodos
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'programas','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Programas (P.N.F)
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'indicadores','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Indicadores de Proceso
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'trayectos','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Trayectos
            </a>
        </li>
        <li class="treeview">
            <a href="#"><i class="far fa-dot-circle"></i> <span>Zonas Geogr&aacute;ficas</span>
                <span class="pull-right-container">
                    <i class="fas fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="<?= $this->Url->build(['controller' => 'estados','action' => 'index'])?>">
                        <i class="far fa-dot-circle"></i>&nbsp;Estados
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['controller' => 'municipios','action' => 'index'])?>">
                        <i class="far fa-dot-circle"></i>&nbsp;Municipios
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['controller' => 'parroquias','action' => 'index'])?>">
                        <i class="far fa-dot-circle"></i>&nbsp;Parroquias
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</li>
<!-- Gestión Docente -->
<li class="treeview">
    <a href="#"><i class="fa fa-sitemap"></i><span>&nbsp;Gestión Docente</span>
        <span class="pull-right-container">
            <i class="fas fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="/gestionacademica"><i class="far fa-dot-circle"></i>&nbsp;Avance de Gestión</a>
        </li>
    </ul>
</li>
