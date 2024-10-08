<div class="metabox-holder" style="padding-top: 20px">

    <?php foreach ($template_data['settings_api_obj']->get_settings_sections() as $form) { ?>
        <div id="<?php echo $form['id']; ?>" class="<?php echo SKWPALC_PREFIX; ?>-group" style="display: none;">


            <div class="skwpalc-content">
                <form method="post" action="options.php">
                    <?php
                    do_action('wsa_form_top_' . $form['id'], $form);
                    settings_fields($form['id']);
                    do_settings_sections($form['id']);
                    do_action('wsa_form_bottom_' . $form['id'], $form);
                    $settings_fields=$template_data['settings_api_obj']->get_settings_fields();
                    if (isset($settings_fields[$form['id']])):
                        ?>
                        <div style="padding-left: 10px">
                            <?php submit_button(); ?>
                        </div>
                    <?php endif; ?>
                </form>

            </div>

            <?php
            if (isset($form['help_content'])) {
                ?>

                <div class="skwpalc-help-sidebar">
                    <h2><?php echo __('Sample Result', 'sk-wp-admin-login-captcha') ?></h2>
                    <div class="skwpalc-help-sidebar-content">
                        <?php
                        echo $form['help_content'];
                        
                        ?>
                    </div>
                </div>

                <?php
            }
            ?>
        </div>
    </div>
<?php } ?>
</div>