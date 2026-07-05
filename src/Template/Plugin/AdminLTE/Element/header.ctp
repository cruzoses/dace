<?php 
    use Cake\Core\Configure;
    use Cake\I18n\Time; 
?>

<?php if( isset($layout) && $layout == "default" ) : ?>

    <nav class="navbar navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="<?= $this->Url->build('/');?>" class="navbar-brand">
                    <i class="fa fa-graduation-cap fa-lg"></i>&nbsp;
                    <strong><?= Configure::read('Universidad.Siglas')?></strong>
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <?php if( !OUTSERVICE ) : ?>
                    <li><a href="#">Noticias <span class="sr-only">(current)</span></a></li>
                    <li><a href="#">Contacto</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Registro&nbsp;<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <?= $this->Html->link('Aspirante',['controller' => 'aspirantes', 'action' => 'registro']) ?>
                            </li>
                            <li><a href="<?= $this->Url->build('/profesor');?>">Docente</a></li>
                            <li><a href="#">Estudiante</a></li>
                            <li class="divider"></li>                        
                            <li>
                                <?php echo $this->Html->link('Olvidó la contrase&ntilde;a',
                                    ['controller' => 'usuarios', 'action' => 'nuevaclave'],
                                    ['escape' => false]);
                                ?>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <?php if( !isset($userActivo) && !OUTSERVICE ) :?>
                        <li>
                            <?php echo $this->Html->link('<i class="fa fa-sign-in"></i>&nbsp;Ingresar</a>',
                                array('controller' => 'usuarios', 'action' => 'login'),
                                array('escape' => false));
                            ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

<?php else : ?>

    <a href="<?= $this->Url->build('/')?>" class="logo">
        <span class="logo-mini visible-xs">
            <i class="fa fa-graduation-cap"></i>&nbsp;
            <strong><?= Configure::read('Universidad.Siglas');?></strong>
        </span>
        <span class="logo-lg hidden-xs">
            <i class="fa fa-graduation-cap"></i>&nbsp;
            <strong><?= Configure::read('Sistema.Siglas');?></strong>
        </span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <span class="navbar-brand namenav hidden-xs">
            <strong><?= Configure::read('Universidad.Nombre');?></strong>
        </span>
        <span class="navbar-brand logo-lg visible-xs">
            <strong><?= Configure::read('Sistema.Siglas');?></strong>
        </span>
        <div id="session-countdown" class="session-countdown-bar" style="display:none;">
            <span class="session-countdown-msg">
                <i class="fa fa-clock-o"></i>&nbsp;ATENCIÓN: Su sesión finalizará en <span class="countdown-seconds">60</span>s
            </span>
            <button type="button" class="btn btn-xs btn-warning" onclick="Inactividad.cancel()">
                <i class="fa fa-hand-stop-o"></i> Cancelar
            </button>
        </div>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?= isset($userActivo) ? $this->Html->image('logos/logouptbal.png',['class' => 'user-image', 'alt' => 'User Image']) : "";?>
                        <span class="hidden-xs"><?= isset($userActivo) ? $userActivo['alias'] : "";?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <?= isset($userActivo) ? $this->Html->image('site/avatar_generico.png',['class' => 'img-circle', 'alt' => 'User Image']) : "";?>
                            <p>
                                <?= isset($userActivo) ? $userActivo['name'] : "" ;?>
                                <small>
                                    <?= isset($userActivo) ? $userActivo['created']->format('d-m-Y g:i a') : "";?>
                                    <br>
                                    <?= isset($userActivo) ? implode(', ', array_column($userActivo['rols'], 'nombre')) : ""; ?>
                                </small>
                            </p>
                        </li>
                        <?php if( isset($userActivo['rols']) && !empty($userActivo['rols']) ) : ?>
                        <li class="user-body">
                            <div class="row">
                                <div class="col-xs-12 text-center">
                                    <a href="<?= $this->Url->build('/cambiaclave');?>" class="btn btn-sm btn-default"><i class="fa fa-key"></i>&nbsp;Cambiar Contrase&ntilde;a</a>
                                </div>
                            </div>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= $this->Url->build('/perfil');?>" class="btn btn-sm bg-orange"><i class="fa fa-user"></i>&nbsp;Perfil</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?= $this->Url->build('/logout');?>" class="btn btn-sm bg-maroon"><i class="fa fa-sign-out"></i>&nbsp;Cerrar</a>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>					
                </li>
                <?php if( $this->Permiso->tiene(1) ) : ?>
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

<?php endif ; ?>
