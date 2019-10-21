<?php

namespace Growp\Devtools\Packages;

use function add_action;
use function admin_url;
use function count;
use function esc_html;
use function explode;
use function get_page_by_path;
use Growp\Resource\Resource;
use Growp\TemplateTag\Utils;
use GUrl;
use function is_admin;
use function join;
use Symfony\Component\HttpFoundation\Request;
use function update_post_meta;
use function wp_create_nonce;
use function wp_insert_post;
use function wp_send_json_success;
use function wp_update_post;

class HtmlInfo {

	protected static $instance = null;

	/**
	 * Frontend constructor.
	 * 初期化
	 */
	private function __construct() {
		add_action( "admin_bar_menu", [ $this, "admin_bar" ] );
		add_action( "wp_footer", [ $this, "render_script" ] );
		add_action( "admin_footer", [ $this, "render_script" ] );
		add_action( "wp_ajax_growp_get_html_info", [ $this, "get_html_info" ] );
		add_action( "wp_ajax_growp_create_page", [ $this, "create_page" ] );

	}

	public function create_page() {
		$_keys   = [
			'action',
			'nonce',
			'pageKey',
			'post_content_save',
			'post_name',
			'post_title',
		];
		$_values = [];
		foreach ( $_keys as $key ) {
			$_values[ $key ] = isset( $_POST[ $key ] ) ? esc_html( $_POST[ $key ] ) : "";
		}
		$resource     = Resource::get_instance();
		$sitetree     = $resource->sitetree;

		$pageKey      = $_values["pageKey"];
		$post_name    = $_values["post_name"];
		$current_site = $sitetree[ $pageKey ];


		$post_content = " ";
		if ( $_values["post_content_save"] === "1" ) {
			$post_content = $current_site->main_content_html;
		}

		$_post_slug  = explode( "/", $post_name );
		$parent      = "";
		$parent_post = null;

		$registerd_post = false;
		if ( get_page_by_path( $post_name ) ) {
			$registerd_post = get_page_by_path( $post_name );
		}

		if ( count( $_post_slug ) !== 1 ) {
			$post_name = $_post_slug[ ( count( $_post_slug ) - 1 ) ];
			unset( $_post_slug[ count( $_post_slug ) - 1 ] );
			$parent = join( "/", $_post_slug );
		}

		if ( $parent ) {
			$parent_post = get_page_by_path( $parent );
		}
		$args = [
			'post_title'   => esc_html( $_values["post_title"] ),
			'post_name'    => $post_name,
			'post_status'  => "publish",
			'post_type'    => "page",
			'post_content' => $post_content,
		];
		if ( $parent_post ) {

			$args["post_parent"] = $parent_post->ID;
		}

		if ( $registerd_post ) {
			if ( $_values["post_content_save"] !== "1" ) {
				unset( $args["post_content"] );
			}
			$args["ID"] = $registerd_post->ID;

			$_post_id = wp_update_post( $args );

		} else {
			$_post_id = wp_insert_post( $args );
		}

		update_post_meta( $_post_id, "growp_inserted_true", true );
		wp_send_json_success( [
			'message' => "ページを作成しました",
			'post_id' => $_post_id,
		] );
	}

	public function get_html_info() {
		$resource = Resource::get_instance();
		wp_send_json_success( $resource );
		exit;
	}

