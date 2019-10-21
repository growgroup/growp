<?php

// テーマの設定
use Growp\Customizer\Customizer;
use Growp\Devtools\Packages\ComponentInfo;
use Growp\Devtools\Packages\DevInfo;
use Growp\Devtools\Packages\HtmlInfo;
use Growp\Devtools\Packages\LinkCheck;
use Growp\Devtools\Packages\MetaInfo;
use Growp\Devtools\Packages\Note;
use Growp\Editor\Acf;
use Growp\Editor\AcfBlock;
use Growp\Editor\BlockEditor;
use Growp\Template\Component;

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
	'default_thumbnail_url'          => GUrl::asset( "/assets/images/img-default.jpg" ),

	// 有効なエディタインスタンス
	'active_editor'                  => [
		Acf::class,
		AcfBlock::class,
		Customizer::class,
		BlockEditor::class,
	],
	// 有効な開発者ツール
	'active_devtools'                => [
		Note::class,
		LinkCheck::class,
		MetaInfo::class,
		DevInfo::class,
		HtmlInfo::class,
		ComponentInfo::class,
	],
];
