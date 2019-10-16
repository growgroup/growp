<?php

namespace Growp\Template;

/**
 * Class Layout
 * @package Growp\Template
 */
class Foundation {

	protected $template_path = "";

	protected $templates = [];

	protected $name = "";

	protected $base = "";

	public $content = "";

	public $vars = "";

	private static $instance = null;

	protected $template_headers = [
		// テンプレート名を指定
		'template_name'       => "Template Name",
		// foundation テンプレートを変更
		'foundation'          => "Foundation",
		// ページのタイトルを指定
		'title'               => "Title",
		// ページの説明文を指定
		'description'         => "Description",
		// ページヘッダーのタイトルを指定
		'pageheader_title'    => "PageHeaderTitle",
		// ページヘッダーの画像を指定
		'pageheader_image'    => "PageHeaderImage",
		// ページヘッダーのサブタイトルを指定
		'pageheader_subtitle' => "PageHeaderSubtitle",
		// wpautop フィルターを有効にするかどうかを指定
		'wpautop'             => "Formatting",
	];


	/**
	 * Foundation constructor.
	 */
	private function __construct() {
		add_filter( 'template_include', [ $this, 'base' ], 10, 1 );
	}

	/**
	 * シングルトン
	 * @return null
	 */
	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	/**
	 * template_include フックでテンプレートを書き換える
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function base( $template ) {
		$this->template_path = $template;
		$this->base          = substr( basename( $this->template_path ), 0, - 4 );
		if ( 'index' == $this->base ) {
			$this->template_path = false;
		}

		$this->templates = array( 'views/foundation/base.php' );
		if ( $this->template_path ) {
			array_unshift( $this->templates, sprintf( 'views/foundation/%s.php', apply_filters( "growp/theme/wrapper/base", $this->base ) ) );
			if ( file_exists( $this->template_path ) ) {
				$this->content = $this->get_template_content();
				$this->parse_template_header();
			}

			return locate_template( $this->templates );
		} else {
			return locate_template( $this->templates );
		}
	}

	/**
	 * メインテンプレートを読み込み実行結果を返す
	 * @return false|string
	 */
	public function get_template_content() {
		wp_reset_postdata();
		wp_reset_query();
		ob_start();
		include $this->template_path;
		$content = ob_get_contents();
		ob_end_clean();
		wp_reset_postdata();
		wp_reset_query();

		return $content;
	}

	/**
	 * テンプレートのヘッダーを解析する
	 *
	 * @param $templates
	 *
	 * @return mixed
	 */
	public function parse_template_header() {
		$file_headers = get_file_data( $this->template_path, $this->template_headers );

		// テンプレートのパスを変更
		if ( isset( $file_headers["foundation"] ) && $file_headers["foundation"] ) {
			$this->templates = self::change_foundation_path( $this->templates, $file_headers["foundation"] );
		}
		// タイトルタグを変更
		if ( isset( $file_headers["title"] ) && $file_headers["title"] ) {
			self::change_title_tag( $file_headers["title"] );
		}
		if ( isset( $file_headers["description"] ) && $file_headers["description"] ) {
			self::change_description( $file_headers["description"] );
		}
		$this->vars = $file_headers;
	}

	public static function change_title_tag( $new_title ) {
		if ( ! defined( "WPSEO_VERSION" ) ) {
			add_filter( "pre_get_document_title", function ( $title ) use ( $new_title ) {
				return $new_title;
			} );
		} else {
			add_filter( "wpseo_title", function ( $title ) use ( $new_title ) {
				return $new_title;
			} );
		}
	}

	public static function change_description( $new_description ) {
		if ( defined( "WPSEO_VERSION" ) ) {
			add_filter( "wpseo_metadesc", function ( $old_description ) use ( $new_description ) {
				return $new_description;
			} );
		}
	}

	/**
	 * ベーステンプレートのパスを指定
	 *
	 * @param $templates
	 * @param $filename
	 *
	 * @return mixed
	 */
	public static function change_foundation_path( $templates, $filename ) {
		$template_new_path = "views/foundation/" . $filename . ".php";
		if ( file_exists( get_theme_file_path( $template_new_path ) ) ) {
			unset( $templates[ ( count( $templates ) - 1 ) ] );
			array_push( $templates, $template_new_path );
		}

		return $templates;
	}

	public static function get_content() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance->content;
	}

	/**
	 * テンプレート変数を取得する
	 * @return array
	 */
	public static function get_vars() {
		$layoutObject = self::get_instance();

		return $layoutObject->vars;
	}

	/**
	 * テンプレートパスを取得する
	 * @return string
	 */
	public function get_template_path() {
		return $this->template_path;
	}
}
