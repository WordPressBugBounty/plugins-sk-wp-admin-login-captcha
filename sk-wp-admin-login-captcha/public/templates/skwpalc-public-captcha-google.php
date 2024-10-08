<?php
if (SKWPALC_Captcha::is_google_recaptcha_settings_exists()) {
    ?>
    <p>
        <?php
        if (isset($template_data['captcha_label']) && !empty($template_data['captcha_label'])) {
            ?>
            <label><?php echo $template_data['captcha_label'] ?>
            </label>
            <?php
        }
        ?>

    <div id="skwpalc-google-captcha">
    </div> 
    </p>
    <?php
}
else {
    ?>
    <p><b><?php echo SKWPALC_NAME; ?></b> : <?php echo __('Google captcha settings are missing.', 'sk-wp-admin-login-captcha') ?></p>
    <?php
}
