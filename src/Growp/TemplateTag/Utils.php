<?php

namespace Growp\TemplateTag;

use const ABSPATH;
use Growp\Config\Config;
use function is_user_logged_in;
use function site_url;
use function str_replace;
use const WP_CONTENT_DIR;
use function wp_create_nonce;
use function wp_get_theme;
use function wp_verify_nonce;

class Utils {

	public static function get_theme_name() {
		$theme = wp_get_theme();

		return $theme->template;
	}

	/**
	 * 管理者かどうか判断
	 * @return bool
	 */
	public static function is_administrator() {
		if ( ! is_user_logged_in() ) {
			return false;
		}
		$current_user = wp_get_current_user();
		$caps         = [
			"administrator"
		];
		foreach ( $caps as $cap ) {
			if ( $current_user->has_cap( $cap ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * 相対的なファイルパスを取得する
	 *
	 * @param $path
	 *
	 * @return string|void
	 */
	public static function get_relative_url( $path ) {
		$base = str_replace( ABSPATH, "", $path );
		return site_url( "/" . $base );
	}

	public static function create_nonce() {
		return wp_create_nonce( Config::get( "nonce" ) );
	}

	public static function verify_nonce( $nonce ) {
		return wp_verify_nonce( $nonce, Config::get( "nonce" ) );
	}
}
