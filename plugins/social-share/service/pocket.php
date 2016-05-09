<?php
/**
 * Facebookボタンの設定
 */

class pocketButton extends SnsButtonBase {

	// 名称
	public $name = "Pocket";

	// キー
	public $key = "pocket";
	

	// アカウントページ用のURL
	public $account_url = "https://www.facebook.com/{{account}}/";

	//シェア用のURL
	public $share_url = 'https://getpocket.com/edit.php?url={{url}}&title={{title}}';

	public function __construct() {
		parent::init();
	}

}
