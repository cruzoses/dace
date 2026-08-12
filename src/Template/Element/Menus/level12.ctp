<!-- Institucion -->
<li class="treeview">
    <a href="#"><i class="fa fa-home"></i>&nbsp;<span>Instituci&oacute;n</span>
        <span class="pull-right-container">
            <i class="fas fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="<?= $this->Url->build(['controller' => 'firmas','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Firmas
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'indicadores','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Indicadores
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'subsistemas','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Sistemas de Estudio
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'sedes','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Sedes
            </a>
        </li>
    </ul>
</li>
<!-- Procesos -->
<li class="treeview">
    <a href="#"><i class="fa  fa-cogs"></i><span>&nbsp;Procesos</span>
        <span class="pull-right-container">
            <i class="fas fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="treeview">
            <a href="#"><i class="far fa-dot-circle"></i><span>Activar Carga de Notas</span>
                <span class="pull-right-container">
                    <i class="fas fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="<?php echo $this->Url->build('/carga_notas')?>">
                        <i class="far fa-dot-circle"></i>&nbsp;Per&iacute;odo
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->Url->build('/programas/calificar')?>">
                        <i class="far fa-dot-circle"></i>&nbsp;Programa
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <?php echo $this->Html->link('<i class="far fa-dot-circle"></i>&nbsp;Crear Histórico',
                array('controller' => 'procesos','action' => 'index'),
                array('escape' => false));
            ?>
        </li>
        <li>
            <?php echo $this->Html->link('<i class="far fa-dot-circle"></i>&nbsp;Calcular Indices',
                array('controller' => 'procesos','action' => 'calcularindice'),
                array('escape' => false));
            ?>
        </li>
    </ul>
</li>
<!-- Promociones -->
<li class="treeview">
    <a href="#"><i class="fa fa-graduation-cap"></i><span>&nbsp;Promociones</span>
        <span class="pull-right-container">
            <i class="fas fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="<?= $this->Url->build(['controller' => 'actos','action' => 'index'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Acto de Grado
            </a>
        </li>
        <li>
            <a href="<?= $this->Url->build(['controller' => 'actos','action' => 'libroActas'])?>">
                <i class="far fa-dot-circle"></i>&nbsp;Libro de Actas
            </a>
        </li>
    </ul>
</li>