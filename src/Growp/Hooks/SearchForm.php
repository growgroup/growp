<?php

namespace Growp\Hooks;

use Growp\Editor\BlockTwigExtension;
use http\Env;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class SearchForm {

	public $collections = [];
	public $settings = [];
	public $template = "";

	public function __construct( $settings ) {
		$this->settings = $settings;
		$this->create_models();
	}

	public function set_template( $template ) {

		$twig = new Environment( new ArrayLoader( [
			'index.html' => '{% autoescape false %}' . $template . '{% endautoescape %}'
		] ) );
		$twig->addExtension( new BlockTwigExtension() );

		$this->template = $twig->render( "index.html", $this->get_compiled_form() );
	}

	public function render_form(){
		echo $this->template;
	}
	/**
	 * モデルを作成
	 */
	public function create_models() {
		foreach ( $this->settings as $setting ) {
			$model = new SearchModel( $setting );
			$this->add_collection( $model );
		}
	}


	/**
	 * モデルをコレクションに追加する
	 *
	 * @param SearchModel $model
	 */
	public function add_collection( SearchModel $model ) {
		$this->collections[ $model->name ] = $model;
	}

	/**
	 * モデルを取得する
	 *
	 * @param $model_name
	 *
	 * @return bool|mixed
	 */
	public function get_model( $model_name ) {
		if ( isset( $this->collections[ $model_name ] ) && $this->collections[ $model_name ] ) {
			return $this->collections[ $model_name ];
		}

		return false;
	}

	public function get_compiled_form() {
		$variables = [];
		foreach ( $this->collections as $model_name => $model ) {

			$variables[ $model_name ] = $model->html();
		}

		return $variables;
	}

	/**
	 * HTMLを取得
	 *
	 * @param $model_name
	 *
	 * @return mixed
	 */
	public function get_html( $model_name ) {
		$model = $this->get_model( $model_name );

		return $model->html();
	}
}
