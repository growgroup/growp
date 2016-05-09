<?php

/**
 * Class GarumaxSnsButton
 * ソーシャルボタンのファクトリー
 */
class SnsButtonBase {

	// 名称
	public $name = "";

	// アカウントのバリュー
	public $account_value = "";

	// シェアボタンのパラメータ
	public $share_value = array();

	// キー
	public $key = "";

	// アカウントページ用のURL
	public $account_url = "";

	//シェア用のURL
	public $share_url = "";

	// 結果URL
	public $account_results_url = "";

	// 結果URL
	public $share_results_url = "";

	// 画像のURL
	public $account_image = "";

	// 画像のURL
	public $share_image = "";


	/**
	 * 初期化する
	 */
	protected function init() {
		$this->set_image_url();
		$this->set_url();
	}

	/**
	 * ゲッター,セッターの準備
	 *
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed
	 */
	public function __call( $name, $arguments ) {
		if ( substr( $name, 0, 4 ) == 'get_' ) {
			$model_props = $this->properties();

			$property = substr( $name, 4 );
			if ( array_key_exists( $property, $model_props ) ) {
				return $this->{$property};
			}
		}
		if ( substr( $name, 0, 4 ) == 'set_' ) {
			$model_props = $this->properties();

			$property = substr( $name, 4 );
			if ( array_key_exists( $property, $model_props ) ) {
				$this->{$property} = $arguments[0];
			}
		}
	}

	/**
	 * プロパティを取得
	 * @return array
	 */
	public function properties() {
		return get_object_vars( $this );
	}

	/**
	 * 画像のURLをセットする
	 */
	public function set_image_url() {
		$account_image_url   = GROWP_SOCIAL_BUTTON_URL . '/icon-header-%1$s.png';
		$share_image_url     = GROWP_SOCIAL_BUTTON_URL . '/icon-footer-%1$s.svg';
		$this->account_image = sprintf( $account_image_url, $this->key );
		$this->share_image   = sprintf( $share_image_url, $this->key );
	}

	/**
	 * URL置換
	 *
	 * @param $url
	 * @param array $data 置換するデータ
	 *
	 * @return mixed
	 */
	static public function replace_url( $url, $data = array() ) {
		return preg_replace_callback( '/\{{(\w+)}}/', function ( $m ) use ( $data ) {

			if ( ! isset( $data[ $m[1] ] ) ) {
				return '';
			}

			return $data[ $m[1] ];
		}, $url );
	}

	/**
	 * URLをセットする
	 */
	public function set_url() {


		// アカウントタイプの場合
		if ( ! $this->account_value ) {
			$account_value = get_option( 'sns_options' );
			if ( isset( $account_value[ $this->key ] ) ) {
				$this->account_value["account"] = $account_value[ $this->key ];
			}
			$this->account_value["site_url"] = site_url( "" );
			$this->account_value["feed_url"] = get_bloginfo( 'rss2url' );
		}

		$this->account_results_url = static::replace_url( $this->account_url, $this->account_value );


		// シェアタイプの場合
		if ( ! $this->share_value ) {
			$post_id           = get_the_ID();
			$text              = $this->get_share_text( $post_id );
			$this->share_value = array(
				'url'   => get_the_permalink( $post_id ),
				'text'  => $text,
				'title' => get_the_title( $post_id )
			);
		}
		$this->share_results_url = static::replace_url( $this->share_url, $this->share_value );
	}

	/**
	 * ボタンを取得する
	 *
	 * @param $type
	 *
	 * @return string
	 */
	public function get_button( $type = "account" ) {

		$target    = "";
		$span      = "";
		$share_url = "";


		if ( $type === "account" ) {
			$url       = $this->account_results_url;
			$image     = $this->account_image;
			$target    = 'target="_blank"';
			$share_url = site_url( "/" );
			$width     = "40";
			$height    = "40";
		} else {
			$url   = $this->share_results_url;
			$image = $this->share_image;
			if ( $this->key === "feedly" ) {
				$count = $this->fetch_feedly_count();
				$span  = "<span>$count</span>";
			} else {
				$span = "<span>0</span>";
			}

			$share_url = ( isset( $this->share_value['url'] ) ? $this->share_value['url'] : false );
			$width     = "32";
			$height    = "32";
		}

		$share_url = str_replace( "dev", "com", $share_url );

		$html = "<li>";
		$html .= '<a data-shareurl="' . $share_url . '"   data-service-target="' . $this->key . '" href="' . $url . '" ' . $target . '>';
		$html .= '<img src="' . $image . '" alt="' . $this->name . '" width="' . $width . '" height="' . $height . '">';
//		if ( get_theme_mod( 'all_share_count_visible', false ) === true ) {
		$html .= $span;
//		}
		$html .= "</a>";
		$html .= "</li>";

		return $html;
	}

	/**
	 * シェア用のテキストを取得する
	 *
	 * @param $post_id
	 *
	 * @return bool|int|string
	 */
	public function get_share_text( $post_id ) {
		$text = get_the_excerpt( $post_id );
		if ( ! $text ) {
			$text = get_the_content();
			if ( mb_strlen( $text ) >= 160 ) {
				$text = mb_strpos( $text, 0, 160 );
			}
		}

		return $text;
	}

	public function fetch_feedly_count() {

		$count = get_transient( "feedly_count" );

		if ( ! $count ) {
			$feed_url    = rawurlencode( site_url( "/feed/" ) );
			$res         = '0';
			$subscribers = wp_remote_get( "http://cloud.feedly.com/v3/feeds/feed%2F$feed_url" );
			if ( ! is_wp_error( $subscribers ) && $subscribers["response"]["code"] === 200 ) {
				$subscribers = json_decode( $subscribers['body'] );
				if ( $subscribers ) {
					$subscribers = $subscribers->subscribers;
					set_transient( 'feedly_subscribers', $subscribers, 60 * 60 * 12 );
					$count = ( $subscribers ? $subscribers : '0' );
					set_transient( "feedly_count", $count, 60 * 60 * 12 );
				}
			}
		}


		return $count;
	}

}
