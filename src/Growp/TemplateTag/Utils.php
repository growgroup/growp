<?php

namespace Growp\TemplateTag;

use function is_user_logged_in;
use function wp_get_theme;

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
}
