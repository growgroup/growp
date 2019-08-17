<?php

namespace Growp\Devtools\Packages;

use function add_action;
use Growp\TemplateTag\Utils;
use function is_admin;

class MetaInfo {

	protected static $instance = null;

	/**
	 * Frontend constructor.
	 * 初期化
	 */
	private function __construct() {
		add_action( "admin_bar_menu", [ $this, "admin_bar" ] );
		add_action( "wp_footer", [ $this, "render_script" ] );
	}

	public function render_script() {
		if ( is_admin() ){
			return false;
		}
		?>
		<script>
			;(function ($) {
				class MetaInfo {
					constructor() {
						this.namespace = "metainfo";
						this.open = window.localStorage.getItem("growp_metainfo_open");
						console.log(this.open);
						if ($.isEmptyObject(this.open)) {
							this.open = false;
						}
						if ( this.open === "false" ){
							this.open = false;
						}

						this.meta_tables = {
							title: {
								label: "title",
								selector: "title",
								callback: function ($el) {
									return $el.text();
								},
								error_case: (result) => {
									if (result) {
										return true;
									}
									return false;
								}
							},
							meta_desc: {
								label: "meta description",
								selector: "meta[name=description]",
								callback: function ($el) {
									return $el.attr("content")
								},
								error_case: (result) => {
									if (result) {
										return true;
									}
									return false;
								}
							},
							meta_index: {
								label: "meta index",
								selector: "meta[name=robots]",
								callback: function ($el) {
									return $el.attr("content")
								},
								error_case: (result) => {
									if (result.search("noindex") !== -1) {
										return false;
									}
									return true;
								}
							},
							ogp: {
								label: "meta ogp",
								selector: "meta[property*='og:'],meta[name*='twitter:']",
								callback: function ($el) {
									let items = [];
									for (let a in $el) {
										if (typeof $el[a].outerHTML === "string") {
											items.push($el[a].outerHTML);
										}
									}
									return $("<pre />", {
										text: items.join("\n")
									});
								},
								error_case: (result) => {
									if (result) {
										return true;
									}
									return false;
								}
							},
							h1: {
								label: "h1タグ",
								selector: "h1",
								callback: function ($el) {
									return $el.text();
								},
								error_case: (result) => {
									if (result) {
										return true;
									}
									return false;
								}
							},
							gtm: {
								label: "Googleタグマネージャー",
								selector: "h1",
								callback: function ($el) {
									let $scripttag = $("script[src*='https://www.googletagmanager.com/gtm.js?id=']");
									if ($scripttag.length && Boolean(window.google_tag_manager)) {
										let matches = $scripttag.attr("src").match(/id=(.*?)$/m);
										if (typeof matches[1] !== "undefined") {
											return "[設定済み] ID : <code>" + matches[1] + "</code>";
										}
									}
									return "[未設定]";
								},
								error_case: (result) => {
									if (result === "[未設定]") {
										return false;
									}
									return true;
								}
							},
							ga: {
								label: "GoogleAnalytics",
								selector: "h1",
								callback: function ($el) {
									let $scripttag = $("script[src*='www.google-analytics.com']");
									if ($scripttag.length && Boolean(window.gaData)) {
										var gaid = [];
										var hits = 0;
										for (var _gaid in window.gaData) {
											gaid.push(_gaid);
											hits = window.gaData[_gaid].hitcount
										}
										if (gaid.length === 1) {
											return `[設定済み] ID : <code>${gaid} </code> ヒット回数 : <code>${hits}</code>`;
										} else {
											return "[x 重複設定]";
										}
									}
									return "[未設定]";
								},
								error_case: (result) => {
									if (result === "[未設定]") {
										return false;
									}
									return true;
								}
							},

						};
						this.tables = this.parse();
						this.render();
						if (this.open) {
							this.$wrapper.slideDown(0);
						}
					}

					generateClass(classname) {
						return this.namespace + "-" + classname;
					}

					parse() {
						var tables = [];
						for (var key in this.meta_tables) {
							let $el = $(this.meta_tables[key].selector);
							for (var _key = 0; _key < $el.length; _key++) {
								if ($($el[_key]).closest("#query-monitor-main").length !== 0) {
									delete $el[_key];
								}
							}
							let val = this.meta_tables[key].callback($el);

							tables.push({
								th: this.meta_tables[key].label,
								td: val,
								error: this.meta_tables[key].error_case(val)
							});
						}
						return tables;
					}

					render() {
						let $table = $("<table />", {
							class: 'growpdev-table',
							style: "border-collapse: collapse; width: 100%;"
						});
						for (let key in this.tables) {
							let $tr = $('<tr />', {
								style: "border: 1px solid #ccc;"
							});
							let $th = $('<th />', {
								text: this.tables[key].th,
								style: "width: 200px; text-align: left; font-size: 13px; font-weight: bold; background: #252525; color: #fff; padding: 10px 16px; letter-spacing: 0px;"
							});
							let $td = $('<td />', {
								html: this.tables[key].td,
								style: "text-align: left; font-size: 13px; font-weight: bold; background: #FFF; color: #000; padding: 10px 16px; font-weight: normal; letter-spacing: 0px;"
							});

							if (!this.tables[key].error) {
								$td.css("background", "#F00")
							}
							$tr.append($th);
							$tr.append($td);
							$table.append($tr);
						}
						let $wrapper = $("<div />", {
							style: "display: none; background: #fff; padding: 16px 32px; position: relative; z-index: 1000; width: 100%; border-bottom: 1px solid #ccc;",
							class: this.generateClass("metainfo")
						});
						let $title = $("<div />", {
							style: "font-size: 18px; font-weight: bold; letter-spacing: 0px; color: #000; margin-bottom: 12px;",
							text: "メタ情報"
						});
						let $displayButton = $("<a />", {
							text: "x 隠す",
							style: "font-size: 14px; text-description: underline; letter-spacing: 0px; color: #000; float: right;"
						});
						$displayButton.on("click", (e) => {
							this.toggle();
						});
						$title.append($displayButton);
						$wrapper.append($title);
						$wrapper.append($table);
						$("body").prepend($wrapper);
						this.$wrapper = $wrapper;
					}

					toggle() {
						this.open = !this.open;
						if (this.open) {
							this.$wrapper.slideDown(0);
						} else {
							this.$wrapper.slideUp(0);
						}
						window.localStorage.setItem("growp_metainfo_open", this.open);
					}
				}

				$(function () {
					let metainfo = new MetaInfo();
					$("#wp-admin-bar-metainfo a").on("click", function (e) {
						e.preventDefault();
						metainfo.toggle();
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
		if ( is_admin() ){
			return false;
		}
		$wp_admin_bar->add_node( [
			'id'     => "metainfo",
			'parent' => "growp_dev",
			'title'  => "SEO情報",
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


