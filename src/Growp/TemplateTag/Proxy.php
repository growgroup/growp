<?php

use Growp\Template\Component;
use Growp\Template\Foundation;
use Growp\Template\LayoutComponent;
use Growp\Template\ProjectComponent;
use Growp\TemplateTag\Tags;
use Growp\TemplateTag\Url;
use Growp\TemplateTag\Nav;
use YoastSEO_Vendor\GuzzleHttp\Handler\Proxy;

class GUrl extends Url {
}

class GTag extends Tags {
}

class GNav extends Nav {
}

class GTemplate {
	/**
	 * コンポーネントをインクルード
	 *
	 * @param $file
	 * @param array $params
	 */
	public static function get_component( $file, $params = array() ) {
		Component::get( $file, $params );
	}

	public static function get_layout( $file, $params = array() ) {
		LayoutComponent::get( $file, $params );
	}

	public static function get_project( $file, $params = array() ) {
		ProjectComponent::get( $file, $params );
	}

	public static function get_content() {
		ob_start();
		load_template( Foundation::get_instance()->get_template_path(), false );
		$templatedata = ob_get_contents();
		ob_end_clean();

		return $templatedata;
	}
}
