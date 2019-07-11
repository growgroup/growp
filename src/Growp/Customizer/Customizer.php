<?php

namespace Growp\Customizer;

use function esc_attr__;
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
				'label'       => __( 'アクセントカラー', 'growp' ),
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

		} );
	}

}
