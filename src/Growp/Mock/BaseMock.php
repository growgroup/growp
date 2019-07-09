<?php

namespace Growp\Mock;

/**
 * Class GROWP_Create_Mock
 */
abstract class BaseMock {

	/**
	 * 作成するモックのキー
	 * @var string
	 */
	public $mock_lock_key = "";

	/**
	 * ロックした時の値
	 * @var int
	 */
	public $mock_lock_value = 1;


	/**
	 * 挿入した記事データ
	 * @var array
	 */
	public $insert_data = array();

	/**
	 * GROWP_Create_Mock constructor.
	 *
	 * @param string $mock_lock_key
	 */
	public function __construct( $mock_lock_key ) {
		$this->mock_lock_key = $mock_lock_key;
		$this->init();
	}


	/**
	 * 挿入したIDを取得
	 *
	 * @param string $post_name
	 *
	 * @return bool
	 */
	public function get_insert_id( $post_name ) {

		foreach ( $this->insert_data as $data ) {
			if ( isset( $data["post_name"] ) && $data["post_name"] === $post_name ) {
				return $data["post_id"];
			}
		}

		return false;
	}


	/**
	 * モックがロックされているかどうか判断
	 * @return mixed|void
	 */
	public function is_mock_lock() {
		return ( (int) get_option( $this->mock_lock_key, 0 ) === $this->mock_lock_value );
	}

	/**
	 * モック動作をロックする
	 * @return $this
	 */
	public function lock() {
		update_option( $this->mock_lock_key, $this->mock_lock_value );

		return $this;
	}

	/**
	 * 実行
	 */
	public function init() {
		if ( $this->is_mock_lock() ) {
			return false;
		}
		$this->run();
		$this->lock();
	}

	/**
	 * 実行
	 */
	protected function run() {
	}

	/**
	 * 投稿を挿入する
	 *
	 * @param $postdata
	 *
	 * @return $this
	 */
	protected function insert_post( $postdata ) {
		$_defaults = array(
			'post_status' => "publish",
			'post_date'   => date_i18n( 'Y-m-d H:i:s' )
		);
		$insert_id = wp_insert_post( wp_parse_args( $postdata, $_defaults ) );

		if ( ! $insert_id ) {
			$wp_error = new WP_Error();
			echo $wp_error->get_error_message( "【growp mock】 記事データの作成に失敗しました。 : " . get_class( $this ) );
			exit;
		}
		$this->insert_data[] = array(
			'post_id'   => $insert_id,
			'post_name' => $postdata["post_name"]
		);

		return $this;
	}


	/**
	 * 投稿メタの挿入
	 *
	 * @param int $post_id
	 * @param string $key
	 * @param $value
	 *
	 * @return $this
	 */
	protected function insert_post_meta( $post_id, $key, $value ) {
		$flag = update_post_meta( $post_id, $key, $value );
		if ( ! $flag ) {
			$wp_error = new WP_Error();
			echo $wp_error->get_error_message( "【growp mock】 カスタムフィールドの作成に失敗しました。 ID: " . $post_id );
			exit;
		}

		return $this;
	}
}
