<?php
/**
 * ソーシャルシェアボタン用テーマ内プラグイン
 */

define( "GROWP_SOCIAL_BUTTON_URL", get_template_directory_uri() . '/inc/social-share/assets/images/' );
define( "GROWP_SOCIAL_BUTTON_ASSETS_URL", get_template_directory_uri() . '/inc/social-share/assets/' );

require_once( __DIR__ . "/class-social-share.php" );

require_once( __DIR__ . "/service/base.php" );
require_once( __DIR__ . "/service/twitter.php" );
require_once( __DIR__ . "/service/facebook.php" );
require_once( __DIR__ . "/service/hatenabookmark.php" );
require_once( __DIR__ . "/service/googleplus.php" );
require_once( __DIR__ . "/service/feedly.php" );
require_once( __DIR__ . "/service/rss.php" );
require_once( __DIR__ . "/service/pocket.php" );
require_once( __DIR__ . "/widget/widget.php" );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'social-share-css', GROWP_SOCIAL_BUTTON_ASSETS_URL . '/css/social-buttons.css',
		array() );
	wp_enqueue_script( 'social-share-js', GROWP_SOCIAL_BUTTON_ASSETS_URL . '/js/social-buttons.js',
		array( 'jquery' ) );
} );

/**
 * シェアボタンのインスタンスを取得する
 */
function growp_get_sharebutton() {
	return new GrowpSocialShare();
}

function growp_get_snsbutton_url( $service, $type ) {
	$classname = $service . "Button";
	$prop_name = $type . "_results_url";
	if ( ! class_exists( $classname ) ) {
		return false;
	}
	$button = new $classname;

	return isset( $button->{$prop_name} ) ? $button->{$prop_name} : false;
}

/**
 * ソーシャルサービスのリスト
 * @return array
 */
function growp_social_buttons() {
	return [
		"twitter"        => "Twitter",
		"facebook"       => "Facebook",
		"googleplus"     => "Google Plus",
		"hatenabookmark" => "Hatena Bookmark",
//		"feedly"         => "Feedly",
		"pocket"         => "Pocket",
//		"rss"            => "RSS",
	];
}
