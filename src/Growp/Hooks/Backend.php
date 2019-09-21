<?php

namespace Growp\Hooks;

use function add_action;
use function count;
use function esc_attr;
use function explode;
use function get_page_by_path;
use function get_post_types;
use Growp\Mock\FrontAndHome;
use Growp\Mock\MwWpForm;
use Growp\Mock\PrivacyPolicy;
use Growp\Mock\Sitemap;
use Growp\Mock\TinymceAdvanced;
use Growp\Mock\WpAdminUiCustomize;
use Growp\Resource\Resource;
use Growp\TemplateTag\Tags;
use Growp\TemplateTag\Utils;
use function implode;
use function join;
use function strpos;
use function update_post_meta;
use function wp_insert_post;
use function wp_send_json_error;
use function wp_send_json_success;
use function wp_verify_nonce;

class Backend {

	protected static $instance = null;

	private function __construct() {

		add_action( 'login_head', [ $this, 'login_head' ] );

		add_action( "admin_init", [ $this, "original_dashboard_widget" ] );
		add_action( "admin_init", [ $this, "disable_updated" ] );
		add_filter( 'wp_dashboard_widgets', [ $this, "disable_dashoboard_widget" ], 100 );

		add_action( 'admin_head', [ $this, 'admin_head_css_js' ] );
		add_action( "admin_footer_text", [ $this, 'admin_footer_copyright' ] );
		add_filter( 'admin_body_class', [ $this, 'admin_body_class' ] );
		add_action( "admin_menu", [ $this, 'admin_menu' ] );

		add_filter( "theme_templates", [ $this, 'theme_templates' ], 10, 4 );
		add_action( 'admin_init', [ $this, 'add_editor_roles' ] );
		add_action( 'editable_roles', [ $this, 'filter_editable_roles' ] );
		add_filter( 'tiny_mce_before_init', [ $this, 'mce_options' ] );
		add_action( "after_switch_theme", [ $this, 'mock_init' ] );
		add_action( "wp_before_admin_bar_render", [ $this, 'admin_bar' ], 1000 );

		add_filter( 'kirki_telemetry', '__return_false' );

		$post_types = get_post_types( [
			'public' => true
		] );

		foreach ( $post_types as $post_type ) {
			add_filter( "manage_{$post_type}_posts_columns", function ( $column ) {

				if ( isset( $column["tags"] ) ) {
					unset( $column["tags"] );
				}
				if ( isset( $column["comments"] ) ) {
					unset( $column["comments"] );
				}
				if ( isset( $column["author"] ) ) {
					unset( $column["author"] );
				}
				// Yoast SEOに対して無効化する
				foreach (
					[
						"wpseo-links",
						"wpseo-score",
						"wpseo-title",
						"wpseo-metadesc",
						"wpseo-focuskw",
						"wpseo-score-readability",
					] as $_meta_key
				) {
					if ( isset( $column[ $_meta_key ] ) ) {
						unset( $column[ $_meta_key ] );
					}
				}

				return $column;
			}, 99, 1 );
		}

		add_action( "wp_ajax_growp_create_page", function () {
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
//			echo '<pre>';
//			echo var_dump($_values["nonce"]);
//			echo '</pre>';
//			if ( ! wp_verify_nonce( $_values["nonce"], "GROWP_THEMECUSTOMIZER" ) ) {
//				wp_send_json_error([
//					"message" => "ページを作成できませんでした。不正なアクセス"
//				]);
//				exit;
//			}
			$resource     = Resource::get_instance();
			$sitetree     = $resource->sitetree;
			$pageKey      = $_values["pageKey"];
			$post_name    = $_values["post_name"];
			$current_site = $sitetree[ $pageKey ];
			$post_content = " ";
			if ( $_values["post_content_save"] === "1" ) {
				$post_content = $current_site->main_content_html;
			}

			$_post_slug = explode( "/", $post_name );
			$parent     = "";

			if ( get_page_by_path( $post_name ) )
			if ( count( $_post_slug ) !== 1 ) {
				$post_name = $_post_slug[ ( count( $_post_slug ) - 1 ) ];
				unset( $_post_slug[ count( $_post_slug ) - 1 ] );
				$parent = join( "/", $_post_slug );
			}

//			echo '<pre>';
//			echo var_dump( $parent );
////			echo var_dump( $post_slug );
//			echo '</pre>';
//			exit;

			if ( $parent ) {
				$parentpost = get_page_by_path( $parent );
			}
//			exit;
			$args = [
				'post_title'   => esc_html( $_values["post_title"] ),
				'post_name'    => $post_name,
				'post_status'  => "publish",
				'post_type'    => "page",
				'post_content' => $post_content,
			];
			if ( $parentpost ) {
				$args["post_parent"] = $parentpost->ID;
			}
			$_post_id = wp_insert_post( $args );
			update_post_meta( $_post_id, "growp_inserted_true", true );
			wp_send_json_success( [
				'message' => "ページを作成しました",
				'post_id' => $_post_id,
			] );
		} );


	}

