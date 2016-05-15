<?php

/**
 * 一気にページを作成
 *
 * $posts = array(
 *      'page' => [
 *          [
 *             'post_title' => "",
 *             'post_name' => "",
 *             'post_content' => "",
 *          ]
 *      ]
 * )
 *
 */
class bulkCreatePosts {

	// 作成する投稿の内容
	private $settings = array();

	private $default_post_field = array();

	/**
	 * bulkCreatePosts constructor.
	 * 初期化
	 */
	public function __construct() {

		$this->default_post_field = array(
			'post_title'        => "", // 投稿タイトル
			'post_name'         => "", // 投稿スラッグ
			'post_content'      => "", // コンテンツ
			'post_status'       => "publish", // 投稿ステータス
			'post_author'       => "0", // 著者
			'post_date'         => date_i18n( 'Y-m-d H:i:s' ), // 投稿の日付
			'post_date_gmt'     => "", // GMT の投稿の日付
			'post_excerpt'      => "", // 抜粋文
			'comment_status'    => "closed", // コメントのステータス
			'ping_status'       => "closed",
			'post_password'     => "",
			'to_ping'           => "",
			'pinged'            => "",
			'post_modified'     => "",
			'post_modified_gmt' => "",
			'post_parent'       => "",
			'guid'              => "",
			'menu_order'        => "",
			'post_type'         => "",
			'post_mime_type'    => "",
			'comment_count'     => "",
		);

	}


	/**
	 * 作成する投稿情報をセット
	 *
	 * @param $settings
	 *
	 * @return $this
	 */
	public function set_settings( $settings ) {
		$this->settings = $settings;
		return $this;
	}


	/**
	 * 投稿を作成する
	 * @throws Exception
	 */
	public function create_post() {

		// 投稿設定がされていない場合
		if ( empty( $this->settings ) || ! $this->settings ) {
			throw new Exception( "作成する投稿の情報が設定されていません。" );
		}

		$temp_post_ids = [];

		// 投稿タイプごとにループ
		foreach ( $this->settings as $post_type => $posts ) {


			// 投稿ごとにループ
			foreach ( $posts as $post ) {

				$post = wp_parse_args( $post, $this->default_post_field );
				// 投稿タイプをセットする
				$post['post_type'] = $post_type;

				// すでに同じタイトルの記事がそ存在する場合はループを飛ばす
				if ( $this->is_exits_title( $post['post_title'] ) ){
					continue;
				}

				$temp_post_ids[] = wp_insert_post( $post );

			}
		}
		
		return $this;
	}


	/**
	 * 投稿を更新する
	 */
	public function update_post(){

		// 投稿設定がされていない場合
		if ( empty( $this->settings ) || ! $this->settings ) {
			throw new Exception( "更新する投稿の情報が設定されていません。" );
		}

		$temp_post_ids = [];

		// 投稿タイプごとにループ
		foreach ( $this->settings as $post_type => $posts ) {


			// 投稿ごとにループ
			foreach ( $posts as $post ) {

				$post = wp_parse_args( $post, $this->default_post_field );

				// 投稿タイプをセットする
				$post['post_type'] = $post_type;

				// すでに同じタイトルの記事がそ存在する場合はループを飛ばす
				$post_id = $this->is_exits_title( $post['post_title'] ) ;
				if ( $post_id ){
					$post['ID'] = $post_id;
					$temp_post_ids[] = wp_update_post($post);
				}
			}
		}

		return $this;

	}

	/**
	 * タイトルを比較
	 *
	 * @param $title
	 *
	 * @return bool
	 */
	public function is_exits_title( $title ) {
		global $wpdb;

		$sql     = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s", $title );
		$results = $wpdb->get_results( $sql );

		if ( $results && isset($results[0]->ID) ){
			// 投稿IDを返す
			return $results[0]->ID;
		}

		return false;

	}
}
