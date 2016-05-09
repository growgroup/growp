<?php
/**
 * Twitterボタンの設定
 */
class twitterButton extends SnsButtonBase {

	// 名称
	public $name = "ツイッター";

	// キー
	public $key = "twitter";

	// アカウントページ用のURL
	public $account_url = "http://twitter.com/{{account}}/";

	//シェア用のURL
	public $share_url = 'https://twitter.com/intent/tweet?url={{url}}&text={{title}}&original_referer={{reffer}}';

	public function __construct() {
		parent::init();
	}

}