	/**
	 * ダッシュボードのウィジェットを非表示にする
	 * @return array|string
	 */
	public function disable_dashoboard_widget() {
		if ( ! Tags::get_option( "growp_admincustomize_admin_menu_dashboard" ) ) {
			return [];
		}
		if ( Utils::is_administrator() ) {
			return [];
		}
		global $wp_meta_boxes;
		unset( $wp_meta_boxes["dashboard"]["normal"]["core"]["dashboard_activity"] );
		unset( $wp_meta_boxes["dashboard"]["side"]["core"]["dashboard_quick_press"] );
		unset( $wp_meta_boxes["dashboard"]["side"]["core"]["dashboard_primary"] );

		return [];
	}

	/**
	 * 更新を停止
	 */
	public function disable_updated() {
		$update_notice = Tags::get_option( "growp_admincustomize_admin_update_notice" );
		if ( Utils::is_administrator() ) {
			return "";
		}
		if ( $update_notice ) {
			remove_action( 'admin_notices', 'update_nag', 3 );
			remove_action( 'admin_notices', 'maintenance_nag', 10 );
			remove_action( 'load-update-core.php', 'wp_update_plugins' );
			add_filter( 'pre_site_transient_update_plugins', function () {
				return null;
			} );
		}
	}

	/**
	 * オリジナルダッシュボード
	 * @return string
	 */
	public function original_dashboard_widget() {

		$org_dashboard_content = Tags::get_option( "growp_admincustomize_admin_menu_dashboard_org" );
		if ( Utils::is_administrator() ) {
			return "";
		}
		if ( $org_dashboard_content ) {
			remove_action( "welcome_panel", "wp_welcome_panel" );
			add_action( "welcome_panel", function () use ( $org_dashboard_content ) {
				$markdown = file_get_contents( get_theme_file_path( "assets/css/markdown.css" ) );
				?>
				<style>
					<?php echo $markdown ?>
				</style>
				<div class="markdown-body">
					<?php echo $org_dashboard_content; ?>
				</div>
				<?php
			} );
		}

	}

	/**
	 * テーマのテンプレート
	 *
	 * @param $templates
	 * @param $themes
	 * @param $post
	 * @param $post_type
	 *
	 * @return array|mixed
	 */
	public function theme_templates( $templates, $themes, $post, $post_type ) {
		$post_templates = [];
		$files          = (array) $themes->get_files( 'php', 4, true );

		foreach ( $files as $file => $full_path ) {
			if ( ! preg_match( '|Template Name:(.*)$|mi', file_get_contents( $full_path ), $header ) ) {
				continue;
			}

			$types = array( 'page' );
			if ( preg_match( '|Template Post Type:(.*)$|mi', file_get_contents( $full_path ), $type ) ) {
				$types = explode( ',', _cleanup_header_comment( $type[1] ) );
			}

			foreach ( $types as $type ) {
				$type = sanitize_key( $type );
				if ( ! isset( $post_templates[ $type ] ) ) {
					$post_templates[ $type ] = array();
				}

				$post_templates[ $type ][ $file ] = _cleanup_header_comment( $header[1] );
			}
		}

		return ( isset( $post_templates[ $post_type ] ) ? $post_templates[ $post_type ] : [] );

	}

	/**
	 * 管理バーを表示
	 *
	 * @param $wp_admin_bar
	 *
	 * @return string
	 */
	public function admin_bar() {
		global $wp_admin_bar;
		$admin_bar = Tags::get_option( "growp_admincustomize_admin_menu_adminbar" );
		if ( ! $admin_bar ) {
			return $wp_admin_bar;
		}
		if ( Utils::is_administrator() ) {
			return "";
		}
		$wp_admin_bar->remove_node( "wporg" );
		$wp_admin_bar->remove_node( "documentation" );
		$wp_admin_bar->remove_node( "support-forums" );
		$wp_admin_bar->remove_node( "wp-logo-external" );
		$wp_admin_bar->remove_node( "comments" );
		$wp_admin_bar->remove_node( "comments" );
		$wp_admin_bar->remove_node( "updates" );
		$wp_admin_bar->remove_node( "feedback" );
		$wp_admin_bar->remove_node( "wp-logo" );
		$wp_admin_bar->remove_node( "wpseo-menu" );
		$wp_admin_bar->remove_node( "query-monitor" );
//		dump($wp_admin_bar);
		$wp_admin_bar->remove_menu( "new_draft" );

	}


	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * ログイン画面のロゴの変更と、独自CSSの調整
	 */
	public function login_head() {
		$css = Tags::get_option( "growp_admincustomize_login_panel_css" );
		?>
		<style>
			.login h1 a {
				background-image: url(<?php echo  Tags::get_option("growp_base_logo_image_in_header") ?>);
			}

			<?php echo $css ?>
		</style>
		<?php
	}


