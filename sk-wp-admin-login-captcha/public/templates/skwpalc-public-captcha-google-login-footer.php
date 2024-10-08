<script>
    var skwpalcGoogleReCaptchaonloadCallback = function () {
        grecaptcha.render('skwpalc-google-captcha', {
            'sitekey': '<?php echo $template_data["google_site_key"]; ?>'
        });
    };
</script>