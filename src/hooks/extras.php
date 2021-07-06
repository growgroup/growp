<?php
/**
 * その他必要な設定
 *
 * @package growp
 */

/**
 * body に付与するタグをカスタマイズ
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function growp_body_classes( $classes ) {
	global $post;

	// スラッグが設定されている場合出力
	if ( isset( $post->post_name ) ) {
		$classes[] = $post->post_name;
	}

	return $classes;
}

add_filter( 'body_class', 'growp_body_classes' );


/**
 * 抜粋文をカスタマイズ
 *
 * @return void
 * @since 1.2.1
 */

add_action( 'excerpt_more', 'growp_change_more' );

function growp_change_more( $more ) {
	if ( 0 == get_theme_mod( 'single_char_num', 50 ) ) {
		return "";
	}
	$more = ' &hellip; <span class="c-button is-more">' . __( 'More', 'growp' ) . '</span>';

	return apply_filters( 'growp_readmore', $more );

}

/**
 * 抜粋文の長さをカスタマイズ
 *
 * @param $length
 *
 * @return int
 */
function growp_excerpt_length( $length ) {
	return 80;
}

add_filter( 'excerpt_length', 'growp_excerpt_length', 999 );


/**
 * コンポーネントをショートコードで呼び出し
 */
function growp_shortcode_get_component( $atts ) {
	$atts = shortcode_atts( array(
			'name' => '',
	), $atts, 'growp_component' );
	if ( empty( $atts["name"] ) ) {
		return "";
	}
	ob_start();
	GTemplate::get_component( $atts["name"] );
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_shortcode( 'growp_component', 'growp_shortcode_get_component' );


/**
 * MW WP FORM に日本語の入力チェックのチェックボックスを追加する
 */
if ( class_exists( "MW_WP_Form_Abstract_Validation_Rule" ) ) {

	class JapaneseValidation extends \MW_WP_Form_Abstract_Validation_Rule {
		/**
		 * バリデーションルール名を指定
		 * @var string
		 */
		protected $name = 'japanese';

		/**
		 * バリデーションチェック
		 *
		 * @param string $key name属性
		 * @param array $option
		 *
		 * @return string エラーメッセージ
		 */
		public function rule( $key, array $options = array() ) {
			$value = $this->Data->get( $key );
			if ( ! \MWF_Functions::is_empty( $value ) ) {
				if ( preg_match( "/(й|ц|у|к|е|н|г|ш|щ|з|х|ъ|ф|ы|в|а|п|р|о|л|д|ж|э|я|ч|с|м|и|т|ь|б|ю|П)/", $value, $matches ) ) {
					$defaults = array(
							'message' => "キリル文字は含むことができません。"
					);
					$options  = array_merge( $defaults, $options );

					return $options['message'];
				}
				// １文字以上日本語が含まれているか？
				if ( ! preg_match( "/[一-龠]+|[ぁ-ん]+|[ァ-ヴー]+|[一-龠]+|[ａ-ｚＡ-Ｚ０-９]/u", $value ) ) {

					$defaults = array(
							'message' => "日本語での入力をお願いします"
					);
					$options  = array_merge( $defaults, $options );

					return $options['message'];
				}
			}
		}

		/**
		 * 設定パネルに追加
		 *
		 * @param numeric $key バリデーションルールセットの識別番号
		 * @param array $value バリデーションルールセットの内容
		 */
		public function admin( $key, $value ) {
			?>
			<label><input type="checkbox" <?php checked( $value[ $this->getName() ],
						1 ); ?> name="<?php echo MWF_Config::NAME; ?>[validation][<?php echo $key; ?>][<?php echo esc_attr( $this->getName() ); ?>]" value="1" /><?php esc_html_e( '日本語チェック',
						'mw-wp-form' ); ?></label>
			<?php
		}
	}

	add_filter( "mwform_validation_rules", function ( $validation_rules ) {
		$validation_rules["japanese"] = new JapaneseValidation();

		return $validation_rules;
	} );
}


/**
 * AddToAny シェアボタンのメタボックスを表示しない
 */
function growp_remove_share_box() {
	$post_types = get_post_types( array( 'public' => true ) );
	remove_meta_box( 'A2A_SHARE_SAVE_meta', $post_types, 'side' );
}

add_action( 'add_meta_boxes', 'growp_remove_share_box', 40 );


/**
 * パンくずリストの調整
 * コメントアウトを外すことで、カスタム投稿タイプのアーカイブURLを以下より追加可能
 */
//add_filter( 'wpseo_breadcrumb_links', 'growp_yoast_seo_breadcrumb_append_link' );
//function growp_yoast_seo_breadcrumb_append_link( $links ) {
//
//	$breadcrumb   = array();
//
//	if ( is_post_type_archive( "interview" ) || is_singular("interview") ) {
//		$breadcrumb[] = array(
//				'url'  => site_url( '/recruit/' ),
//				'text' => '採用情報',
//		);
//		array_splice( $links, 1, - 2, $breadcrumb );
//	}
//	if ( is_post_type_archive( "requirement" ) || is_singular("requirement") ) {
//		$breadcrumb[] = array(
//				'url'  => site_url( '/recruit/' ),
//				'text' => '採用情報',
//		);
//		array_splice( $links, 1, - 2, $breadcrumb );
//	}
//
//	return $links;
//}

/**
 * 管理メニューの削除
 *
 * @param $wp_admin_bar
 */
function growp_remove_bar_menus( $wp_admin_bar ) {
	if ( current_user_can( "administrator" ) ) {
		return;
	}
	//WordPressアイコン
	$wp_admin_bar->remove_menu( 'wp-logo' );
	//WordPressアイコン -> WordPress について
	$wp_admin_bar->remove_menu( 'about' );
	//WordPressアイコン -> WordPress.org
	$wp_admin_bar->remove_menu( 'wporg' );
	//WordPressアイコン -> ドキュメンテーション
	$wp_admin_bar->remove_menu( 'documentation' );
	//WordPressアイコン -> サポートフォーラム
	$wp_admin_bar->remove_menu( 'support-forums' );
	//WordPressアイコン -> フィードバック
	$wp_admin_bar->remove_menu( 'feedback' );

	//サイト情報
//	$wp_admin_bar->remove_menu( 'site-name' );
	//サイト情報 -> ダッシュボード
//	$wp_admin_bar->remove_menu( 'dashboard' );
	//サイト情報 -> テーマ
	$wp_admin_bar->remove_menu( 'themes' );
	//サイト情報 -> ウィジェット
	$wp_admin_bar->remove_menu( 'widgets' );
	//サイト情報 -> メニュー
	$wp_admin_bar->remove_menu( 'menus' );
	//サイト情報 -> ヘッダー
	$wp_admin_bar->remove_menu( 'header' );

	//カスタマイズ
	$wp_admin_bar->remove_menu( 'customize' );

	//コメント
	$wp_admin_bar->remove_menu( 'comments' );

	//新規
	$wp_admin_bar->remove_menu( 'new-content' );
	//新規 -> 投稿
	$wp_admin_bar->remove_menu( 'new-post' );
	//新規 -> メディア
	$wp_admin_bar->remove_menu( 'new-media' );
	//新規 -> 固定ページ
	$wp_admin_bar->remove_menu( 'new-page' );
	//新規 -> ユーザー
	$wp_admin_bar->remove_menu( 'new-user' );

	// Duplicate post
	$wp_admin_bar->remove_menu( 'duplicate-post' );
	$wp_admin_bar->remove_menu( 'new-draft' );
	$wp_admin_bar->remove_menu( 'rewrite-republish' );

	// Analytics
	$wp_admin_bar->remove_node( 'gainwp-1' );

	//〜の編集
//	$wp_admin_bar->remove_menu( 'edit' );

	//こんにちは、[ユーザー名]　さん
//	$wp_admin_bar->remove_menu( 'my-account' );
	//ユーザー -> ユーザー名・アイコン
//	$wp_admin_bar->remove_menu( 'user-info' );
	//ユーザー -> プロフィールを編集
//	$wp_admin_bar->remove_menu( 'edit-profile' );
	//ユーザー -> ログアウト
//	$wp_admin_bar->remove_menu( 'logout' );

	//検索
	$wp_admin_bar->remove_menu( 'search' );
}

add_action( 'admin_bar_menu', 'growp_remove_bar_menus', 99999 );




/**
 * ログインしているユーザだけに表示するショートコード
 * [if-login] このメッセージはログインしているユーザだけに表示されます [/if-login]
 *
 * @param $atts
 * @param null $content
 *
 * @return string
 */
function growp_if_login( $atts, $content = null ) {
	if ( is_user_logged_in() ) {
		return '' . do_shortcode( $content ) . '';
	} else {
		return '';
	}
}

add_shortcode( 'if-login', 'growp_if_login' );

/**
 * ログインしていないユーザだけに表示するショートコード
 * [if-login] このメッセージはログインしていないユーザだけに表示されます [/if-login]
 *
 * @param $atts
 * @param null $content
 *
 * @return string
 */
function growp_if_not_login( $atts, $content = null ) {
	if ( is_user_logged_in() ) {
		return '';
	} else {
		return '' . do_shortcode( $content ) . '';
	}
}

add_shortcode( 'if-not-login', 'growp_if_not_login' );