	/**
	 * 管理画面フッターのコピーライトをカスタマイズ
	 */
	public function admin_footer_copyright() {
		$copy = Tags::get_option( "growp_admincustomize_copyright_right" );
		echo $copy;
	}


	/**
	 * 管理画面の head タグ内に記載する
	 */
	public function admin_head_css_js() {
		$css = Tags::get_option( "growp_admincustomize_css_js_css" );
		$js  = Tags::get_option( "growp_admincustomize_css_js_js" );
		if ( $css ) {
			?>
			<style>
				<?php echo $css ?>
			</style>
			<?php
		}
		if ( $js ) {
			?>
			<script>
				<?php echo $js ?>
			</script>
			<?php
		}

	}

	/**
	 * 管理画面の body タグに権限情報を付与する
	 *
	 * @param $classes
	 *
	 * @return string
	 */
	public function admin_body_class( $classes ) {
		$current_user = wp_get_current_user();
		if ( isset( $current_user->roles[0] ) ) {
			$classes .= " role-" . $current_user->roles[0];
		}

		return $classes;
	}


	/**
	 * モックの作成
	 */
	public function mock_init() {
		new Sitemap();
		new MwWpForm();
		new FrontAndHome();
		new PrivacyPolicy();
		new WpAdminUiCustomize();
		new TinymceAdvanced();
	}

	/**
	 * 編集者の権限を変更し、ユーザーを追加することができるように
	 */
	public function add_editor_roles() {
		$role = get_role( 'editor' );
		$role->add_cap( 'delete_users' );
		$role->add_cap( 'create_users' );
		$role->add_cap( 'remove_users' );
		$role->add_cap( 'edit_users' );
		$role->add_cap( 'list_users' );
	}

	/**
	 * 管理メニューを加工する
	 * @return array
	 */
	public function admin_menu() {
		$admin_menu_option = Tags::get_option( "growp_admincustomize_admin_menu_menu" );
		if ( ! $admin_menu_option ) {
			return false;
		}
		global $menu;
		if ( Utils::is_administrator() ) {
			return $menu;
		}

		$media = $menu[10];
		unset( $menu[10] );
		unset( $menu[15] ); // リンク
		unset( $menu[25] ); // コメント
		unset( $menu[60] ); // 外観
		unset( $menu[65] ); // プラグイン
		unset( $menu[75] ); // ツール
		unset( $menu[80] ); // 設定
		unset( $menu["80.025"] ); // カスタムフィールド
		foreach ( $menu as $mkey => $m ) {
			if ( $m[2] === "edit.php?post_type=growp_acf_block" ) {
				unset( $menu[ $mkey ] );
			}
			if ( $m[2] === "wpseo_dashboard" ) {
				unset( $menu[ $mkey ] );
			}
		}
		$menu["98.0098"] = $media;
	}

	/**
	 * 管理者以外は新規ユーザー登録で管理者権限アカウントを追加できないように
	 *
	 * @param $all_roles
	 *
	 * @return array
	 */
	public function filter_editable_roles( $all_roles ) {
		$current_user = wp_get_current_user();
		if ( isset( $current_user->roles[0] ) && $current_user->roles[0] !== "administrator" ) {
			unset( $all_roles["administrator"] );
		}

		return $all_roles;
	}

	/**
	 * Tinymceのオプションを加工
	 *
	 * @param $init_array
	 *
	 * @return mixed
	 */
	public function mce_options( $init_array ) {
		global $allowedposttags;
		$init_array['valid_elements']          = '*[*]';
		$init_array['extended_valid_elements'] = '*[*]';
		$init_array['valid_children']          = '+a[' . implode( '|', array_keys( $allowedposttags ) ) . ']';
		$init_array['indent']                  = true;
		$init_array['wpautop']                 = false;
		$init_array['force_p_newlines']        = false;

		return $init_array;
	}


}
