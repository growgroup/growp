<?php

/**
 * rss ボタンの設定
 */
class rssButton extends SnsButtonBase {

	// 名称
	public $name = "rss";

	// キー
	public $key = "rss";

	// アカウントページ用のURL
	public $account_url = "{{site_url}}/feed/";

	//シェア用のURL
	public $share_url = "{{site_url}}/feed/";

	public function __construct() {
		parent::init();

		$site_url = array(
			'site_url' => site_url( "" )
		);
		$this->set_account_value( $site_url );
		$this->set_share_value( $site_url );
	}

}
