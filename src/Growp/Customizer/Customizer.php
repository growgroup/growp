<?php

namespace Growp\Customizer;

use function esc_attr__;
use function file_put_contents;
use function get_theme_file_path;
use Growp\Resource\Resource;
use Growp\TemplateTag\Tags;
use const GROWP_VERSION;
use GUrl;
use Kirki;
use ScssPhp\ScssPhp\Compiler;
use function str_replace;
use Symfony\Component\HttpFoundation\Request;
use function wp_enqueue_style;

class Customizer {

	public static $instance = null;

	public $menu = null;

	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	private function __construct() {
		add_action( "init", [ $this, 'init' ] );
		add_action( "wp_ajax_growp_register_components", [ $this, "register" ] );
		add_action( "wp_ajax_growp_get_html_info", [ $this, "get_html_info" ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_script' ] );
	}


	public function enqueue_script() {
		wp_enqueue_script(
			'growp_theme_customizer',
			get_theme_file_uri( 'assets/js/theme-customizer.js' ),
			[ 'jquery', 'customize-preview' ],
			"",
			true
		);
		wp_enqueue_script(
			'growp_jbox',
			'https://cdn.jsdelivr.net/npm/jbox@1.0.5/dist/jBox.all.min.js',
			[ 'jquery', 'customize-preview' ],
			"",
			true
		);
		wp_enqueue_style(
			"growp_jbox",
			"https://cdn.jsdelivr.net/npm/jbox@1.0.5/dist/jBox.all.css",
			[],
			"",
			"all"
		);
		wp_enqueue_style(
			"growp_themecustomizer",
			get_theme_file_uri( 'assets/css/theme-customizer.css' ),
			[],
			"",
			"all"
		);
		wp_localize_script( "growp_theme_customizer", "GROWP_THEMECUSTOMIZER", [
			"nonce"  => wp_create_nonce( __FILE__ ),
			"action" => "growp_register_components"
		] );
	}

	/**
	 * テーマカスタマイザー設定の登録
	 */
	public function init() {
		$customizer_id = "growp";

		/**
		 * テーマカスタマイザーの設定
		 */
		add_filter( 'kirki/config', function () {
			return get_theme_file_uri( "/vendor/aristath/kirki/" );
		} );


		Kirki::add_config( $customizer_id, array(
			'capability'  => 'edit_theme_options',
			'option_type' => 'theme_mod',
		) );

		/**
		 * ==========================================
		 * 01_基本設定
		 * ==========================================
		 */
		Kirki::add_panel( 'growp_base', array(
			'priority'    => 10,
			'title'       => esc_attr__( '[GG] 基本設定', 'growp' ),
			'description' => esc_attr__( 'Webサイトの基本的な設定', 'growp' ),
		) );

		Kirki::add_section( 'growp_base_logo', array(
			'title'       => esc_attr__( 'ロゴ設定', 'growp' ),
			'description' => esc_attr__( 'ロゴに関する設定を行ってください。', 'growp' ),
			'panel'       => 'growp_base',
			'priority'    => 160,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'image',
			'settings'    => 'growp_base_logo_image_in_header',
			'label'       => esc_attr__( 'ヘッダー : ロゴイメージ', 'growp' ),
			'description' => esc_attr__( 'ヘッダーにロゴとして設定する画像をアップロードしてください。(推奨: 266px × 32px)', 'growp' ),
			'section'     => 'growp_base_logo',
			'default'     => GUrl::asset( "/dist/assets/images/logo.png" ),
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'image',
			'settings'    => 'growp_base_logo_image_in_footer',
			'label'       => esc_attr__( 'フッター : ロゴイメージ', 'growp' ),
			'description' => esc_attr__( 'フッターにロゴとして設定する画像をアップロードしてください。(推奨: 266px × 32px)', 'growp' ),
			'section'     => 'growp_base_logo',
			'default'     => get_theme_file_uri( "/dist/assets/images/logo_white.png" ),
		) );

		/**
		 * 連絡先
		 */
		Kirki::add_section( 'growp_base_contact', array(
			'title'       => esc_attr__( '連絡先', 'growp' ),
			'description' => esc_attr__( '連絡先に関する設定を行ってください。', 'growp' ),
			'panel'       => 'growp_base',
			'priority'    => 160,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'text',
			'settings'    => 'growp_base_tel_number01',
			'label'       => esc_attr__( '電話番号01', 'growp' ),
			'description' => esc_attr__( '電話番号を入力してください', 'growp' ),
			'section'     => 'growp_base_contact',
			'default'     => "123-456-7890",
		) );
		Kirki::add_field( $customizer_id, array(
			'type'        => 'text',
			'settings'    => 'growp_base_tel_number02',
			'label'       => esc_attr__( '電話番号02', 'growp' ),
			'description' => esc_attr__( '電話番号を入力してください', 'growp' ),
			'section'     => 'growp_base_contact',
			'default'     => "123-456-7890",
		) );
		Kirki::add_field( $customizer_id, array(
			'type'        => 'text',
			'settings'    => 'growp_base_fax_number',
			'label'       => esc_attr__( 'FAX番号', 'growp' ),
			'description' => esc_attr__( 'FAX番号を入力してください', 'growp' ),
			'section'     => 'growp_base_contact',
			'default'     => "123-456-7890",
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'textarea',
			'settings'    => 'growp_base_tel_time',
			'label'       => esc_attr__( '受付時間', 'growp' ),
			'description' => esc_attr__( '受付時間を入力してください', 'growp' ),
			'section'     => 'growp_base_contact',
			'default'     => "9:00〜18:00（平日）",
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'text',
			'settings'    => 'growp_base_email',
			'label'       => esc_attr__( 'メールアドレス', 'growp' ),
			'description' => esc_attr__( 'メールアドレスを入力してください', 'growp' ),
			'section'     => 'growp_base_contact',
			'default'     => "info@exampleggg.com",
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'text',
			'settings'    => 'growp_base_contact_url01',
			'label'       => esc_attr__( 'お問い合わせURL01', 'growp' ),
			'description' => esc_attr__( 'お問い合わせページのURLを入力してください', 'growp' ),
			'section'     => 'growp_base_contact',
			'default'     => "/contact/",
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'text',
			'settings'    => 'growp_base_contact_url02',
			'label'       => esc_attr__( 'お問い合わせURL02', 'growp' ),
			'description' => esc_attr__( 'お問い合わせページのURLを入力してください', 'growp' ),
			'section'     => 'growp_base_contact',
			'default'     => "/contact/",
		) );

		/**
		 * コピーライト
		 */
		Kirki::add_section( 'growp_base_copy', array(
			'title'       => esc_attr__( 'コピーライト設定', 'growp' ),
			'description' => esc_attr__( 'フッター下部のコピーライトに関する設定を行ってください。', 'growp' ),
			'panel'       => 'growp_base',
			'priority'    => 160,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'textarea',
			'settings'    => 'growp_base_copyright',
			'label'       => esc_attr__( 'コピーライト', 'growp' ),
			'description' => esc_attr__( 'コピーライトを入力してください', 'growp' ),
			'section'     => 'growp_base_copy',
			'default'     => "Copyright © Sample Co.,Ltd. All Rights Reserved.",
		) );

		/**
		 * タグマネージャー
		 */
		Kirki::add_section( 'growp_base_gtm', array(
			'title'       => esc_attr__( 'タグマネージャー', 'growp' ),
			'description' => esc_attr__( 'タグマネージャーに関する設定を行ってください。', 'growp' ),
			'panel'       => 'growp_base',
			'priority'    => 160,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'code',
			'settings'    => 'growp_base_tagmanager_head',
			'label'       => esc_attr__( 'head タグ内', 'growp' ),
			'description' => esc_attr__( 'タグマネージャーから発行されたscriptタグを入力してください', 'growp' ),
			'section'     => 'growp_base_gtm',
			'default'     => "",
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'code',
			'settings'    => 'growp_base_tagmanager_body_open',
			'label'       => esc_attr__( 'body タグ直下', 'growp' ),
			'description' => esc_attr__( 'タグマネージャーから発行された noscript タグを入力してください', 'growp' ),
			'section'     => 'growp_base_gtm',
			'default'     => "",
		) );
		Kirki::add_panel( 'growp_component', array(
			'priority'    => 11,
			'title'       => esc_attr__( '[GG] コンポーネント設定', 'growp' ),
			'description' => esc_attr__( 'コンポーネント設定に関わる設定', 'growp' ),
		) );
		Kirki::add_section( 'growp_component_info', array(
			'title'       => esc_attr__( 'コンポーネント情報', 'growp' ),
			'description' => esc_attr__( '', 'growp' ),
			'panel'       => 'growp_component',
			'priority'    => 160,
		) );
		$resource = Resource::get_instance();

		Kirki::add_field( $customizer_id, array(
			'type'        => 'custom',
			'settings'    => 'growp_component_info_panel',
			'label'       => __( 'コンポーネント情報', 'growp' ),
			'description' => esc_attr__( '', 'growp' ),
			'section'     => 'growp_component_info',
			'default'     => join( "<br>", [
				'静的HTMLパス : ' . $resource->relative_html_path,
				'レイアウト: ' . count( $resource->html_metadata["components"]["layout"] ),
				'コンポーネント: ' . count( $resource->html_metadata["components"]["component"] ),
				'プロジェクト: ' . count( $resource->html_metadata["components"]["project"] ),
				'<button id="growp_view_sitemap" class="button-secondary">HTML情報を見る</button>'
			] )
		) );
		Kirki::add_field( $customizer_id, array(
			'type'        => 'custom',
			'settings'    => 'growp_component_info_register',
			'label'       => __( 'コンポーネントの自動登録', 'growp' ),
			'description' => esc_attr__( '', 'growp' ),
			'section'     => 'growp_component_info',
			'default'     => join( "<br>", [
				'テーマ内の resource/ ディレクトリ内にあるHTMLファイルから、CSS設計の<a href="https://github.com/hiloki/flocss" target="_blank">FLOCSS</a>命名規則に沿ったコンポーネントをブロックとして登録します。<br>
<br><button id="growp_register_components" class="button-secondary">コンポーネントを登録する  <div class="spinner"></div></button>',
			] )
		) );

		Kirki::add_section( 'growp_component_margin', array(
			'title'       => esc_attr__( 'マージン設定', 'growp' ),
			'description' => esc_attr__( 'コンポーネント間のマージンに関する設定を行ってください。', 'growp' ),
			'panel'       => 'growp_component',
			'priority'    => 160,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'number',
			'settings'    => 'growp_component_margin_xs',
			'label'       => __( '間隔 : 最小', 'growp' ),
			'description' => esc_attr__( '', 'growp' ),
			'section'     => 'growp_component_margin',
			'default'     => '8',
			'choices'     => [
				'min'  => 0,
				'max'  => 1600,
				'step' => 1,
			],
		) );
		Kirki::add_field( $customizer_id, array(
			'type'        => 'number',
			'settings'    => 'growp_component_margin_sm',
			'label'       => __( '間隔 : 小', 'growp' ),
			'description' => esc_attr__( '', 'growp' ),
			'section'     => 'growp_component_margin',
			'default'     => '16',
			'choices'     => [
				'min'  => 0,
				'max'  => 1600,
				'step' => 1,
			],
		) );
		Kirki::add_field( $customizer_id, array(
			'type'        => 'number',
			'settings'    => 'growp_component_margin_md',
			'label'       => __( '間隔 : 中', 'growp' ),
			'description' => esc_attr__( '', 'growp' ),
			'section'     => 'growp_component_margin',
			'default'     => '32',
			'choices'     => [
				'min'  => 0,
				'max'  => 1600,
				'step' => 1,
			],
		) );
		Kirki::add_field( $customizer_id, array(
			'type'        => 'number',
			'settings'    => 'growp_component_margin_lg',
			'label'       => __( '間隔 : 大', 'growp' ),
			'description' => esc_attr__( '', 'growp' ),
			'section'     => 'growp_component_margin',
			'default'     => '72',
			'choices'     => [
				'min'  => 0,
				'max'  => 1600,
				'step' => 1,
			],
		) );
		Kirki::add_field( $customizer_id, array(
			'type'        => 'number',
			'settings'    => 'growp_component_margin_xlg',
			'label'       => __( '間隔 : 最大', 'growp' ),
			'description' => esc_attr__( '', 'growp' ),
			'section'     => 'growp_component_margin',
			'default'     => '100',
			'choices'     => [
				'min'  => 0,
				'max'  => 1600,
				'step' => 1,
			],
		) );

		/**
		 * ==========================================
		 * 02_デザイン設定
		 * ==========================================
		 */
		Kirki::add_panel( 'growp_design', array(
			'priority'    => 11,
			'title'       => esc_attr__( '[GG] デザイン設定', 'growp' ),
			'description' => esc_attr__( 'Webサイトの配色、フォントに関わる設定', 'growp' ),
		) );

		Kirki::add_section( 'growp_design_css', array(
			'title'       => esc_attr__( 'CSS設定', 'growp' ),
			'description' => esc_attr__( 'メインのCSSファイルを選択してください', 'growp' ),
			'panel'       => 'growp_design',
			'priority'    => 160,
		) );

		$resource  = Resource::get_instance();
		$css_files = [];
		foreach ( $resource->css_files as $css_key => $_css_file ) {
			$css_files[ $_css_file ] = $_css_file;
		}
		Kirki::add_field( $customizer_id, array(
			'type'        => 'select',
			'settings'    => 'growp_design_css_file',
			'label'       => __( 'メインCSSファイル', 'growp' ),
			'description' => esc_attr__( 'メインCSSファイルを選択してください。', 'growp' ),
			'section'     => 'growp_design_css',
			'default'     => Resource::get_default_main_css_file_path(),
			'choices'     => $css_files,
		) );


		Kirki::add_section( 'growp_design_container', array(
			'title'       => esc_attr__( 'コンテナ設定', 'growp' ),
			'description' => esc_attr__( 'コンテナに関する設定を行ってください。', 'growp' ),
			'panel'       => 'growp_design',
			'priority'    => 160,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'number',
			'settings'    => 'growp_design_container_width',
			'label'       => __( 'コンテナ幅', 'growp' ),
			'description' => esc_attr__( 'デスクトップ時のコンテナ幅を選択してください。', 'growp' ),
			'section'     => 'growp_design_container',
			'default'     => '1200',
			'choices'     => [
				'min'  => 0,
				'max'  => 1600,
				'step' => 1,
			],
		) );

		Kirki::add_section( 'growp_design_color', array(
			'title'       => esc_attr__( 'カラー設定', 'growp' ),
			'description' => esc_attr__( '配色に関する設定を行ってください。<br>※ CSS設定で指定したCSSファイルを元に、style_rewrite.cssファイルを生成し読み込みます。', 'growp' ),
			'panel'       => 'growp_design',
			'priority'    => 160,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'color',
			'settings'    => 'growp_design_color_primary',
			'label'       => __( 'プライマリーカラー', 'growp' ),
			'description' => esc_attr__( 'プライマリーカラーを選択してください。', 'growp' ),
			'section'     => 'growp_design_color',
			'default'     => '#4B6CCD',
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'color',
			'settings'    => 'growp_design_color_secondary',
			'label'       => __( 'セカンダリーカラー', 'growp' ),
			'description' => esc_attr__( 'セカンダリーカラーを選択してください。', 'growp' ),
			'section'     => 'growp_design_color',
			'default'     => 'rgba(75, 108, 205, 0.1)',
			'choices'     => array(
				'alpha' => true,
			),
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'color',
			'settings'    => 'growp_design_color_accent',
			'label'       => __( 'アクセントカラー', 'growp' ),
			'description' => esc_attr__( 'アクセントカラーを選択してください。', 'growp' ),
			'section'     => 'growp_design_color',
			'default'     => 'rgba(75, 108, 205, 0.1)',
			'choices'     => array(
				'alpha' => true,
			),
		) );
		Kirki::add_field( $customizer_id, array(
			'type'         => 'repeater',
			'settings'     => 'growp_design_color_other',
			'label'        => __( 'その他カラー', 'growp' ),
			'description'  => esc_attr__( 'その他カラーを選択してください。', 'growp' ),
			'section'      => 'growp_design_color',
			'button_label' => "カラーを追加",
			'default'      => [],
			'row_label'    => [
				'type'  => 'text',
				'value' => '配色セット',
			],
			'fields'       => [
				'before' => [
					'type'    => 'color',
					'label'   => '元のカラーコード',
					'default' => 'rgba(255,255,255,1)',
					'choices' => [
						'alpha' => true,
					],
				],
				'after'  => [
					'type'    => 'color',
					'label'   => '置換後のカラーコード',
					'default' => 'rgba(255,255,255,1)',
					'choices' => [
						'alpha' => true,
					],
				],
			]
		) );

		/**
		 * ==========================================
		 * 03_レイアウト
		 * ==========================================
		 */

		/**
		 * 投稿タイプ設定
		 */
		Kirki::add_panel( 'growp_admincustomize', array(
			'priority'    => 18,
			'title'       => esc_attr__( '[GG] 管理画面カスタマイズ', 'growp' ),
			'description' => esc_attr__( '', 'growp' ),
		) );

		Kirki::add_section( 'growp_admincustomize_login_panel', array(
			'title'       => esc_attr__( 'ログイン画面', 'growp' ),
			'description' => esc_attr__( 'ログイン画面の設定を行ってください。', 'growp' ),
			'panel'       => 'growp_admincustomize',
			'priority'    => 160,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'code',
			'settings'    => 'growp_admincustomize_login_panel_css',
			'label'       => __( 'ログイン画面CSS', 'growp' ),
			'description' => esc_attr__( 'ログイン画面のCSSを設定してください', 'growp' ),
			'section'     => 'growp_admincustomize_login_panel',
			'priority'    => 12,
			'default'     => '/**
 * ログイン画面_ロゴのスタイル
 */
#login h1 a {
	background-size: 224px;
	height: 64px;
	width: 234px;
}
',
			'choices'     => [
				'language' => 'css',
			],
		) );

		Kirki::add_section( 'growp_admincustomize_css_js', array(
			'title'       => esc_attr__( '管理画面CSS・JavaScript', 'growp' ),
			'description' => esc_attr__( '管理画面のみで反映するCSS・JavaScriptの設定を行ってください。', 'growp' ),
			'panel'       => 'growp_admincustomize',
			'priority'    => 160,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'code',
			'settings'    => 'growp_admincustomize_css_js_css',
			'label'       => __( '管理画面CSS', 'growp' ),
			'description' => esc_attr__( '管理画面のみで反映するCSSを設定してください', 'growp' ),
			'section'     => 'growp_admincustomize_css_js',
			'priority'    => 12,
			'default'     => '
/** MW WP Form 用 */
#the-list .ui-sortable-placeholder {
	display: none;
}
/** 
 ユーザーの権限ごとに body タグに class を付与
 */
.role-editor .hoge {
}',
			'choices'     => [
				'language' => 'css',
			],
		) );
		Kirki::add_field( $customizer_id, array(
			'type'        => 'code',
			'settings'    => 'growp_admincustomize_css_js_js',
			'label'       => __( '管理画面JavaScript', 'growp' ),
			'description' => esc_attr__( '管理画面のみで反映するJavaScriptを設定してください', 'growp' ),
			'section'     => 'growp_admincustomize_css_js',
			'priority'    => 12,
			'default'     => ';(function($){
	$(function(){
	});
})(jQuery)
				',
			'choices'     => [
				'language' => 'javascript',
			],
		) );

		Kirki::add_section( 'growp_admincustomize_copyright', array(
			'title'       => esc_attr__( '管理画面 フッターテキスト', 'growp' ),
			'description' => esc_attr__( '管理画面のフッターテキスト設定を行ってください。', 'growp' ),
			'panel'       => 'growp_admincustomize',
			'priority'    => 160,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'text',
			'settings'    => 'growp_admincustomize_copyright_right',
			'label'       => __( 'フッターテキスト', 'growp' ),
			'description' => esc_attr__( '右側のフッターテキストを入力してください。', 'growp' ),
			'section'     => 'growp_admincustomize_copyright',
			'priority'    => 12,
			'default'     => 'ご不明な点はGrowGroup株式会社（052-753-6413）までご連絡下さい。',
		) );

		Kirki::add_section( 'growp_admincustomize_admin', array(
			'title'       => esc_attr__( 'クライアント用管理画面UI設定', 'growp' ),
			'description' => esc_attr__( 'クライアント用の管理画面UI設定を行ってください。', 'growp' ),
			'panel'       => 'growp_admincustomize',
			'priority'    => 160,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'checkbox',
			'settings'    => 'growp_admincustomize_admin_menu_menu',
			'label'       => __( '管理メニュー設定', 'growp' ),
			'description' => esc_attr__( '編集者に対して、簡素化した管理メニューを有効にするかどうか設定してください。', 'growp' ),
			'section'     => 'growp_admincustomize_admin',
			'priority'    => 12,
			'default'     => true,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'checkbox',
			'settings'    => 'growp_admincustomize_admin_menu_adminbar',
			'label'       => __( '管理バー設定', 'growp' ),
			'description' => esc_attr__( '編集者に対して、簡素化した管理バーを有効にするかどうか設定してください。', 'growp' ),
			'section'     => 'growp_admincustomize_admin',
			'priority'    => 12,
			'default'     => true,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'checkbox',
			'settings'    => 'growp_admincustomize_admin_menu_dashboard',
			'label'       => __( 'ダッシュボード設定', 'growp' ),
			'description' => esc_attr__( '編集者に対して、簡素化したダッシュボードを有効にするかどうか設定してください。', 'growp' ),
			'section'     => 'growp_admincustomize_admin',
			'priority'    => 12,
			'default'     => true,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'checkbox',
			'settings'    => 'growp_admincustomize_admin_columns',
			'label'       => __( '記事一覧のカラム設定', 'growp' ),
			'description' => esc_attr__( '編集者に対して、記事一覧のカラムを調整します', 'growp' ),
			'section'     => 'growp_admincustomize_admin',
			'priority'    => 12,
			'default'     => true,
		) );

		Kirki::add_field( $customizer_id, array(
			'type'        => 'editor',
			'settings'    => 'growp_admincustomize_admin_menu_dashboard_org',
			'label'       => __( 'オリジナルダッシュボードウィジェット', 'growp' ),
			'description' => esc_attr__( 'オリジナルダッシュボードウィジェットを設定する場合は入力してください。', 'growp' ),
			'section'     => 'growp_admincustomize_admin',
			'priority'    => 12,
			'default'     => "",
			'choices'     => [
				'element' => "textarea",
				'row'     => "10",
			]
		) );

	}

	/**
	 * Ajaxでコンポーネントを登録
	 */
	public function register() {
		$request = Request::createFromGlobals();
		if ( ! wp_verify_nonce( $request->get( "nonce" ), __FILE__ ) ) {
			wp_send_json_error( [ "message" => "不正なアクセス" ] );
			exit;
		}
		$resource = Resource::get_instance();
		$resource->register_components();
		wp_send_json_success( [ "message" => "コンポーネントを登録しました" ] );
		exit;
	}

	public function get_html_info() {
		$request = Request::createFromGlobals();
//		if ( ! wp_verify_nonce( $request->get( "nonce" ), __FILE__ ) ) {
//			wp_send_json_error( [ "message" => "不正なアクセス" ] );
//			exit;
//		}
		$resource = Resource::get_instance();
		wp_send_json_success( $resource );
		exit;
	}


}
