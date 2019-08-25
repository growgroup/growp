<?php

namespace Growp\Hooks;


use function acf_esc_attrs;
use function acf_merge_attributes;
use function esc_html;
use function is_callable;
use function strtolower;
use function wp_parse_args;

class SearchModel {

	// input name
	public $name = "";

	public $method = "";

	// tax_query or meta_query
	public $query_type = "";

	// フォームの種別
	// text or textarea or select or checkbox or radio
	public $input_type = "";

	// 検索の値
	public $value = "";

	// 属性値
	public $attrs = [
		'placeholder' => "",
		'required'    => "",
		'class'       => "",
		'id'          => "",
	];

	// デフォルトバリュー
	public $default_value = "";

	// 透過文字
	public $placeholder = "";

	// 必須かどうか
	public $required = false;

	// 選択肢
	public $choices = [];

	// 選択している値
	public $selected = "";

	// チェックしている値
	public $checked = "";

	// 比較演算子
	// =, != , <=, >=, IN, NOT IN, BETWEEN
	public $compare = "";

	public function __construct( $settings ) {
		$settings = wp_parse_args( $settings, [
			'name'          => '',
			'method'        => 'GET',
			'label'         => '',
			'query_type'    => 'meta_query',
			'input_type'    => 'text',
			'value'         => '',
			'choices'       => [],
			'default_value' => '',
			'required'      => false,
			'compare'       => '=',
			'attrs'         => []
		] );
		foreach ( $settings as $key => $setting ) {
			if ( isset( $this->{$key} ) ) {
				$this->{$key} = $setting;
			}
		}

		if ( is_callable( $this->choices ) ) {
			$choices       = $this->choices;
			$this->choices = $choices();
		}

		$this->set_value();
		$this->parse_attrs();
	}

	public function set_value() {

		if ( strtolower( $this->method ) === "get" ) {
			$this->value = isset( $_GET[ $this->name ] ) ? esc_html( $_GET[ $this->name ] ) : "";
		}

		if ( strtolower( $this->method ) === "post" ) {
			$this->value = isset( $_POST[ $this->name ] ) ? esc_html( $_POST[ $this->name ] ) : "";
		}

	}

	/**
	 * 属性値を加工
	 */
	public function parse_attrs() {
		$this->attrs["name"]     = $this->name;
		$this->attrs["required"] = $this->required;
		$this->attrs["value"]    = $this->value;
		foreach ( $this->attrs as $attr_key => $attr ) {
			if ( $attr_key === "required" && ! $attr ) {
				unset( $this->attrs[ $attr_key ] );
			}
		}
	}

	/**
	 * HTMLを出力
	 *
	 * @param bool $echo
	 *
	 * @return void | string
	 */
	public function html( $echo = false ) {
		$html = "";
		switch ( $this->input_type ) {

			case 'text' :
				$attrs_text = $this->esc_attrs( $this->attrs );
				$html       .= "<input type='text' $attrs_text />";
				break;

			case 'textarea' :
				$attrs_text = $this->esc_attrs( $this->attrs );
				$html       .= "<textarea $attrs_text>$this->value</textarea>";
				break;

			case 'checkbox' :
			case 'radio' :
				foreach ( $this->choices as $choice ) {
					$_attrs     = wp_parse_args( $choice, $this->attrs );
					$attrs_text = $this->esc_attrs( $_attrs );
					if ( ! $_attrs["checked"] ) {
						unset( $_attrs["checked"] );
					}
					$html .= "<input type='$this->input_type' $attrs_text />";
				}
				break;

			case 'select' :
				$attrs_text = $this->esc_attrs( $this->attrs );
				$html       .= "<select $attrs_text>";
				foreach ( $this->choices as $choice ) {
					$_attrs = wp_parse_args( $choice, [
						'value'    => "",
						'label'    => "",
						'selected' => false,
					] );
					if ( ! $_attrs["selected"] ) {
						unset( $_attrs["selected"] );
					}
					$attrs_text = $this->esc_attrs( $_attrs );
					$html       .= "<option $attrs_text />";
				}
				$html .= "</select>";
				break;
		}
		if ( $echo ) {
			echo $html;

			return;
		}

		return $html;
	}

	function esc_attrs( $attrs ) {
		$html = '';
		// Loop over attrs and validate data types.
		foreach ( $attrs as $k => $v ) {

			// String (but don't trim value).
			if ( is_string( $v ) && ( $k !== 'value' ) ) {
				$v = trim( $v );

				// Boolean
			} elseif ( is_bool( $v ) ) {
				$v = $v ? 1 : 0;

				// Object
			} elseif ( is_array( $v ) || is_object( $v ) ) {
				$v = json_encode( $v );
			}

			// Generate HTML.
			$html .= sprintf( ' %s="%s"', esc_attr( $k ), esc_attr( $v ) );
		}

		// Return trimmed.
		return trim( $html );
	}

}
