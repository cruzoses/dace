<li class="treeview active">
	<a href="#"><i class="fa fa-archive"></i><span>&nbsp;Opciones</span>
	    <span class="pull-right-container">
		    <i class="fas fa-angle-left pull-right"></i>
	    </span>
	</a>
    <ul class="treeview-menu">
        <li>
            <a href="<?= $this->Url->build(['controller' => 'Estudiantes','action' => 'situacion',
                $userActivo['estudiantes'][0]['id']])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Situación Académica
            </a>
		</li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'Estudiantes','action' => 'notasLapso',
                $userActivo['estudiantes'][0]['id']])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Notas de Lapso
            </a>
		</li>
    </ul>
</li>