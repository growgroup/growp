<?php

namespace Growp\TemplateTag;

use Growp\Resource\Resource;
use function home_url;

class Url {

	/**
	 * 子テーマディレクトリのURLを取得する
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public static function asset( $path = "" ) {
		return esc_url( get_theme_file_uri( Resource::get_relative_html_path() . $path ) );
	}

	/**
	 * 子テーマディレクトリのURLを出力
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public static function the_asset( $path = "" ) {
		echo static::asset( $path );
	}


	public static function the_url( $path = "" ) {
		echo home_url( $path );
	}

}
