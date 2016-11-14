<?php

/**
 * Feedly ボタンの設定
 */
class feedlyButton extends SnsButtonBase {

	// 名称
	public $name = "Feedly";

	// キー
	public $key = "feedly";

	// アカウントページ用のURL
	public $account_url = "http://feedly.com/i/subscription/feed/{{site_url}}/feed";

	//シェア用のURL
	public $share_url = 'http://feedly.com/i/subscription/feed/{{site_url}}/feed';

	public function __construct() {
		parent::init();

		$site_url = array(
			'site_url' => site_url( "" ) . "/feed/"
		);
		$this->set_account_value( $site_url );
		$this->set_share_value( $site_url );
	}

}
