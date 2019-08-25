<?php

namespace Growp\Hooks;

use function array_map;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function get_terms;
use function get_theme_file_path;
use Growp\Menu\Menu;
use Growp\Resource\Resource;
use Growp\Template\Component;
use Growp\TemplateTag\Tags;
use const GROWP_VERSION;
use function str_replace;
use function strpos;

/**
 * Class Frontend
 * テーマのフロントエンドに関連する出力や設定を行う class
 * @package Growp\Hooks
 */
class Frontend {

	protected static $instance = null;

	/**
	 * Frontend constructor.
	 * 初期化
	 */
	private function __construct() {
		add_action( "init", [ $this, 'setup' ] );
		add_action( "init", [ $this, 'cleanup' ] );
		add_action( "template_redirect", [ $this, 'protect_author' ] );
		add_filter( 'style_loader_tag', [ $this, 'clean_style_tag' ] );
		add_filter( 'body_class', [ $this, 'body_class' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'growp_scripts' ], 10 );
		add_shortcode( 'growp_component', [ $this, 'growp_shortcode_get_component' ] );
		$this->change_template_path();
		new Menu( "header_nav", "ヘッダーナビゲーション" );
		new Menu( "footer_nav", "フッターナビゲーション" );
//		$searchFormSetting = [
//			[
//				'name'          => 's_name',
//				'query_type'    => 'tax_query',
//				'input_type'    => 'text',
//				'value'         => '',
//				'default_value' => '',
//				'attrs'         => [
//					'placeholder' => "test",
//					'required'    => true,
//					'class'       => "test",
//					'id'          => "test",
//				],
//			],
//			[
//				'name'          => 's_select',
//				'query_type'    => 'tax_query',
//				'input_type'    => 'select',
//				'value'         => '',
//				'default_value' => '',
//				'choices'       => function () {
//					return array_map( function ( $a ) {
//						return [
//							'value' => $a->term_id,
//							'label' => $a->name,
//						];
//					}, get_terms( [ 'taxonomy' => "category" ] ) );
//				},
//				'attrs'         => [
//					'placeholder' => "test",
//					'required'    => true,
//					'class'       => "test",
//					'id'          => "test",
//				],
//			]
//		];
//
//		$search_form       = new SearchForm( $searchFormSetting );
//		$search_form->set_template( "<form method='post' action='/home/'>{{s_select|raw}} {{s_name|raw}}<button>送信</button></form>" );
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
			'title-tag',
			'yoast-seo-breadcrumbs',
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

		static::rewrite_color_stylesheet_file();
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


					if ( strpos( $template, "views/templates" ) !== false ) {
						$templates[ $key ] = $template;
						continue;
					}
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
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		add_filter( 'wp_head', function () {
			if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
				remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
			}
			global $wp_widget_factory;
			if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
				remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
			}
		} );
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
		$styles   = [];
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

		wp_enqueue_script( "jquery" );

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
			if ( strpos( $file, "jquery.min" ) !== false ) {
				continue;
			}
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
		Component::get( $atts["name"] );
		$content = ob_get_contents();
		ob_clean();

		return $content;
	}

	/**
	 * CSSファイルのカラーコードを書き換える
	 */
	public static function rewrite_color_stylesheet_file() {
		$transient_key    = "css_cache_" . "cssselectors";
		$transient_expire = 60 * 60 * 24;
		$css              = false;
		if ( ! $css ) {
			$resource = Resource::get_instance();
			$css_file = Resource::get_default_main_css_file_path();
			$css_file = Tags::get_option( "growp_design_css_file", $css_file );
//			dump($css_file);
			$css_data        = file_get_contents( get_theme_file_path( $css_file ) );
			$primary_color   = Tags::get_option( "growp_design_color_primary" );
			$secondary_color = Tags::get_option( "growp_design_color_secondary" );
			$accent_color    = Tags::get_option( "growp_design_color_accent" );
			$other_colors    = Tags::get_option( "growp_design_color_other" );
			$css_data        = str_replace( "#65A04D", $primary_color, $css_data );
			$css_data        = str_replace( "#F9F7F0", $secondary_color, $css_data );
			$css_data        = str_replace( "#E04B3A", $accent_color, $css_data );
			foreach ( $other_colors as $color ) {
				$css_data = str_replace( $color["before"], $color["after"], $css_data );
			}
			file_put_contents( get_theme_file_path( $resource->relative_html_path . "/assets/css/style_rewrite.css" ), $css_data );
			foreach ( $resource->css_files as $file_key => $file ) {
				if ( $file === $css_file ) {
					unset( $resource->css_files[ $file_key ] );
				}
			}
			//			$resource->css_files[] = $resource->relative_html_path . "/assets/css/style_rewrite.css";
		}
	}

}
