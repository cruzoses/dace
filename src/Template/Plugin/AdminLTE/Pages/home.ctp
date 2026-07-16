<?php $this->assign('title','Index' ); ?>
<?php if ($this->layout == 'default'): ?>
    <div class="visible-xs"><br></div>
    <div class="logo-container">
        <?= $this->Html->link(
            $this->Html->image("logos/logouptbal.png", ["alt" => "U.P.T.B.A.L"]),
            ['controller' => 'usuarios', 'action' => 'login'],
            ['escape' => false]
        ); ?>
    </div>
    <h1><p class="text-center"><strong><?= \Cake\Core\Configure::read('Sistema.Siglas')?></strong></p></h1>
<?php else: ?>
    <div class="logoAbajo">
        <?= $this->Html->image('logos/logouptbal.png', [
            'class' => 'img-responsive',
            'alt' => 'U.P.T.B.A.L',
            'height' => '128',
            'width' => '128'
        ]); ?>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function () 
    {
        var sName = "<?= $this->layout ?>";
        $('#modulo').text("Index");
        if (sName == "default") {
            $('body').addClass('home-page');
        }else{
            $('body').removeClass('home-page');
        }     
    });
</script>
