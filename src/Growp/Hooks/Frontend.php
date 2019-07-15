<?php

namespace Growp\Hooks;

use function add_shortcode;
use Growp\Resource\Resource;
use Growp\Template\BaseComponent;
use const GROWP_VERSION;
use function ob_clean;
use function ob_get_contents;
use function ob_start;
use function shortcode_atts;

class Frontend {

	protected static $instance = null;

	private function __construct() {
		add_action( "init", [ $this, 'setup' ] );
		add_action( "init", [ $this, 'cleanup' ] );
		add_action( "template_redirect", [ $this, 'protect_author' ] );
		add_filter( 'style_loader_tag', [ $this, 'clean_style_tag' ] );
		add_filter( 'body_class', [ $this, 'body_class' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'growp_scripts' ], 10 );
		add_shortcode( 'growp_component', [ $this, 'growp_shortcode_get_component' ] );
		$this->change_template_path();
	}

	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * テーマのセットアップ
	 * 基本的な設定などを追加
	 */
	public function setup() {
		$theme_supports = [
			'editor-styles',
			'align-wide',
			'automatic-feed-links',
			'post-thumbnails',
			'menus',
			'customize-selective-refresh-widgets',
			'title-tag'
		];


		foreach ( $theme_supports as $support ) {
			add_theme_support( $support );
		}
		// HTML5構造化マークアップで出力
		add_theme_support(
			'html5',
			array(
				'comment-list',
				'search-form',
				'comment-form',
				'gallery',
				'caption',
			)
		);
		add_editor_style( "resource/gg-styleguide/dist/assets/css/style.css" );
	}

	/**
	 * テンプレートへのパスを変更する
	 */
	public function change_template_path() {
		$templates = [
			'index',
			'404',
			'archive',
			'author',
			'category',
			'tag',
			'taxonomy',
			'date',
			'home',
			'frontpage',
			'page',
			'paged',
			'search',
			'single',
			'singular',
			'attachment',
			'embed'
		];
		foreach ( $templates as $template ) {
			add_filter( "{$template}_template_hierarchy", function ( $templates ) {
				foreach ( $templates as $key => $template ) {
					$templates[ $key ] = 'views/templates/' . $template;
				}

				return $templates;
			} );
		}
	}

	/**
	 * body タグへの class 属性を付与
	 *
	 * @param $classes
	 *
	 * @return array
	 */
	public function body_class( $classes ) {
		global $post;
		// スラッグが設定されており、場合出力
		if ( isset( $post->post_name ) && ( strlen( $post->post_name ) == mb_strlen( $post->post_name, 'utf8' ) ) ) {
			$classes[] = "page-class-" . $post->post_name;
		}

		return $classes;
	}

	/**
	 * head タグ内の余分なタグ出力を削除
	 */
	public function cleanup() {
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
		global $wp_widget_factory;
		remove_action( 'wp_head',
			array(
				$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
				'recent_comments_style',
			)
		);
	}

	/**
	 * 著者ページをデフォルトで表示しない
	 */
	public function protect_author() {
		if ( is_author() ) {
			wp_redirect( home_url( '/404/' ) );
			exit;
		}
	}

	/**
	 * link タグに付与されるid属性を削除
	 *
	 * @param $input
	 *
	 * @return string
	 */
	public function clean_style_tag( $input ) {
		preg_match_all( "!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input,
			$matches );
		$media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';

		return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
	}

	/**
	 * CSS、jsの読み込み
	 */
	public function growp_scripts() {

		$resource = Resource::get_instance();


		$styles = [];
		foreach ( $resource->css_files as $key => $file ) {
			$styles[] = [
				'handle' => basename( $file ) . $key,
				'src'    => get_theme_file_uri( $file ),
				'deps'   => [],
				'media'  => 'all',
			];
		}
		$styles[] = [
			'handle' => "overwrite",
			'src'    => get_theme_file_uri( "assets/css/overwrite.css" ),
			'deps'   => [],
			'media'  => 'all',
		];

		foreach ( $styles as $style_key => $style ) {
			$style = wp_parse_args( $style, array(
				'handle' => $style_key,
				"src"    => "",
				'deps'   => array(),
				'media'  => "all",
				'ver'    => GROWP_VERSION,
			) );
			extract( $style );
			wp_enqueue_style( "growp_" . $style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media'] );
		}

		$javascript = [];
		/**
		 * 読み込むJsファイルを定義
		 */
		foreach ( $resource->js_files as $key => $file ) {
			$javascript[] = [
				'handle'    => basename( $file ) . $key,
				'src'       => get_theme_file_uri( $file ),
				'deps'      => array( "jquery" ), // 依存するスクリプトのハンドル名
				'in_footer' => true, // wp_footer に出力
			];
		}

		foreach ( $javascript as $js_key => $js ) {
			$js = wp_parse_args( $js, array(
				'handle'    => $js_key,
				'deps'      => array(),
				'media'     => "all",
				'in_footer' => true,
				'ver'       => GROWP_VERSION,
			) );

			wp_enqueue_script( "growp_" . $js['handle'], $js['src'], $js['deps'], $js['ver'], $js['in_footer'] );
		}

		/**
		 * コメント欄が有効なページでは、
		 * 返信用のjsを登録
		 */
		if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * コンポーネントをショートコードで呼び出し
	 */
	public function growp_shortcode_get_component( $atts ) {
		$atts = shortcode_atts( array(
			'name' => '',
		), $atts, 'growp_component' );
		if ( empty( $atts["name"] ) ) {
			return "";
		}
		ob_start();
		BaseComponent::get( $atts["name"] );
		$content = ob_get_contents();
		ob_clean();

		return $content;
	}


}
