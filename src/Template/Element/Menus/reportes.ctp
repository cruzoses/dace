<!-- Reportes -->
<li class="treeview">
    <a href="#"><i class="fa fa-print"></i><span>&nbsp;Reportes</span>
        <span class="pull-right-container">
            <i class="fas fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="/reportes/actadenotas"><i class="far fa-dot-circle"></i>&nbsp;Acta de Notas</a>
        </li>
        <li>
            <a href="/reportes/listar-avance-docente"><i class="far fa-dot-circle"></i>&nbsp;Avance Docente</a>
        </li>
        <li>
            <a href="/reportes/listamaterias"><i class="far fa-dot-circle"></i>&nbsp;Asignaturas</a>
        </li>
        <li>
            <a href="/reportes/listarmallas"><i class="far fa-dot-circle"></i>&nbsp;Asignaturas X Programa</a>
        </li>
        <li>
            <a href="/reportes/listacarreras"><i class="far fa-dot-circle"></i>&nbsp;Carreras</a>
        </li>
        <li>
            <a href="/reportes/cursos"><i class="far fa-dot-circle"></i>&nbsp;Cursos</a>
        </li>
        <li class="treeview">							
            <a href="#"><i class="far fa-dot-circle"></i><span>Estad&iacute;sticas</span>
                <span class="pull-right-container">
                    <i class="fas fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="<?php echo $this->Url->build('/reportes/nuevoingreso')?>">
                        <i class="far fa-dot-circle"></i>&nbsp;Nuevo Ingreso
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->Url->build('/reportes/inscripcion')?>">
                        <i class="far fa-dot-circle"></i>&nbsp;Inscripci&oacute;n
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->Url->build('/reportes/estudiantes')?>">
                        <i class="far fa-dot-circle"></i>&nbsp;Estudiantes x Programa
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="/reportes/actodegrado"><i class="far fa-dot-circle"></i>&nbsp;Graduandos</a>
        </li>
        <li>
            <a href="/reportes/actasdegrado"><i class="far fa-dot-circle"></i>&nbsp;Libro de Actas</a>
        </li>
        <li>
            <a href="/reportes/ofertas">
                <i class="far fa-dot-circle"></i>&nbsp;Ofertas
            </a>
        </li>
        <li>
            <a href="/reportes/listaperiodos">
                <i class="far fa-dot-circle"></i>&nbsp;Per&iacute;odos
            </a>
        </li>
        <li>
            <a href="/reportes/listaprogramas">
                <i class="far fa-dot-circle"></i>&nbsp;Programas
            </a>
        </li>
    </ul>
</li>