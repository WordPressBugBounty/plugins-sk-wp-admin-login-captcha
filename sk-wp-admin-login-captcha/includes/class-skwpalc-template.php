<?php

if (!class_exists('SKWPALC_Template')) {

    class SKWPALC_Template {

        public static function include_template($name, $type = 'admin', $template_data = array()) {
            if (file_exists(SKWPALC_DIRECTORY_PATH . $type . '/templates/' . $name . '.php')) {
                require_once SKWPALC_DIRECTORY_PATH . $type . '/templates/' . $name . '.php';
            }
        }

    }

}
