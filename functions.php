<?php
/**
 * テーマのための関数
 * =====================================================
 * @package  epigone
 * @since 1.0.0
 * =====================================================
 */

define("EPIGONE_STYLESHEET_URL", get_stylesheet_directory_uri() . "/assets/css/style.css");
define("EPIGONE_JAVASCRIPT_URL", get_stylesheet_directory_uri() . "/assets/js/scripts");

load_template( get_template_directory() . '/inc/init.php', true );
