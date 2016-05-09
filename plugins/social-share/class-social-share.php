<?php

/**
 * ソーシャルシェアボタン用 class
 */
class GrowpSocialShare {

	// 表示する形式
	public $type = "";

	// 有効にするボタン
	public $active_buttons = array();

	// ボタンの設定
	public $buttons = array();

	/**
	 * GrowpSocialShare constructor.
	 * 初期化
	 */
	public function __construct() {
		$this->set_active_buttons();
		$this->set_social_buttons();
	}

	/**
	 * 有効なボタンをセットする
	 */
	public function set_active_buttons() {
		$activates            = growp_social_buttons();
		$this->active_buttons = $activates;
	}

	/**
	 * ボタンを非有効化
	 *
	 * @param string $service サービス名
	 *
	 * @return object $this
	 */
	public function unsetButton( $service ) {
		if ( isset( $this->buttons[ $service ] ) ) {
			unset( $this->buttons[ $service ] );
		}

		return $this;
	}

	/**
	 * ボタンを追加
	 *
	 * @param $service
	 * @param $button
	 *
	 * @return $this
	 */
	public function addButton( $service, $button ) {
		$this->buttons[ $service ] = $button;

		return $this;
	}

	/**
	 * ソーシャルボタンの基本的な設定を記述
	 */
	public function set_social_buttons() {

		$this->buttons = array();

		foreach ( $this->active_buttons as $button => $bool ) {
			if ( $bool ) {
				$classname = $button . 'Button';
				if ( class_exists( $classname ) ) {
					$this->buttons[ $button ] = new $classname();
				}
			}
		}

	}

	/**
	 * 取得する
	 *
	 * @param $type
	 *
	 * @return string
	 */
	public function get( $type ) {
		$html = "";
		foreach ( $this->buttons as $button ) {
			$html .= $button->get_button( $type );
		}

		return $html;
	}

}
