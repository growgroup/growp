<?php

namespace Growp\Config;

use function get_theme_file_path;

class Config {

	private static $store = null;

	private static $instance = null;

	public function __construct() {
		$this->load();
	}

	/**
	 * シングルトンインスタンス
	 * @return Config|null
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * config ファイルを読み込む
	 */
	public function load() {
		$config      = require_once get_theme_file_path( "config.php" );
		self::$store = $config;
	}

	/**
	 * 取得する
	 *
	 * @param $key
	 *
	 * @return bool|mixed
	 */
	public static function get( $key ) {
		if ( isset( self::$store[ $key ] ) ) {
			return self::$store[ $key ];
		}

		return false;
	}
}
