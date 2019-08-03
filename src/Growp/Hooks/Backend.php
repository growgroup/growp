<?php

namespace Growp\Hooks;

use Growp\Mock\FrontAndHome;
use Growp\Mock\MwWpForm;
use Growp\Mock\PrivacyPolicy;
use Growp\Mock\Sitemap;
use Growp\Mock\TinymceAdvanced;
use Growp\Mock\WpAdminUiCustomize;
use Growp\TemplateTag\Tags;

class Backend {

	protected static $instance = null;

	private function __construct() {

		add_action( 'login_head', [ $this, 'login_head' ] );
		add_action( 'admin_head', [ $this, 'admin_head_css_js' ] );
		add_action( "admin_footer_text", [ $this, 'admin_footer_copyright' ] );
		add_filter( 'admin_body_class', [ $this, 'admin_body_class' ] );
		add_action( "admin_menu", [ $this, 'admin_menu' ] );

		add_filter( 'wp_dashboard_widgets', function () {
			global $wp_meta_boxes;
			unset( $wp_meta_boxes["dashboard"]["normal"]["core"]["dashboard_activity"] );
			unset( $wp_meta_boxes["dashboard"]["side"]["core"]["dashboard_quick_press"] );
			unset( $wp_meta_boxes["dashboard"]["side"]["core"]["dashboard_primary"] );

			return [];
		}, 100 );

		// ようこそパネルを非表示
		remove_action( "welcome_panel", "wp_welcome_panel" );

		add_action( "admin_init", function () {
			$org_dashboard_content = Tags::get_option( "growp_admincustomize_admin_menu_dashboard_org" );

			if ( $org_dashboard_content ) {
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
		} );

		add_action( "admin_init", function () {
			$update_notice = Tags::get_option( "growp_admincustomize_admin_update_notice" );

			if ( $update_notice ) {
				remove_action( 'admin_notices', 'update_nag', 3 );
				remove_action( 'admin_notices', 'maintenance_nag', 10 );
				remove_action( 'load-update-core.php', 'wp_update_plugins' );
				add_filter( 'pre_site_transient_update_plugins', function () {
					return null;
				} );
			}
		} );


		add_filter( "theme_templates", [ $this, 'theme_templates' ], 10, 4 );
		add_action( 'admin_init', [ $this, 'add_editor_roles' ] );
		add_action( 'editable_roles', [ $this, 'filter_editable_roles' ] );

		add_filter( 'tiny_mce_before_init', [ $this, 'mce_options' ] );
		add_action( "after_switch_theme", [ $this, 'mock_init' ] );
		add_action( "admin_bar_menu", [ $this, 'admin_bar' ], 1000 );
	}

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

	public function admin_dashboard() {

	}

	/**
	 * 管理バーを表示
	 *
	 * @param $wp_admin_bar
	 *
	 * @return string
	 */
	public function admin_bar( $wp_admin_bar ) {
		$admin_bar = Tags::get_option( "growp_admincustomize_admin_menu_adminbar" );
		if ( ! $admin_bar ) {
			return $wp_admin_bar;
		}
		$current_user = wp_get_current_user();
		// 管理者の場合はそのまま表示
		if ( $current_user->has_cap( "administrator" ) ) {
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
		?>
		<style>
			<?php echo $css ?>
		</style>
		<script>
			<?php echo $js ?>
		</script>
		<?php
		$screen = get_current_screen();
		if ( isset( $screen->base ) && $screen->base === "dashboard" ) {
			?>

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
		$current_user = wp_get_current_user();

		// 管理者の場合はそのまま表示
		if ( $current_user->has_cap( "administrator" ) ) {
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