	public function render_script() {

		?>
		<script>
			;(function ($) {
				var register_lock = false;

				function escape_html(string) {
					if (typeof string !== 'string') {
						return string;
					}
					return string.replace(/[&'`"<>]/g, function (match) {
						return {
							'&': '&amp;',
							"'": '&#x27;',
							'`': '&#x60;',
							'"': '&quot;',
							'<': '&lt;',
							'>': '&gt;',
						}[match]
					});
				}

				function renderHtmlInfoTable(e) {
					$.ajax({
						type: "post",
						url: "<?php echo admin_url( "/admin-ajax.php" );?>",
						data: {
							action: "growp_get_html_info",
							nonce: "<?php wp_create_nonce( HtmlInfo::class ) ?>",
						}
					}).done(function (res) {
						let content = res.data;
						let _siteTree = res.data.sitetree;
						let siteTree = [];
						for (let key in _siteTree) {
							siteTree.push(_siteTree[key])
						}

						// siteTree = siteTree.sort(function (a, b) {
						// 	if (a.relative_path === "/index.html") {
						// 		return -1;
						// 	}
						// 	return 1;
						// });
						let $htmlInfoTable = $("<table />", {class: "g-sitetree-table"});
						$htmlInfoTable.append($("<thead><tr><th>ID</th><th>タイトル</th><th>スラッグ</th><th>HTML</th></tr></thead>"));
						let $htmlInfoTable_tbody = $("<tbody />");

						function escapeRegExp(string) {
							return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
						}

						// テンプレート変換用HTMLを見る
						$(document).on("click", ".show_template_html_code", function () {
							let pageKey = $(this).data("site-key");
							var _html = siteTree[pageKey].raw_html;
							var replaceMatrix = {
								'src="\/assets\/': 'src="\<\?php GUrl::the_asset(); \?\>/assets/',
								'style="background\-image:url\(\'\/': 'style="background-image:url(\'\<\?php GUrl::the_asset(); \?\>/',
								'style="background\-image:url\(\/': 'style="background-image:url(\<\?php GUrl::the_asset(); \?\>/',
								'style="background\-image: url\(\/': 'style="background-image: url(\<\?php GUrl::the_asset(); \?\>/',
								'style="background\-image: url\(\'\/': 'style="background-image: url(\'\<\?php GUrl::the_asset(); \?\>/',
								'style="background\-image: url\("\/': 'style="background-image: url("\<\?php GUrl::the_asset(); \?\>/',
								'href="\/': ' href="\<\?php echo home_url() \?\>/'
							};
							for (var rkey in replaceMatrix) {
								_html = _html.replace(new RegExp(escapeRegExp(rkey), "g"), replaceMatrix[rkey]);
							}

							let $pre = $("<textarea />", {style: "height: 100vw", html: escape_html(_html)});
							new jBox('Modal', {
								title: "テンプレート変換用HTML",
								content: $pre,
								zIndex: 1000000200,
								color: 'black',
								width: "100vw",
								height: "100vh",
							}).open();
							let editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
							editorSettings.codemirror = _.extend(
								{},
								editorSettings.codemirror,
								{
									mode: 'html'
								},
							);
							wp.codeEditor.initialize($pre);
						});
						// HTMLを見る
						$(document).on("click", ".show_html_code", function () {
							let pageKey = $(this).data("site-key");

							let $pre = $("<textarea />", {style: "height: 100vw", html: escape_html(siteTree[pageKey].main_content_html)});
							new jBox('Modal', {
								title: "HTML",
								content: $pre,
								zIndex: 1000000200,
								color: 'black',
								width: "100vw",
								height: "100vh",
							}).open();
							let editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
							editorSettings.codemirror = _.extend(
								{},
								editorSettings.codemirror,
								{
									mode: 'html'
								},
							);
							wp.codeEditor.initialize($pre);
						});

						// ページを作成する
						$(document).on("click", ".craete_page", function (e) {
							e.preventDefault();
							let pageKey = $(this).data("site-key");
							let page = siteTree[pageKey];
							let $content = $("<div />", {class: "g-createpage-form"});
							let $pageName = $("<input />", {
								type: "text",
								name: "post_title",
								id: "page_create_post_title" + pageKey,
								value: page.title
							});
							let $pageSlug = $("<input />", {
								type: "text",
								name: "post_name",
								id: "page_create_post_name" + pageKey,
								value: page.relative_path.replace("index.html", "").slice(1, -1),
							});
							let $pageContent = $("<input />", {
								name: "post_content",
								type: "checkbox",
								checked: true,
								id: "page_create_post_content" + pageKey,
								value: "1",

							});
							let $pageRewrite = $("<input />", {
								name: "page_rewrite",
								type: "checkbox",
								checked: true,
								id: "page_rewrite" + pageKey,
								value: "1",
							});

							var $row = $("<div />", {class: "g-createpage-form__row"});
							$row.append("<label>ページタイトル</label><br>");
							$row.append($pageName);
							$content.append($row);
							var $row = $("<div />", {class: "g-createpage-form__row"});
							$row.append("<label>ページのスラッグ</label><br>");
							$row.append($pageSlug);
							$content.append($row);
							var $row = $("<div />", {class: "g-createpage-form__row"});
							$row.append($pageContent);
							$row.append("<label>ページのHTMLを一緒に保存する</label><br>");
							$content.append($row);
							var $row = $("<div />", {class: "g-createpage-form__row"});
							$row.append($pageRewrite);
							$row.append("<label>同じスラッグのページでもHTMLを上書く</label><br>");

							$content.append($row);
							let $submitButton = $("<button />", {
								class: "g-createpage-submit button-primary",
								text: "この情報で固定ページを作成"
							});
							$content.append($submitButton);

							let $jBox = new jBox("Modal", {
								title: "ページを作成",
								content: $content,
								zIndex: 100000000,
								width: 500,

							});
							$jBox.open();

							$submitButton.on("click", function (e) {
								e.preventDefault();
								$.ajax({
									type: "post",
									url: "<?php echo admin_url( "/admin-ajax.php" );?>",
									data: {
										action: "growp_create_page",
										nonce: "<?php echo admin_url( "/admin-ajax.php" ) ?>",
										pageKey: pageKey,
										post_content_save: $("#page_create_post_content" + pageKey).val(),
										post_name: $("#page_create_post_name" + pageKey).val(),
										post_title: $("#page_create_post_title" + pageKey).val(),
									}
								}).done(function (res) {
									if (typeof res.data.message !== "undefined") {
										new jBox('Notice', {
											content: res.data.message,
											zIndex: 10000000,
											color: 'black',
										}).open();
										$jBox.destroy();
									}
								})
							});
						});

						for (let key in siteTree) {
							let page = siteTree[key];
							let $row = $("<tr />");
							$row.append($("<td />", {text: (key - 0) + 1})); // ID
							$row.append($("<td />", {text: page.title})); // ページタイトル
							$row.append($("<td />", {text: page.relative_path.replace("index.html", "")})); // スラッグ
							let $showHtmlButton = $("<button />", {text: "HTMLを見る", class: "show_html_code"}).attr("data-site-key", key);
							let $showTemplateHtmlButton = $("<button />", {text: "テンプレート変換用HTMLを見る", class: "show_template_html_code"}).attr("data-site-key", key);
							let $pageCreateButton = $("<button />", {
								text: "固定ページを作成",
								class: "craete_page",
							}).attr("data-site-key", key);
							$row.append($("<td />").append($showHtmlButton).append($showTemplateHtmlButton).append($pageCreateButton)); // スラッグ
							$htmlInfoTable_tbody.append($row)
						}
						$htmlInfoTable.append($htmlInfoTable_tbody);
						new jBox('Modal', {
							title: "HTML情報",
							content: $htmlInfoTable,
							zIndex: 10000000,
							color: 'black',
						}).open();
					})
				}

				$(document).on("click", "#wp-admin-bar-htmlinfo a", function (e) {
					e.preventDefault();
					renderHtmlInfoTable(e);
				});

				$(document).on("click", "#growp_register_components", function (e) {
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
			'id'     => "htmlinfo",
			'parent' => "growp_dev",
			'title'  => "HTML情報",
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


