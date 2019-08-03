<?php

namespace Growp\Editor;

use GUrl;

class BlockEditor {

	public static $instance = null;

	private function __construct() {

		add_filter( "init", [ $this, "setup" ] );
		$this->add_assets();
//		$this->print_scripts();
//		$this->use_post_type();
	}

	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * テーマサポートの追加、
	 * エディタースタイルの設定
	 *
	 * @return void
	 */
	public function setup() {
		add_theme_support( 'editor-styles' );
		add_theme_support( 'align-wide' );
		add_editor_style( "resource/gg-styleguide/dist/assets/css/style.css" );
	}

	/**
	 * 追加のCSSを読み込み
	 *
	 * @return void
	 */
	public function add_assets() {
		add_action( 'enqueue_block_editor_assets', function () {
			wp_enqueue_style( 'growp_site_css', get_theme_file_uri( "/assets/css/block-editor.css" ), [ 'wp-block-library' ] );
			wp_enqueue_style( 'growp_site_css_app', GUrl::asset() . "/assets/css/app.css", [ 'wp-block-library' ] );
			wp_enqueue_code_editor(
				array_merge(
					array(
						'type'       => "html",
						'codemirror' => array(
							'indentUnit' => 2,
							'tabSize'    => 2,
							'mode'       => "php"
						),
					)
				)
			);
			wp_enqueue_script( 'growp_site_javascript_main', GUrl::asset() . "/assets/js/app.js", [ 'wp-block-library' ], true );
			wp_enqueue_script( 'growp_site_javascript', get_theme_file_uri( "assets/js/block-editor.js" ), ['acf-blocks'], true );
		} );
	}

	/**
	 * 管理画面で有効化
	 *
	 * @return void
	 */
	public function print_scripts() {
		add_action( 'admin_print_footer_scripts', function () {
			?>
			<script>
				// wp.domReady(function () {
				// 	$('#editor .editor-writing-flow').addClass('l-post-content');
				// });
			</script>
			<?php
		} );
	}

	public function use_post_type() {
		add_filter( 'use_block_editor_for_post', function ( $user_block_editor, $post ) {
			// 固定ページか、works 投稿タイプの時はブロックエディターをオフにする
			// if (
			//  ( $post->post_type === 'page' )
			//  ||( $post->post_type === 'works' )
			// ) {
			//  $use_block_editor = false;
			// } else {
			//  $use_block_editor = true;
			// }
			return $user_block_editor;
		}, 10, 2 );
	}
}

