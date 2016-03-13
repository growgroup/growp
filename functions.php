<?php
/**
 * テーマのための関数
 * =====================================================
 * @package  growp
 * @since 1.0.0
 * =====================================================
 */

define("GROWP_STYLESHEET_URL", get_stylesheet_directory_uri() . "/assets/css/style.css");
define("GROWP_JAVASCRIPT_URL", get_stylesheet_directory_uri() . "/assets/js/scripts.js");

load_template( get_template_directory() . '/inc/init.php', true );
