<?php

// テーマの設定
return [

	// nonceで利用するアクション名
	'nonce'                          => __FILE__,

	// 静的ファイルのバージョンの設定 : 例) 1.1.1
	'assets_version'                 => false,

	// テーマカスタマイザーを利用するかどうか
	'use_themecustomizer_stylesheet' => false,

	// 開発用ツールを利用するかどうか
	'use_devtools'                   => true,

	// デフォルトアイキャッチ画像
	'default_thumbnail_url'          => GUrl::asset( "img-default-thumbnail.png" ),


];
