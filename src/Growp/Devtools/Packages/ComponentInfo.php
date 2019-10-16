<?php

namespace Growp\Devtools\Packages;

use function add_action;
use function admin_url;
use Growp\TemplateTag\Utils;
use function is_admin;
use function wp_create_nonce;

class ComponentInfo {

	protected static $instance = null;

	/**
	 * Frontend constructor.
	 * 初期化
	 */
	private function __construct() {
		add_action( "admin_bar_menu", [ $this, "admin_bar" ] );
		add_action( "wp_footer", [ $this, "render_script" ] );
		add_action( "admin_footer", [ $this, "render_script" ] );
	}

	public function render_script() {

		?>
		<script>
			;(function ($) {
				$(document).on("click", "#componentinfo", function (e) {
					e.preventDefault();
					if (register_lock) {
						return false;
					}
					register_lock = true;
					let $button = $(this);
					let $spinner = $button.find(".spinner").addClass("is-active");
					$.ajax({
						type: "post",
						url: ajaxurl,
						data: {
							action: "growp_register_components",
							nonce: GROWP.nonce
						}
					}).done(function (res) {
						$spinner.removeClass("is-active");
						new jBox('Notice', {
							content: 'コンポーネントの登録に成功しました',
							zIndex: 10000000,
							color: 'black',
						}).open();
						register_lock = false;
					})
				});
			})(jQuery);
		</script>
		<?php
	}

	/**
	 * 管理バーに追加
	 *
	 * @param $wp_admin_bar
	 *
	 * @return string
	 */
	public function admin_bar( $wp_admin_bar ) {
		$wp_admin_bar->add_node( [
			'id'     => "componentinfo",
			'parent' => "growp_dev",
			'title'  => "コンポーネント情報",
			'href'   => "#",
		] );
		return "";
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


