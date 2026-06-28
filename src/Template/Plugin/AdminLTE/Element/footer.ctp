<?php 
    use Cake\Core\Configure;
?>
<footer class="main-footer">
    <div class="container-fluid">
        <div class="pull-right hidden-xs">        
            <strong>Versi&oacute;n</strong>&nbsp;<?= Configure::read('Sistema.Version')?>
        </div>
        <strong>
            <?= Configure::read('Universidad.CopyRihgt'); ?> 
            <a href="https://www.uptbal.edu.ve" target="_blank"><?= Configure::read('Universidad.Siglas'); ?></a>            
            <?= Configure::read('Universidad.RIF'); ?>
        </strong> 
    </div>
</footer>

<!--
<footer class="main-footer">
    <?php if (isset($layout) && $layout == 'default'): ?>
        <div class="container-fluid">
    <?php endif; ?>
    <div class="pull-right hidden-xs">
        <strong>Versi&oacute;n</strong>&nbsp;<?= Configure::read('Sistema.Version')?>        
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights reserved.
    <?php if (isset($layout) && $layout == 'default'): ?>
        </div>
    <?php endif; ?>
</footer>
-->