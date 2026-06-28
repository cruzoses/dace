<?= $this->Html->css(captcha_layout_stylesheet_url(), ['inline' => false]) ?>

<?= $this->Form->create(null, ['action' => 'index']) ?>

<!-- show captcha image html -->
<?= captcha_image_html() ?>

<!-- Captcha code user input textbox -->
<?= $this->Form->input('CaptchaCode', [
  'label' => __('Retype the characters from the picture:'),
  'maxlength' => '10',
  'style' => 'width: 270px;',
  'id' => 'CaptchaCode'
]) ?>

<?= $this->Form->button(__('Submit'), ['style' => 'float: left; margin-left: 20px;']) ?>
<?= $this->Form->end() ?>
