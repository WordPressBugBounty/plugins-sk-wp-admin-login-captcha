<?php
if (SKWPALC_Captcha::is_google_recaptcha_v3_settings_exists()) {
?>
    <p>
        <?php

        if (isset($template_data['captcha_label_v3']) && !empty($template_data['captcha_label_v3'])) {
        ?>
            <label><?php echo $template_data['captcha_label_v3'] ?>
            </label>
        <?php
        }
        ?>

    <div id="skwpalc-google-captchav3-badge" style="margin: 0px 0px 14px 0px;"></div>
    <input id="gctkn" type="hidden" name="g-recaptcha-response" value="" />
    </p>
<?php
} else {
?>
    <p><b><?php echo SKWPALC_NAME; ?></b> : <?php echo __('Google captcha settings are missing.', 'sk-wp-admin-login-captcha') ?></p>
<?php
}
