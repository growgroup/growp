<?php

namespace Growp\Template;

use Growp\Exception\TemplateErrorException;
use function ob_end_clean;
use function ob_get_contents;
use function var_dump;
use function wp_reset_postdata;
use function wp_reset_query;

/**
 * Class Layout
 * @package Growp\Template
 */
class Foundation {

	protected $template_path = "";

	protected $name = "";

	protected $base = "";

	public $content = "";

	private static $instance = null;

	private function __construct() {
		add_filter( 'template_include', [ $this, 'base' ], 10, 1 );
	}

	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * テンプレートを変更する
	 *
	 * @param $name
	 *
	 * @throws TemplateErrorException
	 */
	public static function use( $name ) {
		$layoutObject = static::get_instance();
		/**
		 * この時点でnameがセットされている場合は例外を投げる
		 */
		if ( $layoutObject->name ) {
			throw new TemplateErrorException( "テンプレートがすでにセットされています", "not_template_change_timing" );
		}
		$layoutObject->name = $name;
	}


	public function base( $template ) {

		$this->template_path = $template;

		$this->base = substr( basename( $this->template_path ), 0, - 4 );
		if ( 'index' == $this->base ) {
			$this->template_path = false;
		}

		$templates = array( 'views/foundation/base.php' );
		if ( $this->template_path ) {
			array_unshift( $templates, sprintf( 'views/foundation/%s.php', apply_filters( "growp/theme/wrapper/base", $this->base ) ) );
			if ( file_exists( $this->template_path ) ) {
				$headers = [
					'template_name'       => "Template Name",
					'layout'              => "Layout",
					'title'               => "Title",
					'description'         => "Description",
					'pageheader_title'    => "PageHeaderTitle",
					'pageheader_image'    => "PageHeaderImage",
					'pageheader_subtitle' => "PageHeaderSubtitle",
					'wpautop'             => "Formatting",
				];
				wp_reset_postdata();
				wp_reset_query();

				ob_start();
				include $this->template_path;
				$this->content = ob_get_contents();
				ob_end_clean();
				wp_reset_postdata();
				wp_reset_query();
				$file_headers = get_file_data( $this->template_path, $headers );
				$this->vars   = $file_headers;
			}

			return locate_template( $templates );
		} else {
			return locate_template( $templates );
		}
	}

	public static function get_content() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance->content;
	}

	/**
	 * テンプレート変数を取得する
	 * @return mixed_
	 */
	public static function get_vars() {
		$layoutObject = static::get_instance();

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
