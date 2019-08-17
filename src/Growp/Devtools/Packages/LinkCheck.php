<?php

namespace Growp\Devtools\Packages;

class LinkCheck {
	protected static $instance = null;

	/**
	 * Frontend constructor.
	 * 初期化
	 */
	private function __construct() {
		add_action( "admin_bar_menu", [ $this, "admin_bar" ] );
		add_action( "wp_footer", [ $this, 'render_template' ] );
	}

	public function render_template() {
		?>
		<script>
			/**
			 * リンクチェック
			 */
			;(function ($) {
				$(function () {
					$("#wp-admin-bar-broken_link_check").on("click", function (e) {
						e.preventDefault();
						let $links = $("html body a");
						$(".c-slidebar-button,.c-slidebar-menu,.u-hidden-lg,.u-hidden-sm").css({"cssText": "display: block !important"});

						function check_link(i, $links) {
							let max = $links.length;
							let skip = false;
							if (i > max) {
								return false;
							}
							if ($($links[i]).hasClass("qm-link")) {
								skip = true;
							}
							if ($($links[i]).closest("#wpadminbar").length) {
								skip = true;
							}
							if ($($links[i]).closest(".js-notewrap").length) {
								skip = true;
							}
							let url = $($links[i]).attr("href");
							if (typeof url === "undefined") {
								skip = true;
							}
							let $span = $("<span />", {
								text: url,
								class: 'js-link',
								style: 'border: 1px solid #e8e8e8;background: #fff; letter-spacing: 0px; font-weight: normal; position: absolute; font-size: 11px !important; font-family: sans-serif; color: #000;'
							});
							if (!skip && url.search("#") == 0) {
								$($links[i]).css("outline", "4px solid #ff0");
								$($links[i]).attr("title", "要確認");
								$($links[i]).append($span);
								skip = true;
							}

							if (skip) {
								i++;
								check_link(i, $links);
								return true;
							}

							$.ajax({
								url: url,
								type: 'GET',
								async: true,
								timeout: 10000,
								success: function () {
									$($links[i]).css("outline", "4px solid #44d858");
									$($links[i]).attr("title", "成功");
									$($links[i]).append($span);
									i++;
									check_link(i, $links);
								},
								error: function (msg) {
									console.log($($links[i]).attr("href") + " is error");
									$($links[i]).css("outline", "4px solid #ca3e3e");
									$($links[i]).attr("title", "失敗");
									$($links[i]).append($span);
									i++;
									check_link(i, $links);
								}
							});
						}

						check_link(0, $links);
					});
				})
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
		if ( ! is_admin() ) {
			$wp_admin_bar->add_node( [
				'id'     => "broken_link_check",
				'parent' => "growp_dev",
				'title'  => "簡易リンクチェック",
			] );
		}

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
