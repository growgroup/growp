<?php
/**
 * テーマのための関数
 * =====================================================
 * @package  growp
 * @since 1.0.0
 * =====================================================
 */


// テンプレートのパス
define('TEMPLATE_PATH', __DIR__ );

// CSSファイル
define("GROWP_STYLESHEET_URL", get_stylesheet_directory_uri() . "/assets/css/style.css");

// テーマのJavaScriptファイル
define("GROWP_JAVASCRIPT_URL", get_stylesheet_directory_uri() . "/assets/js/scripts.js");

/**
 * テーマのための class
 */
require_once __DIR__ . "/src/classes/class-theme-wrapper.php";
require_once __DIR__ . "/src/classes/class-bulk-posts.php";
require_once __DIR__ . "/src/classes/class-menu-posts.php";
require_once __DIR__ . "/src/classes/class-post-type.php";
require_once __DIR__ . "/src/classes/class-tgm-plugin-activation.php";
require_once __DIR__ . "/src/classes/class-walker-comment.php";
require_once __DIR__ . "/src/classes/class-walker-nav.php";

/**
 * テンプレートタグ定義
 */
require_once __DIR__ . "/src/tags/nav.php";
require_once __DIR__ . "/src/tags/tag.php";
require_once __DIR__ . "/src/tags/template.php";
require_once __DIR__ . "/src/tags/url.php";

/**
 * アクションフック定義
 */
require_once __DIR__ . "/src/hooks/comment.php";
require_once __DIR__ . "/src/hooks/default-plugins.php";
require_once __DIR__ . "/src/hooks/extras.php";
require_once __DIR__ . "/src/hooks/scripts.php";
require_once __DIR__ . "/src/hooks/setup.php";
require_once __DIR__ . "/src/hooks/sidebar.php";





