<?php $nId = $this->request->getSession()->read('UsuarioActivo.Docente.0.id') ?>
<!-- Lista -->
<li class="treeview">
    <a href="#"><i class="fa fa-archive"></i><span>&nbsp;Cursos</span>
        <span class="pull-right-container">
            <i class="fas fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="<?= $this->Url->build(['controller' => 'profesores','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Cursos Asignados
            </a>
        </li>
    </ul>
</li>