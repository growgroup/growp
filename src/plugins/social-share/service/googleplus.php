<?php
/**
 * Google Plus ボタンの設定
 */

class googleplusButton extends SnsButtonBase {

	// 名称
	public $name = "Google Plus";

	// キー
	public $key = "googleplus";

	// アカウントページ用のURL
	public $account_url = "https://plus.google.com/{{account}}/posts";

	//シェア用のURL
	public $share_url = 'https://plus.google.com/share?url={{url}}';

	public function __construct() {
		parent::init();
	}

}
