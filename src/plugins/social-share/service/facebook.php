<?php
/**
 * Facebookボタンの設定
 */

class facebookButton extends SnsButtonBase {

	// 名称
	public $name = "Facebook";

	// キー
	public $key = "facebook";
	
	// アカウントページ用のURL
	public $account_url = "https://www.facebook.com/{{account}}/";

	//シェア用のURL
	public $share_url = 'https://www.facebook.com/sharer/sharer.php?u={{url}}&t={{title}}';

	public function __construct() {
		parent::init();
	}

}
