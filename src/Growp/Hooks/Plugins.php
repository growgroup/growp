<?php

namespace Growp\Hooks;

use function class_exists;

class Plugins {

	protected static $instance = null;

	/**
	 * Frontend constructor.
	 * 初期化
	 */
	private function __construct() {
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			add_action( 'tgmpa_register', [ $this, 'theme_register_required_plugins' ] );
		}
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

	public function theme_register_required_plugins() {

		$plugins = [

			[
				'name'             => 'Advanced Custom Fields Pro',
				'slug'             => 'advanced-custom-fields-pro',
				'required'         => true,
				'source'           => 'https://github.com/wp-premium/advanced-custom-fields-pro/archive/master.zip',
				'force_activation' => true,
			],
			[
				'name'             => 'Yoast SEO',
				'slug'             => 'wordpress-seo',
				'required'         => true,
				'force_activation' => true,
			],
			[
				'name'             => 'MW WP Form',
				'slug'             => 'mw-wp-form',
				'required'         => true,
				'force_activation' => false,
			],
			[
				'name'             => 'WP Migrate db',
				'slug'             => 'wp-migrate-db',
				'required'         => true,
				'force_activation' => false,
			],
			[
				'name'             => 'Duplicate Post',
				'slug'             => 'duplicate-post',
				'required'         => true,
				'force_activation' => true,
			],
			[
				'name'             => 'Custom Post Type Permalinks',
				'slug'             => 'custom-post-type-permalinks',
				'required'         => true,
				'force_activation' => true,
			],
			[
				'name'             => 'Custom Post Type UI',
				'slug'             => 'custom-post-type-ui',
				'required'         => true,
				'force_activation' => false,
			],
			[
				'name'             => 'Intuitive Custom Post Order',
				'slug'             => 'intuitive-custom-post-order',
				'required'         => true,
				'force_activation' => true,
			],

			[
				'name'             => 'TinyMCE Advanced',
				'slug'             => 'tinymce-advanced',
				'required'         => true,
				'force_activation' => false,
			],

			[
				'name'             => 'SiteGuard WP Plugin',
				'slug'             => 'siteguard',
				'required'         => false,
				'force_activation' => false,
			],

			[
				'name'             => 'Login rebuilder',
				'slug'             => 'login-rebuilder',
				'required'         => false,
				'force_activation' => false,
			],

			[
				'name'             => 'Google Analytics Dashboard for WP (GADWP)',
				'slug'             => 'google-analytics-dashboard-for-wp',
				'required'         => false,
				'force_activation' => false,
			],
			[
				'name'             => 'WP Multibyte Patch',
				'slug'             => 'wp-multibyte-patch',
				'required'         => true,
				'force_activation' => false,
			],
			[
				'name'             => 'User Switching',
				'slug'             => 'user-switching',
				'required'         => false,
				'force_activation' => false,
			],
			[
				'name'             => 'Query Monitor',
				'slug'             => 'query-monitor',
				'required'         => false,
				'force_activation' => false,
			],
		];

		$config = array(
			'id'           => 'tgmpa',
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => __( 'この通知を閉じる', 'growp' ),
			'is_automatic' => true,
			'message'      => '',
			'strings'      => array(
				'dismiss'                         => __( 'この通知を閉じる', 'growp' ),
				'page_title'                      => __( '必要なプラグインをインストール', 'growp' ),
				'menu_title'                      => __( 'プラグインインストール', 'growp' ),
				'installing'                      => __( 'プラグインをインストールしています: %s', 'growp' ),
				'oops'                            => __( 'Something went wrong with the plugin API.', 'growp' ),
				'notice_can_install_required'     => _n_noop(
					'このテーマは次のプラグインを必要とします: %1$s.',
					'このテーマは次のプラグインを必要とします: %1$s.',
					'growp'
				),
				// %1$s = plugin name(s).
				'notice_can_install_recommended'  => _n_noop(
					'このテーマは以下のプラグインを推奨しています: %1$s.',
					'このテーマは以下のプラグインを推奨しています: %1$s.',
					'growp'
				),
				// %1$s = plugin name(s).
				'notice_cannot_install'           => _n_noop(
					'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
					'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
					'growp'
				),
				// %1$s = plugin name(s).
				'notice_ask_to_update'            => _n_noop(
					'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
					'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
					'growp'
				),
				// %1$s = plugin name(s).
				'notice_ask_to_update_maybe'      => _n_noop(
					'There is an update available for: %1$s.',
					'There are updates available for the following plugins: %1$s.',
					'growp'
				),
				// %1$s = plugin name(s).
				'notice_cannot_update'            => _n_noop(
					'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
					'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
					'growp'
				),
				// %1$s = plugin name(s).
				'notice_can_activate_required'    => _n_noop(
					'次の必要なプラグインは現在有効になっていません: %1$s.',
					'次の必要なプラグインは現在有効になっていません: %1$s.',
					'growp'
				),
				// %1$s = plugin name(s).
				'notice_can_activate_recommended' => _n_noop(
					'次の推奨されているプラグインは現在有効になっていません: %1$s.',
					'The following recommended plugins are currently inactive: %1$s.',
					'growp'
				),
				// %1$s = plugin name(s).
				'notice_cannot_activate'          => _n_noop(
					'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
					'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
					'growp'
				),
				// %1$s = plugin name(s).
				'install_link'                    => _n_noop(
					'プラグインのインストールを始める',
					'プラグインのインストールを始める',
					'growp'
				),
				'update_link'                     => _n_noop(
					'プラグインの更新を始める',
					'プラグインの更新を始める',
					'growp'
				),
				'activate_link'                   => _n_noop(
					'プラグインを有効化する',
					'プラグインを有効化する',
					'growp'
				),
				'return'                          => __( 'プラグインインストーラーに戻る', 'growp' ),
				'plugin_activated'                => __( 'プラグインが正常に有効化されました。', 'growp' ),
				'activated_successfully'          => __( '次のプラグインが正常に有効化されました:', 'growp' ),
				'plugin_already_active'           => __( '対処なし。 プラグイン$1$sはすでに有効にされています。', 'growp' ),
				// %1$s = plugin name(s).
				'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.',
					'growp' ),
				// %1$s = plugin name(s).
				'complete'                        => __( 'All plugins installed and activated successfully. %1$s',
					'growp' ),
				// %s = dashboard link.
				'contact_admin'                   => __( 'Please contact the administrator of this site for help.',
					'tgmpa' ),

				'nag_type' => 'updated',
				// Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			),

		);

		tgmpa( $plugins, $config );
	}
}
