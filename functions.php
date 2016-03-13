<?php
/**
 * テーマのための関数
 * =====================================================
 * @package  growp
 * @since 1.0.0
 * =====================================================
 */

define("growp_STYLESHEET_URL", get_stylesheet_directory_uri() . "/assets/css/style.css");
define("growp_JAVASCRIPT_URL", get_stylesheet_directory_uri() . "/assets/js/scripts");

load_template( get_template_directory() . '/inc/init.php', true );
