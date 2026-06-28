<?= $this->Form->create(null, ['action' => 'index']) ?>

<?= $this->Html->css('sace.css') ?>

<?= $this->Captcha->render($captchaId, [
    'inputLabel' => __('Retype the characters from the picture:'),
]) ?>

<?= $this->Form->hidden('captcha_id', ['value' => $captchaId]) ?>

<?= $this->Form->button(__('Submit'), ['style' => 'float: left; margin-left: 20px;']) ?>
<?= $this->Form->end() ?>
