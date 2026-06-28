function securimageRefreshCaptcha(captcha_image)
{
    var captchaId = '';
    var chars     = "abcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < 40; ++i) {
        captchaId += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    document.getElementById(captcha_image + '_captcha_id').value = captchaId;
    document.getElementById(captcha_image).src = document.getElementById(captcha_image).src.replace(/\/([^\/]+)$/, '/' + captchaId);
}
