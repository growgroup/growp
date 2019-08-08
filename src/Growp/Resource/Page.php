<?php

namespace Growp\Resource;

class Page {
	public $id = "";
	public $title = "";
	public $description = "";
	public $page_header_title = "";
	public $page_header_subtitle = "";
	public $page_header_image = "";
	public $type = "";
	public $path = [];
	public $absolute_path = "";
	public $relative_path = "";
	public $depth = [];
	public $components = [];
	public $main_content_html = [];

	public function __call( $method, $args ) {
		foreach ( array( '/^get_/' => 'get_', '/^set_/' => 'set_' ) as $regex => $replace ) {
			if ( preg_match( $regex, $method ) ) {
				$property = str_replace( $replace, '', $method );
				$method   = rtrim( $replace, '_' );
			}
		}

		$args = array_merge( array( $property ), $args );

		return call_user_func_array( array( $this, $method ), $args );
	}

	public function get( $prop ) {
		if ( isset( $this->$prop ) ) {
			return $this->$prop;
		}

		return null;
	}

	public function set( $prop, $value ) {
		if ( isset( $this->$prop ) ) {
			$this->$prop = $value;
		}
	}

	public function toArray() {
		$properties = get_object_vars( $this );

		return $this->toArrayRecursive( $properties );
	}

	private function toArrayRecursive( $property ) {
		// 配列の場合は中身を再帰的に配列に変換
		if ( is_array( $property ) ) {
			$array = [];
			foreach ( $property as $key => $value ) {
				$array[ $key ] = $this->toArrayRecursive( $value );
			}

			return $array;
		}
		// 配列変換が実装されているオブジェクトの場合は変換
		if ( is_object( $property ) ) {
			if ( ! ( $property instanceof Arrayable ) ) {
				throw new \LogicException( '配列に変換できないオブジェクトが含まれています' );
			}

			return $property->toArray();
		}

		// プリミティブ型の場合
		return $property;
	}
}
