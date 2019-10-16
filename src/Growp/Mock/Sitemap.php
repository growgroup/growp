<?php

namespace Growp\Mock;

/**
 * サイトマップページの作成
 */
class Sitemap extends BaseMock {

	public function __construct() {
		parent::__construct( "sitemap-mock" );
	}

	protected function run() {
		$this->insert_post( array(
			'post_title'   => "サイトマップ",
			'post_type'    => 'page',
			'post_content' => " ",
			'post_name'    => "sitemap",
			'post_status'  => "publish",
		) );
	}
}
