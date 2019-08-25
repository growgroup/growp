<?php

namespace Growp\Devtools;

use function get_bloginfo;
use Growp\Devtools\Packages\DevInfo;
use Growp\Devtools\Packages\LinkCheck;
use Growp\Devtools\Packages\MetaInfo;
use Growp\Devtools\Packages\Note;
use Growp\TemplateTag\Utils;

class Devtools {

	protected static $instance = null;

	/**
	 * Frontend constructor.
	 * 初期化
	 */
	private function __construct() {
		if ( ! Utils::is_administrator() ) {
			return false;
		}
		Note::get_instance();
		LinkCheck::get_instance();
		MetaInfo::get_instance();
		DevInfo::get_instance();
		add_action( "admin_bar_menu", [ $this, 'admin_bar' ], 99 );
	}

	public function admin_bar( $wp_admin_bar ) {
		$wp_admin_bar->add_node( [
			'id'    => "growp_dev",
			'title' => "<svg style='vertical-align: middle;' width=\"24px\" height=\"23px\" viewBox=\"0 0 94 91\"><g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\"><g><rect id=\"Rectangle\" fill=\"#FFFFFF\" x=\"0\" y=\"0\" width=\"94\" height=\"91\" rx=\"8\"></rect><path d=\"M34,23 C32.2492851,23.016762 31.0124687,24.2619372 31,26 L31,68.999989 L62,68.999989 C63.7272872,69.0042742 64.9873241,67.7530043 65,66 L65,23 L34,23 Z M58,63 L37,63 C36.2230911,62.9985419 36.0041962,62.7850405 36,62 L36,29 C36,28.2181034 36.2201192,28 37,28 L58,28 C58.7414684,28 58.9615875,28.2181034 59,29 L59,41 L50,41 C49.3717545,40.9474695 49.1516353,41.1655729 49,42 L49,52 C49.1558315,52.3570375 49.3747264,52.5705389 50,52 L54,52 C54.521062,52.5705389 54.7399569,52.3570375 54,52 L54,47 L59,47 L59,62 C59.0004175,62.6474341 58.9433802,62.7774784 59,63 C58.7420065,62.9625493 58.6068483,63.0091408 58,63 Z\" fill=\"#5F6817\" fill-rule=\"nonzero\"></path></g></g></svg> 開発",
		] );
	}

	/**
	 * シングルトンインスタンスを取得
	 * @return null
	 */
	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}
}
