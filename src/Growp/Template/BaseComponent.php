<?php

namespace Growp\Template;


abstract class BaseComponent {

	public $component_name = "";

	public $vars = "";

	public $dir = "";

	public $file_path = "";

	public $ignore = false;

	/**
	 * BaseComponent constructor.
	 *
	 * @param $name
	 * @param $vars
	 */
	private function __construct( $name, $vars ) {
		$this->component_name = $name;
		$this->vars           = $vars;
		$this->set_dir();
	}

	/**
	 * 各コンポーネントのディレクトリを指定するメソッドを実装する
	 * @return mixed
	 */
	abstract public function set_dir();

	/**
	 * API
	 *
	 * @param $name
	 * @param $vars
	 */
	public static function get( $name, $vars = [] ) {
		$instance = new static( $name, $vars );
		$instance->render();
	}

	/**
	 * 出力する
	 * @return BaseComponent
	 */
	public function render() {
		$file_path = get_theme_file_path( $this->dir . "/" . $this->component_name . ".php" );

		if ( $file_path && file_exists( $file_path ) ) {
			$vars            = $this->vars;
			$this->file_path = $file_path;
			include $file_path;
			return $this;
		}

		return $this;
	}
}
