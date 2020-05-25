<?php


abstract class gm_base_term {

	public $taxonomy = "";

	public static $_taxonomy = "";

	public $term_id = 0;

	public $term = [];

	public $fields = [];

	public $metas = [];

	public $field_keys = [

	];

	public function __construct( $term_id ) {
		if ( is_object( $term_id ) && isset( $term_id->term_id ) ) {
			$term_id = $term_id->term_id;
		}
		$this->term_id = $term_id;
		$this->term    = WP_Term::get_instance( $term_id );
		$this->set_fields();
		$this->set_metas();

	}


	/**
	 * ゲッター
	 *
	 * @param $name
	 *
	 * @return mixed|void
	 *
	 */
	public function __get( $name ) {
		if ( isset( $this->term->{$name} ) ) {
			return $this->term->{$name};
		}

		return new ErrorException( "存在しないプロパティにアクセスしようとしています" );
	}

	public function __call( $name, $args ) {
		$type = substr( $name, 0, 3 );
		$prop = substr( $name, 4, 100 );
		if ( $type === "get" ) {
			if ( isset( $this->{$prop} ) ) {
				return $this->{$prop};
			}
			if ( isset( $this->term->{$prop} ) ) {
				return $this->term->{$prop};
			}
			if ( isset( $this->fields[ $prop ] ) ) {
				if ( $this->fields[ $prop ]["type"] === "image" && $this->fields[ $prop ]["return_format"] === "id" ) {
					return wp_get_attachment_image_url( $this->fields[ $prop ]["value"], "full" );
				}
				if ( $this->fields[ $prop ]["type"] === "image" && $this->fields[ $prop ]["return_format"] === "array" ) {
					return $this->fields[ $prop ]["value"]["url"];
				}

				return $this->fields[ $prop ]["value"];
			}
		}

		return new ErrorException( "存在しないプロパティにアクセスしようとしています" );
	}

	public function get_name() {
		return $this->term->name;
	}


	public function set_fields() {
		$this->fields = get_field_objects( $this->term );
	}

	/**
	 * カスタムフィールドを取得する
	 */
	public function set_metas() {
		$this->metas = get_option( "taxonomy_" . $this->term_id );
	}

	/**
	 * ACFの値を取得
	 *
	 * @param $key
	 * @param string $default
	 *
	 * @return mixed|string
	 */
	public function get_field_value( $key, $default = "" ) {
		if ( isset( $this->fields[ $key ]["value"] ) && $this->fields[ $key ]["value"] ) {
			return $this->fields[ $key ]["value"];
		}

		return $default;
	}

	/**
	 * カスタムフィールドの値を取得
	 *
	 * @param $key
	 * @param string $default
	 *
	 * @return mixed|string
	 */
	public function get_meta_value( $key, $default = "" ) {
		if ( isset( $this->metas[ $key ] ) && $this->metas[ $key ] ) {
			return $this->metas[ $key ];
		}

		return $default;
	}


	/**
	 * 一覧ページのURを取得
	 *
	 * @return string|WP_Error
	 */
	public function get_archive_link() {
		return get_term_link( $this->term );
	}

	/**
	 * 現在のタームを取得する
	 *
	 * @param bool $post_id
	 * @param bool $only
	 *
	 * @return array|mixed
	 */
	public static function get_current_term( $post_id = false, $only = true ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$categories = get_the_terms( $post_id, static::class );
		$terms      = [];
		foreach ( $categories as $category ) {
			$terms[] = new static( $category->term_id );
		}
		if ( $only && $terms && isset( $terms[0] ) && $terms[0] ) {
			return $terms[0];
		}

		return $terms;
	}

	public function is_tax() {
		return ( is_tax( $this->taxonomy, [ $this->term_id ] ) );
	}

	public function get_posts_query( $post_type, $posts_per_page = 6 ) {
		return new WP_Query( [
			'post_type'      => $post_type,
			'posts_per_page' => $posts_per_page,
			'tax_query'      => [
				[
					'taxonomy' => $this->taxonomy,
					'terms'    => $this->term_id,
				]
			]
		] );
	}

	public function get_parent_terms(){

	}

	public function get_children_terms( $args = [] ) {
		$default_args = [
			'parent'   => $this->term_id,
			'taxonomy' => $this->taxonomy
		];
		$args         = wp_parse_args( $args, $default_args );
		$terms        = get_terms( $args );

		$_terms = [];
		if ( $terms ) {
			foreach ( $terms as $term ) {
				$_terms[] = new static( $term->term_id );
			}
		}

		return $_terms;
	}
}
