<?php

namespace Growp\Customizer;

use GUrl;
use GTag;
use Kirki;
use Kirki_Helper;

class Customizer {

	public static $instance = null;

	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	private function __construct() {
		add_action( "init", function () {

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
				'default'     => get_theme_file_uri( "/dist/assets/images/logo.png" ),
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
				'settings'    => 'growp_base_tel_number',
				'label'       => esc_attr__( '電話番号', 'growp' ),
				'description' => esc_attr__( '電話番号を入力してください', 'growp' ),
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
				'settings'    => 'growp_base_mailaddress',
				'label'       => esc_attr__( 'メールアドレス', 'growp' ),
				'description' => esc_attr__( 'メールアドレスを入力してください', 'growp' ),
				'section'     => 'growp_base_contact',
				'default'     => "info@exampleggg.com",
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'text',
				'settings'    => 'growp_base_contact_url',
				'label'       => esc_attr__( 'お問い合わせURL', 'growp' ),
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
			 * ==========================================
			 * 02_デザイン設定
			 * ==========================================
			 */

			Kirki::add_panel( 'growp_design', array(
				'priority'    => 11,
				'title'       => esc_attr__( '[GG] デザイン設定', 'growp' ),
				'description' => esc_attr__( 'Webサイトの配色、フォントに関わる設定', 'growp' ),
			) );

			Kirki::add_section( 'growp_design_font', array(
				'title'       => esc_attr__( 'フォント設定', 'growp' ),
				'description' => esc_attr__( 'フォントに関する設定を行ってください。', 'growp' ),
				'panel'       => 'growp_design',
				'priority'    => 160,
			) );

			Kirki::add_field( $customizer_id, array(
				'type'     => 'typography',
				'settings' => 'growp_design_font',
				'label'    => esc_attr__( 'ベースフォント', 'growp' ),
				'section'  => 'growp_design_font',
				'default'  => array(
					'font-family'    => 'Noto Sans JP',
					'variant'        => 'regular',
					'font-size'      => '16px',
					'line-height'    => '1.7',
					'letter-spacing' => '0',
					'color'          => '#333333',
					'text-transform' => 'none',
					'text-align'     => 'left',
				),
				'priority' => 10,
				'output'   => array(
					array(
						'element' => 'html',
					),
				),
			) );

			Kirki::add_section( 'growp_design_color', array(
				'title'       => esc_attr__( 'カラー設定', 'growp' ),
				'description' => esc_attr__( '配色に関する設定を行ってください。', 'growp' ),
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
				'label'       => __( 'セカンダリーカラー', 'growp' ),
				'description' => esc_attr__( 'アクセントカラーを選択してください。', 'growp' ),
				'section'     => 'growp_design_color',
				'default'     => 'rgba(75, 108, 205, 0.1)',
				'choices'     => array(
					'alpha' => true,
				),
			) );

			/**
			 * ==========================================
			 * 03_レイアウト
			 * ==========================================
			 */

			Kirki::add_panel( 'growp_layout', array(
				'priority'    => 11,
				'title'       => esc_attr__( '[GG] レイアウト設定', 'growp' ),
				'description' => esc_attr__( 'ヘッダー、フッター、オファーに関する設定', 'growp' ),
			) );

			/**
			 * 03_01_ヘッダー設定
			 */
			Kirki::add_section( 'growp_layout_header', array(
				'title'       => esc_attr__( 'ヘッダー設定', 'growp' ),
				'description' => esc_attr__( 'ヘッダーに関する設定を行ってください。', 'growp' ),
				'panel'       => 'growp_layout',
				'priority'    => 160,
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'select',
				'settings'    => 'growp_layout_header_type',
				'label'       => __( 'ヘッダーレイアウト', 'growp' ),
				'description' => esc_attr__( 'ヘッダーのレイアウトを選択してください。', 'growp' ),
				'section'     => 'growp_layout_header',
				'default'     => 'simple',
				'priority'    => 10,
				'multiple'    => 1,
				'choices'     => array(
					'simple' => esc_attr__( 'シンプル', 'growp' ),
					'normal' => esc_attr__( '通常', 'growp' ),
					'full'   => esc_attr__( 'フル', 'growp' ),
				),
			) );

			add_filter( "growp/layout/header", function () {
				return "header-" . GTag::get_option( "growp_layout_header_type" );
			} );

			/**
			 * 03_02_フッター設定
			 */
			Kirki::add_section( 'growp_layout_footer', array(
				'title'       => esc_attr__( 'フッター設定', 'growp' ),
				'description' => esc_attr__( 'フッターに関する設定を行ってください。', 'growp' ),
				'panel'       => 'growp_layout',
				'priority'    => 160,
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'select',
				'settings'    => 'growp_layout_footer_type',
				'label'       => __( 'フッターレイアウト', 'growp' ),
				'description' => esc_attr__( 'フッターのレイアウトを選択してください。', 'growp' ),
				'section'     => 'growp_layout_footer',
				'default'     => 'normal',
				'priority'    => 10,
				'multiple'    => 1,
				'choices'     => array(
					'simple' => esc_attr__( 'シンプル', 'growp' ),
					'normal' => esc_attr__( '通常', 'growp' ),
				),
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'background',
				'settings'    => 'growp_layout_footer_background',
				'label'       => __( '背景設定', 'growp' ),
				'description' => esc_attr__( 'フッターの背景を設定してください。', 'growp' ),
				'section'     => 'growp_layout_footer',
				'default'     => [
					'background-color' => "#333"
				],
				'priority'    => 10,
				'output'      => [
					[
						'element' => '.c-footer-menu',
					],
					[
						'element' => '.l-footer',
					],
				]
			) );

			add_filter( "growp/layout/footer", function () {
				return "footer-" . GTag::get_option( "growp_layout_footer_type" );
			} );

			Kirki::add_section( 'growp_layout_pageheader', array(
				'title'       => esc_attr__( 'ページヘッダー設定', 'growp' ),
				'description' => esc_attr__( 'ページヘッダーに関する設定を行ってください。', 'growp' ),
				'panel'       => 'growp_layout',
				'priority'    => 160,
			) );
			Kirki::add_field( $customizer_id, array(
				'type'     => 'typography',
				'settings' => 'growp_layout_pageheader_font',
				'label'    => esc_attr__( 'ベースフォント', 'growp' ),
				'section'  => 'growp_layout_pageheader',
				'default'  => array(
					'font-family'    => 'Noto Sans JP',
					'variant'        => '700',
					'font-size'      => '32px',
					'line-height'    => '1.7',
					'letter-spacing' => '0',
					'color'          => '#FFF',
					'text-transform' => 'none',
					'text-align'     => 'center',
				),
				'priority' => 10,
				'output'   => [
					[
						'element' => '.c-page-header__inner',
					],
					[
						'element' => '.c-page-header__title'
					]
				],
			) );
			Kirki::add_field( $customizer_id, array(
				'type'        => 'background',
				'settings'    => 'growp_layout_pageheader_background',
				'label'       => __( '背景画像', 'growp' ),
				'description' => esc_attr__( '背景を設定してください。', 'growp' ),
				'section'     => 'growp_layout_pageheader',
				'default'     => [
					'background-image' => \GUrl::asset( '/assets/images/pagehead-default.jpg' )
				],
				'priority'    => 10,
				'output'      => [
					[
						'element' => '.c-page-header'
					]
				]
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'checkbox',
				'settings'    => 'growp_layout_pageheader_text_invert',
				'label'       => __( 'タイトルとサブタイトルを逆転', 'growp' ),
				'description' => esc_attr__( 'タイトルとサブタイトルを逆転する場合はチェックをいれてください。。', 'growp' ),
				'section'     => 'growp_layout_pageheader',
				'default'     => false,
				'priority'    => 10,
			) );

			/**
			 * 03_03_オファー設定
			 */
			Kirki::add_section( 'growp_layout_offer', array(
				'title'       => esc_attr__( 'オファー設定', 'growp' ),
				'description' => esc_attr__( 'オファーブロックに関する設定を行ってください。', 'growp' ),
				'panel'       => 'growp_layout',
				'priority'    => 160,
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'select',
				'settings'    => 'growp_layout_offer_show',
				'label'       => __( '表示設定', 'growp' ),
				'description' => esc_attr__( 'オファーブロックを表示するかどうか選択してください。', 'growp' ),
				'section'     => 'growp_layout_offer',
				'default'     => 'show',
				'priority'    => 10,
				'multiple'    => 1,
				'choices'     => array(
					'show' => esc_attr__( '表示', 'growp' ),
					'hide' => esc_attr__( '非表示', 'growp' ),
				),
			) );
			Kirki::add_field( $customizer_id, array(
				'type'        => 'select',
				'settings'    => 'growp_layout_offer_type',
				'label'       => __( 'デザイン設定', 'growp' ),
				'description' => esc_attr__( 'オファーブロックのデザインを選択してください。', 'growp' ),
				'section'     => 'growp_layout_offer',
				'default'     => 'normal',
				'priority'    => 10,
				'multiple'    => 1,
				'choices'     => array(
					'normal' => esc_attr__( '通常', 'growp' ),
					'simple' => esc_attr__( 'シンプル', 'growp' ),
					'detail' => esc_attr__( 'テキストあり', 'growp' ),
				),
			) );
			add_filter( "growp/layout/offer", function () {
				return "offer-" . GTag::get_option( "growp_layout_offer_type" );
			} );
			Kirki::add_field( $customizer_id, array(
				'type'        => 'editor',
				'settings'    => 'growp_layout_offer_text',
				'label'       => __( 'タイトル', 'growp' ),
				'description' => esc_attr__( 'オファーブロックのタイトルを入力してください。', 'growp' ),
				'section'     => 'growp_layout_offer',
				'default'     => '株式会社サンプルへの<br class="u-hidden-lg">お見積り/お問い合わせはこちら',
				'priority'    => 10,
			) );
			Kirki::add_field( $customizer_id, array(
				'type'        => 'editor',
				'settings'    => 'growp_layout_offer_subtitle',
				'label'       => __( 'サブタイトル', 'growp' ),
				'description' => esc_attr__( 'オファーブロックのサブタイトルを入力してください。', 'growp' ),
				'section'     => 'growp_layout_offer',
				'default'     => 'CONTACT',
				'priority'    => 10,
			) );
			Kirki::add_field( $customizer_id, array(
				'type'        => 'textarea',
				'settings'    => 'growp_layout_offer_detail',
				'label'       => __( 'テキスト', 'growp' ),
				'description' => esc_attr__( 'オファーブロックのテキストを入力してください。', 'growp' ),
				'section'     => 'growp_layout_offer',
				'default'     => '株式会社サンプルでは、それぞれの仕事の幅が広く、活躍できるフィールドは無限大です。<br>
				  新卒採用・インターンシップは下記の電話番号、お問い合わせフォームよりご連絡下さい。',
				'priority'    => 10,
			) );
			Kirki::add_field( $customizer_id, array(
				'type'        => 'image',
				'settings'    => 'growp_layout_offer_image',
				'label'       => __( '背景画像', 'growp' ),
				'description' => esc_attr__( 'オファーブロックの背景画像をアップロードしてください。', 'growp' ),
				'section'     => 'growp_layout_offer',
				'default'     => GUrl::asset( '/assets/images/bg-offer.jpg' ),
				'priority'    => 10,
			) );

			/**
			 * ==========================================
			 * 04_トップページ設定
			 * ==========================================
			 */
			Kirki::add_panel( 'growp_frontpage', array(
				'priority'    => 11,
				'title'       => esc_attr__( '[GG] トップページ設定', 'growp' ),
				'description' => esc_attr__( 'トップページに関する設定が可能です。', 'growp' ),
			) );


			/**
			 * 04_01_メインビジュアルの種類
			 */
			Kirki::add_section( 'growp_frontpage_mainvisual_type', array(
				'title'       => esc_attr__( 'メインビジュアル[タイプ]', 'growp' ),
				'description' => esc_attr__( 'トップページのメインビジュアルに関する設定を行ってください。', 'growp' ),
				'panel'       => 'growp_frontpage',
				'priority'    => 160,
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'select',
				'settings'    => 'growp_frontpage_mainvisual_type',
				'label'       => __( 'メインビジュアル[タイプ]', 'growp' ),
				'description' => esc_attr__( 'メインビジュアルのタイプを選択してください。', 'growp' ),
				'section'     => 'growp_frontpage_mainvisual_type',
				'default'     => 'nomral',
				'priority'    => 10,
				'multiple'    => 1,
				'choices'     => array(
					'normal' => esc_attr__( '通常', 'growp' ),
					'slider' => esc_attr__( 'スライダー', 'growp' ),
				),
			) );
			/**
			 * 04_02_メインビジュアル通常パターン
			 */
			Kirki::add_section( 'growp_frontpage_mainvisual_normal', array(
				'title'       => esc_attr__( 'メインビジュアル[通常]', 'growp' ),
				'description' => esc_attr__( 'メインビジュアル[通常]に関する設定を行ってください。', 'growp' ),
				'panel'       => 'growp_frontpage',
				'priority'    => 160,
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'image',
				'settings'    => 'growp_frontpage_mainvisual_normal_image',
				'label'       => __( 'メインビジュアル[通常] : 背景画像', 'growp' ),
				'description' => esc_attr__( 'メインビジュアルの背景イメージをアップロードしてください。', 'growp' ),
				'section'     => 'growp_frontpage_mainvisual_normal',
				'default'     => GUrl::asset( '/assets/images/main-visual.jpg' ),
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'textarea',
				'settings'    => 'growp_frontpage_mainvisual_normal_text',
				'label'       => __( 'メインビジュアル[通常] : テキスト', 'growp' ),
				'description' => esc_attr__( 'メインビジュアルのテキストを設定してください。', 'growp' ),
				'section'     => 'growp_frontpage_mainvisual_normal',
				'default'     => "現場のスペシャリスト集団。<br>それがサンプルです。",
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'text',
				'settings'    => 'growp_frontpage_mainvisual_normal_button_url',
				'label'       => __( 'メインビジュアル[通常] : ボタンURL', 'growp' ),
				'description' => esc_attr__( 'メインビジュアル内ボタンのURLを設定してください。', 'growp' ),
				'section'     => 'growp_frontpage_mainvisual_normal',
				'default'     => "/about/",
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'text',
				'settings'    => 'growp_frontpage_mainvisual_normal_button_text',
				'label'       => __( 'メインビジュアル[通常] : ボタンテキスト', 'growp' ),
				'description' => esc_attr__( 'メインビジュアル内ボタンのテキストを設定してください。', 'growp' ),
				'section'     => 'growp_frontpage_mainvisual_normal',
				'default'     => "私たちについて",
			) );

			/**
			 * 04_03_メインビジュアルスライダーパターン
			 */
			Kirki::add_section( 'growp_frontpage_mainvisual_slider', array(
				'title'       => esc_attr__( 'メインビジュアル[スライダー]', 'growp' ),
				'description' => esc_attr__( 'メインビジュアル[スライダー]に関する設定を行ってください。', 'growp' ),
				'panel'       => 'growp_frontpage',
				'priority'    => 160,
			) );

			Kirki::add_field( $customizer_id, array(
				'type'         => 'repeater',
				'settings'     => 'growp_frontpage_mainvisual_slider',
				'label'        => esc_attr__( 'メインビジュアル[スライダー]', 'growp' ),
				'section'      => 'growp_frontpage_mainvisual_slider',
				'priority'     => 10,
				'row_label'    => array(
					'type'  => 'text',
					'value' => esc_attr__( 'スライド ', 'growp' ),
					'field' => 'image',
				),
				'button_label' => esc_attr__( 'スライドを追加', 'growp' ),
				'default'      => array(),
				'fields'       => array(
					'image'    => array(
						'type'        => 'image',
						'label'       => esc_attr__( '背景画像', 'growp' ),
						'description' => esc_attr__( '画像をアップロードしてください', 'growp' ),
						'default'     => '',
					),
					'link_url' => array(
						'type'        => 'text',
						'label'       => esc_attr__( 'URL', 'growp' ),
						'description' => esc_attr__( 'URLを入力してください', 'growp' ),
						'default'     => '',
					),
				)
			) );
			/**
			 * 04_04_ブランドコンテンツ
			 */
			Kirki::add_section( 'growp_frontpage_brand', array(
				'title'       => esc_attr__( 'ブランドコンテンツ設定', 'growp' ),
				'description' => esc_attr__( 'トップページのスライダー下部に表示する、[ブランドコンテンツ]の設定を行ってください。', 'growp' ),
				'panel'       => 'growp_frontpage',
				'priority'    => 160,
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'select',
				'settings'    => 'growp_layout_brand_show',
				'label'       => __( '表示設定', 'growp' ),
				'description' => esc_attr__( 'ブランドコンテンツを表示するかどうか選択してください。', 'growp' ),
				'section'     => 'growp_frontpage_brand',
				'default'     => 'show',
				'priority'    => 10,
				'multiple'    => 1,
				'choices'     => array(
					'show' => esc_attr__( '表示', 'growp' ),
					'hide' => esc_attr__( '非表示', 'growp' ),
				),
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'editor',
				'settings'    => 'growp_frontpage_brand_title',
				'label'       => __( 'タイトル', 'growp' ),
				'description' => esc_attr__( 'ブランドコンテンツのタイトルを入力してください。', 'growp' ),
				'section'     => 'growp_frontpage_brand',
				'default'     => '現場を補強する、力になれます。<br>教育から開発までトータルに。',
				'priority'    => 10,
			) );
			Kirki::add_field( $customizer_id, array(
				'type'        => 'editor',
				'settings'    => 'growp_frontpage_brand_text',
				'label'       => __( '本文', 'growp' ),
				'description' => esc_attr__( 'ブランドコンテンツの本文を入力してください。', 'growp' ),
				'section'     => 'growp_frontpage_brand',
				'default'     => 'SE各自の開発力・技術力だけに頼ることなく、1ワークに複数のSEを投入するグループワーク制を採用しています。<br class="u-hidden-sm">一人ひとりがSEとして研鑽を積んできた経験と技術に加えて、<br class="u-hidden-sm">集団で発送し、集団で開発し管理進行を行い、高い品質と確実な納期をお約束しています。',
				'priority'    => 10,
			) );
			Kirki::add_field( $customizer_id, array(
				'type'        => 'text',
				'settings'    => 'growp_frontpage_brand_button_text',
				'label'       => __( 'ボタン:テキスト', 'growp' ),
				'description' => esc_attr__( 'ボタンのテキストを設定してください。', 'growp' ),
				'section'     => 'growp_frontpage_brand',
				'default'     => '',
				'priority'    => 10,
			) );
			Kirki::add_field( $customizer_id, array(
				'type'        => 'text',
				'settings'    => 'growp_frontpage_brand_button_url',
				'label'       => __( 'ボタン:URL', 'growp' ),
				'description' => esc_attr__( 'ボタンのurlを設定してください。', 'growp' ),
				'section'     => 'growp_frontpage_brand',
				'default'     => '',
				'priority'    => 10,
			) );
			Kirki::add_field( $customizer_id, array(
				'type'        => 'background',
				'settings'    => 'growp_frontpage_brand_image',
				'label'       => __( '背景画像', 'growp' ),
				'description' => esc_attr__( 'ブランドコンテンツの背景画像をアップロードしてください。', 'growp' ),
				'section'     => 'growp_frontpage_brand',
				'default'     => [
					'background-image' => GUrl::asset( '/assets/images/bg-opening.jpg' )
				],
				'priority'    => 10,
				'output'      => [
					[
						'element' => '.c-opening',
					]
				]
			) );
			/**
			 * 04_05_カードコンテンツ
			 */
			Kirki::add_section( 'growp_frontpage_card', array(
				'title'       => esc_attr__( 'カードメニュー設定', 'growp' ),
				'description' => esc_attr__( 'トップページに表示する[カードメニュー]の設定を行ってください。', 'growp' ),
				'panel'       => 'growp_frontpage',
				'priority'    => 160,
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'select',
				'settings'    => 'growp_frontpage_card_show',
				'label'       => __( '表示設定', 'growp' ),
				'description' => esc_attr__( 'カードコンテンツを表示するかどうか選択してください。', 'growp' ),
				'section'     => 'growp_frontpage_card',
				'default'     => 'show',
				'priority'    => 10,
				'multiple'    => 1,
				'choices'     => array(
					'show' => esc_attr__( '表示', 'growp' ),
					'hide' => esc_attr__( '非表示', 'growp' ),
				),
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'text',
				'settings'    => 'growp_frontpage_card_title',
				'label'       => __( 'タイトル', 'growp' ),
				'description' => esc_attr__( 'タイトルを入力してください。', 'growp' ),
				'section'     => 'growp_frontpage_card',
				'default'     => '企業情報',
				'priority'    => 10,
			) );
			Kirki::add_field( $customizer_id, array(
				'type'        => 'text',
				'settings'    => 'growp_frontpage_card_subtitle',
				'label'       => __( 'サブタイトル', 'growp' ),
				'description' => esc_attr__( 'サブタイトルを入力してください。', 'growp' ),
				'section'     => 'growp_frontpage_card',
				'default'     => 'COMPANY',
				'priority'    => 10,
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'select',
				'settings'    => 'growp_frontpage_card_menu',
				'label'       => __( 'ナビゲーション設定', 'growp' ),
				'description' => esc_attr__( '表示するメニューを選択してください。※画像は各ページに設定されたアイキャッチ画像が表示されます。', 'growp' ),
				'section'     => 'growp_frontpage_card',
				'default'     => '',
				'choices'     => Kirki_Helper::get_terms( [ 'taxonomy' => 'nav_menu' ] ),
				'priority'    => 10,
			) );

			/**
			 * 04_06_お知らせ
			 */
			Kirki::add_section( 'growp_frontpage_news', array(
				'title'       => esc_attr__( 'ニュースブロック設定', 'growp' ),
				'description' => esc_attr__( 'トップページに表示する[ニュースブロック]の設定を行ってください。', 'growp' ),
				'panel'       => 'growp_frontpage',
				'priority'    => 160,
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'select',
				'settings'    => 'growp_frontpage_news_type',
				'label'       => __( '表示タイプ設定', 'growp' ),
				'description' => esc_attr__( 'ニュースブロックの表示タイプを選択してください。', 'growp' ),
				'section'     => 'growp_frontpage_news',
				'default'     => 'normal',
				'priority'    => 12,
				'choices'     => array(
					'normal'    => esc_attr__( '通常', 'growp' ),
					'twocolumn' => esc_attr__( '2カラム', 'growp' ),
					'simple'    => esc_attr__( 'シンプル', 'growp' ),
				),
			) );

			add_filter( "growp/frontpage/news", function () {
				return "front-news-" . GTag::get_option( "growp_frontpage_news_type", "normal" );
			} );

			/**
			 * 投稿タイプ設定
			 */
			Kirki::add_panel( 'growp_posttypes', array(
				'priority'    => 20,
				'title'       => esc_attr__( '[GG] 投稿タイプ', 'growp' ),
				'description' => esc_attr__( 'Webサイトのお客様の声、導入事例などの投稿タイプに関する設定', 'growp' ),
			) );

			Kirki::add_section( 'growp_posttypes_switch', array(
				'title'       => esc_attr__( '利用設定', 'growp' ),
				'description' => esc_attr__( '利用する投稿タイプの設定を行ってください。', 'growp' ),
				'panel'       => 'growp_posttypes',
				'priority'    => 160,
			) );

			Kirki::add_field( $customizer_id, array(
				'type'        => 'multicheck',
				'settings'    => 'growp_posttypes_swtich',
				'label'       => __( '表示設定', 'growp' ),
				'description' => esc_attr__( '利用する投稿タイプを選択してください。', 'growp' ),
				'section'     => 'growp_posttypes_switch',
				'default'     => [
					'blog',
					'media',
					'cases',
				],
				'priority'    => 12,
				'choices'     => growp_get_post_types( true ),
			) );
			$post_types  = growp_get_post_types( true );
			$_post_types = [];

			$pageheader_default = GTag::get_option( "growp_layout_pageheader_background" );
			foreach ( $post_types as $post_type_key => $post_type ) {
				$_post_types[] = [
					'post_type'        => $post_type_key,
					'label'            => $post_type,
					'subtitle'         => mb_strtoupper( $post_type_key ),
					'background-image' => $pageheader_default["background-image"],
				];
			}
			Kirki::add_field( $customizer_id, array(
				'type'         => 'repeater',
				'settings'     => 'growp_posttypes_asset',
				'label'        => esc_attr__( '投稿タイプ設定', 'growp' ),
				'section'      => 'growp_posttypes_switch',
				'priority'     => 12,
				'row_label'    => array(
					'type'  => 'field',
					'value' => esc_attr__( '', 'growp' ),
					'field' => 'label',
				),
				'button_label' => false,
				'default'      => $_post_types,
				'fields'       => array(
					'post_type'        => array(
						'type'        => 'text',
						'label'       => esc_attr__( '投稿タイプ', 'growp' ),
						'description' => esc_attr__( '投稿タイプを設定してください', 'growp' ),
						'default'     => '',
					),
					'label'            => array(
						'type'        => 'text',
						'label'       => esc_attr__( 'ラベル', 'growp' ),
						'description' => esc_attr__( 'ラベルを設定してください', 'growp' ),
						'default'     => '',
					),
					'subtitle'         => array(
						'type'        => 'text',
						'label'       => esc_attr__( 'サブタイトル(英字)', 'growp' ),
						'description' => esc_attr__( '英字のサブタイトルを設定してください', 'growp' ),
						'default'     => '',
					),
					'background-image' => array(
						'type'        => 'image',
						'label'       => esc_attr__( '背景画像', 'growp' ),
						'description' => esc_attr__( '各投稿タイプのページヘッダー背景画像について設定をしてください', 'growp' ),
						'default'     => '',
					),
				)
			) );
		} );
	}

}
