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
 * @since 1.2.1
 * @return void
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
	if( empty($atts["name"]) ) {
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
	$post_types = get_post_types( array( 'public' => true) );
	remove_meta_box( 'A2A_SHARE_SAVE_meta', $post_types, 'side' );
}

add_action( 'add_meta_boxes', 'growp_remove_share_box', 40 );
