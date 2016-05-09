<?php
/**
 * Hatena Bookmarkボタンの設定
 */

class hatenabookmarkButton extends SnsButtonBase {

	// 名称
	public $name = "Hatena Bookmark";

	// キー
	public $key = "hatenabookmark";

	// アカウントページ用のURL
	public $account_url = "http://b.hatena.ne.jp/entrylist?sort=count&url={{site_url}}/";

	//シェア用のURL
	public $share_url = 'http://b.hatena.ne.jp/add?mode=confirm&url={{url}}&title={{title}}';

	public function __construct() {
		parent::init();
	}

}
