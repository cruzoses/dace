<?= $this->Form->create(null, ['action' => 'index']) ?>

<?= $this->Html->css('sace.css') ?>

<?= $this->Captcha->render([
    'captcha_id' => $captchaId,
    'input_text' => __('Retype the characters from the picture:'),
]) ?>

<?= $this->Form->button(__('Submit'), ['style' => 'float: left; margin-left: 20px;']) ?>
<?= $this->Form->end() ?>
