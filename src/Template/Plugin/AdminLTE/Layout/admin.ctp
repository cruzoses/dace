<?php
    use Cake\Core\Configure;
    $cakeDescription = __d('cake_dev', '.:: S.A.C.E :');
    $cakeVersion = __d('cake_dev', 'S.A.C.E. %s', Configure::version())
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">    
        <meta name="application-name" content="SACE UPTBAL">
        <title>
            <?= $cakeDescription ?>:
            <?= $this->fetch('title') ?>
        </title>
        <?= $this->Html->meta('favicon','img/site/favicon.ico',array('type'=>'icon')) ?>
        <!-- Bootstrap 3.3.7 -->
        <?= $this->Html->css('AdminLTE./bower_components/bootstrap/dist/css/bootstrap.min'); ?>
        <!-- Bootstrap Datepicker -->
        <?= $this->Html->css('AdminLTE./bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min'); ?>
        <!-- Bootstrap TimePicker -->
        <?= $this->Html->css('AdminLTE./plugins/timepicker/bootstrap-timepicker.min'); ?>
        <!-- Font Awesome -->
        <?= $this->Html->css('fontawesome/css/all.min') ?>
        <!--?= $this->Html->css('AdminLTE./bower_components/font-awesome/css/font-awesome.min'); ?-->        
        <!-- Sweetalert2 -->
        <?= $this->Html->css('sweetalert2.min'); ?>
        <!-- jQueryUI -->
        <!--?= $this->Html->css('AdminLTE./bower_components/jquery-ui/themes/base/jquery-ui.min'); ?-->
        <!-- Ionicons -->
        <?= $this->Html->css('AdminLTE./bower_components/Ionicons/css/ionicons.min'); ?>
        <!-- Select2 -->
        <?= $this->Html->css('AdminLTE./bower_components/select2/dist/css/select2.min'); ?>
        <!-- Theme style -->
        <?= $this->Html->css('AdminLTE.AdminLTE.min'); ?>
        <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
        <?= $this->Html->css('AdminLTE.skins/_all-skins.min'); ?>
        <!-- Google Font -->
        <!--?= $this->Html->css('google/css/fonts'); ?-->
        <!-- Sace style -->
        <?= $this->Html->css('sace'); ?>
        <!-- jQuery 3 -->
        <?= $this->Html->script('AdminLTE./bower_components/jquery/dist/jquery.min'); ?>
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <?= $this->fetch('css') ?>
    </head>
    <body class="skin-<?= Configure::read('Theme.skin'); ?> fixed">
        <div class="wrapper">
            <header class="main-header">
                <?= $this->element('header', ['layout' => 'admin']); ?>
            </header>
            <aside class="main-sidebar">
                <?= $this->Element('sidebar'); ?>
            </aside>
            <div class="content-wrapper">
                <div class="container-fluid">
                    <section class="content-header">
                        <h1><small><div class="current-time" id="time"></div></small></h1>
                        <ol class="breadcrumb">
                            <li>
                                <a href="#"><i class="fa fa-dashboard"></i>&nbsp;S.A.C.E</a>
                            </li>
                            <li><a href="#"><?= __($this->request->getParam('controller')) ?></a></li>
                            <li class="active"><?= __(ucfirst($this->request->getParam('action'))) ?></li>
                        </ol>
                    </section>
                    <section class="content">
                        <?= $this->Flash->render(); ?>
                        <?= $this->fetch('content'); ?>
                    </section>                
                </div>
            </div>
            <?= $this->element('footer'); ?>
            <aside class="control-sidebar control-sidebar-dark">
                <?= $this->element('control'); ?>
            </aside>
            <div class="control-sidebar-bg"></div>
        </div>
        <!-- Bootstrap 3.3.7 -->
        <?= $this->Html->script('AdminLTE./bower_components/bootstrap/dist/js/bootstrap.min'); ?>
        <!-- Bootstrap Datepicker -->
        <?= $this->Html->script('AdminLTE./bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min'); ?>
        <?= $this->Html->script('AdminLTE./bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min'); ?>
        <!-- Bootstrap TimePicker -->
        <?= $this->Html->script('AdminLTE./bower_components/bootstrap-timepicker/js/bootstrap-timepicker'); ?>
        <!-- sweetalert2 -->
        <?= $this->Html->script('sweetalert2.min'); ?>
        <!-- jQueryUI -->
        <!--?= $this->Html->script('AdminLTE./bower_components/jquery-ui/ui/datepicker.js'); ?-->
        <!--?= $this->Html->script('AdminLTE./bower_components/jquery-ui/ui/i18n/datepicker-es.js'); ?-->
        <!-- Select2 -->
        <?= $this->Html->script('AdminLTE./bower_components/select2/dist/js/select2.full.min'); ?>
        <?= $this->Html->script('AdminLTE./bower_components/select2/dist/js/i18n/es.js'); ?>
        <!-- AdminLTE App -->
        <?= $this->Html->script('AdminLTE.adminlte.min'); ?>
        <!-- Slimscroll -->
        <?= $this->Html->script('AdminLTE./bower_components/jquery-slimscroll/jquery.slimscroll.min'); ?>
        <!-- FastClick -->
        <?= $this->Html->script('AdminLTE./bower_components/fastclick/lib/fastclick'); ?>
        <!-- Funciones de la aplicacion -->
        <?= $this->Html->script('sace'); ?>
        <?= $this->Html->script('inactividad'); ?>
        <?= $this->fetch('script'); ?>
        <?= $this->fetch('scriptBottom'); ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".navbar .menu").slimscroll({
                    height: "200px",
                    alwaysVisible: false,
                    size: "3px"
                }).css("width", "100%");            
                var a = $('a[href="<?= $this->Url->build() ?>"]');
                if (!a.parent().hasClass('treeview') && !a.parent().parent().hasClass('pagination')) {
                    a.parent().addClass('active').parents('.treeview').addClass('active');
                }
                <?php if (isset($userActivo)) : ?>
                Inactividad.init({
                    timeout: <?= Configure::read('Inactividad.timeout') ?: 300000 ?>,
                    countdown: <?= Configure::read('Inactividad.countdown') ?: 60 ?>,
                    keepaliveInterval: <?= Configure::read('Inactividad.keepaliveInterval') ?: 120000 ?>
                });
                <?php endif; ?>
            });
            var basePath = "<?= $this->Url->build('/'); ?>"
            DateAndTime();
        </script>
    </body>
</html>