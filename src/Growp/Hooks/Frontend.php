<?php

namespace Growp\Hooks;

use function add_filter;
use function add_theme_support;
use function array_keys;
use const GROWP_STYLESHEET_URL;
use const GROWP_VERSION;
use function implode;
use function is_author;
use function preg_match_all;
use function remove_action;
use function wp_redirect;

class Frontend extends BaseHookSingleton {

	protected function __construct() {
		add_action( "after_setup_theme", [ $this, 'setup' ] );
		add_action( "init", [ $this, 'cleanup' ] );
		add_action( "template_redirect", [ $this, 'protect_author' ] );
		add_filter( 'tiny_mce_before_init', [ $this, 'mce_options' ] );
		add_filter( 'style_loader_tag', [ $this, 'clean_style_tag' ] );
		add_filter( 'body_class', [ $this, 'body_class' ] );
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
			add_filter( "{$template}_template_hierarchy", [ $this, 'filter_template' ] );
		}
	}

	public function filter_template( $templates ) {
		foreach ( $templates as $key => $template ) {
			$templates[ $key ] = 'views/templates/' . $template;
		}

		return $templates;
	}

	public function body_class( $classes ) {
		global $post;
		// スラッグが設定されている場合出力
		if ( isset( $post->post_name ) && ( strlen( $post->post_name ) == mb_strlen( $post->post_name, 'utf8' ) ) ) {
			$classes[] = $post->post_name;
		}

		return $classes;
	}

	/**
	 * テーマのセットアップ
	 * 基本的な設定などを追加
	 */
	public function setup() {
		$theme_supports = [
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
		add_theme_support('editor-styles');
		add_theme_support( 'align-wide' );
		add_editor_style( "resource/gg-styleguide/dist/assets/css/style.css" );
		add_filter( 'growp_asset_url', function ( $url ) {
			return $url . '?ver=' . GROWP_VERSION;
		} );
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
}
