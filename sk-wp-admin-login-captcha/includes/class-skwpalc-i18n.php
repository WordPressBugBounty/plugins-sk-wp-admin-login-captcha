<?php

if (!class_exists('SKWPALC_i18n')) {

    class SKWPALC_i18n {

        public function load_plugin_textdomain() {
            load_plugin_textdomain(
                    SKWPALC_SLUG, false, basename(SKWPALC_DIRECTORY_PATH) . '/languages/'
            );
        }

    }

}
