<?php

/**
 * Class gm_base_post
 *
 * 投稿オブジェクトの基幹クラス
 *
 */
abstract class gm_base_post {

	/**
	 * 投稿ID
	 * @var bool
	 */
	public $post_id = false;

	/**
	 * 投稿タイプ
	 * @var string
	 */
	public $post_type = "";

	/**
	 * 投稿オブジェクト
	 * @var array|WP_Post|null
	 */
	public $post = null;

	/**
	 * フィールド
	 * @var array
	 */
	public $fields = [];

	/**
	 * タクソノミー
	 * @var array
	 */
	public $taxonomies = [];

	/**
	 * ターム
	 * @var array
	 */
	public $terms = [];


	/**
	 * gm_base_post constructor.
	 * 初期化
	 *
	 * @param $post_id
	 */
	public function __construct( $post_id ) {
		$this->post_id = $post_id;
		$this->post    = get_post( $post_id );
		$this->set_fields();
		$this->set_terms();
	}

	/**
	 * ループ内で投稿を取得
	 *
	 * @return static
	 */
	public static function from_global() {
		return new static( get_the_ID() );
	}


	/**
	 * マジックメソッド
	 *
	 * @param $name
	 * @param $args
	 *
	 * @return ErrorException|false|mixed|string|void
	 */
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

	/**
	 * ゲッター
	 *
	 * @param $name
	 *
	 * @return mixed|void
	 *
	 */
	public function __get( $name ) {
		if ( isset( $this->post->{$name} ) ) {
			return $this->post->{$name};
		}

		return new ErrorException( "存在しないプロパティにアクセスしようとしています" );
	}

	/**
	 * カスタムフィールドをセットする
	 */
	public function set_fields() {
		$this->fields = get_field_objects( $this->post_id );
	}

	/**
	 * タームをセットする
	 * @return array
	 */
	public function set_terms() {
		if ( ! $this->taxonomies ) {
			return [];
		}
		foreach ( $this->taxonomies as $taxonomy ) {
			$terms  = [];
			$_terms = get_the_terms( $this->post_id, $taxonomy );
			if ( $_terms ) {
				foreach ( $_terms as $term ) {
					$classname = "gm_" . $term->taxonomy;
					if ( class_exists( $classname ) ) {
						$terms[] = new $classname( $term->term_id );
					} else {
						$terms[] = $term;
					}
				}
				$this->terms[ $taxonomy ] = $terms;
			}
		}
	}

	/**
	 * カスタムフィールド(ACF) 自体を取得
	 * @return array
	 */
	public function get_fields() {
		return $this->fields;
	}

	/**
	 * カスタムフィールドの値を取得する
	 *
	 * @param $key
	 * @param string $default
	 * @param boolean $raw
	 *
	 * @return mixed|string
	 */
	public function get_field_value( $key, $default = "", $raw = false ) {
		if ( isset( $this->fields[ $key ]["value"] ) && $this->fields[ $key ]["value"] ) {
			if ( $raw === false ) {
				if ( $this->fields[ $key ]["type"] === "taxonomy" ) {
					if ( ! is_array( $this->fields[ $key ]["value"] ) ) {
						$term = get_term( $this->fields[ $key ]["value"] );

						return $term->name;
					}
					if ( is_array( $this->fields[ $key ]["value"] ) ) {
						$terms = [];
						foreach ( $this->fields[ $key ]["value"] as $term_id ) {
							$terms[] = get_term( $term_id );
						}

						return $terms;
					}
				}
				if ( $this->fields[ $key ]["type"] === "image" && is_numeric( $this->fields[ $key ]["value"] ) ) {
					return wp_get_attachment_image_url( $this->fields[ $key ]["value"], "full" );
				}
			}

			return $this->fields[ $key ]["value"];
		}

		return $default;
	}

	/**
	 * カスタムフィールドを持っているか
	 *
	 * @param $key
	 *
	 * @return int
	 */
	public function has( $key ) {
		return ( $this->get_field_value( $key ) ) ? 1 : 0;
	}

	/**
	 * タームを取得する
	 *
	 * @param $taxonomy
	 *
	 * @return mixed
	 */
	public function get_terms( $taxonomy ) {
		if ( isset( $this->terms[ $taxonomy ] ) ) {
			return $this->terms[ $taxonomy ];
		}

		return false;
	}

	public function the_terms_map( $taxonomy, $callback ) {
		if ( isset( $this->terms[ $taxonomy ] ) && is_callable( $callback ) ) {
			foreach ( $this->terms[ $taxonomy ] as $term ) {
				$callback( $term );
			}
		}
		return false;
	}

	/**
	 * メタ情報を取得
	 *
	 * @param $key
	 * @param string $default
	 * @param bool $single
	 *
	 * @return mixed|string
	 */
	public function get_meta( $key, $default = "", $single = true ) {
		$value = get_post_meta( $this->post_id, $key, $single );
		if ( ! $value ) {
			return $default;
		}

		return $value;
	}

	/**
	 * すべてのメタ情報を取得
	 *
	 * @return array
	 */
	public function get_metas() {
		return get_post_custom( $this->post_id );
	}

	/**
	 * サムネイル画像のURLを取得
	 *
	 * @param string $size
	 *
	 * @return false|string
	 */
	public function get_thumbnail_url( $size = "full" ) {
		return GTag::get_thumbnail_url( $this->post_id, $size );
	}

	/**
	 * パーマリンクを取得
	 *
	 * @return false|string
	 */
	public function the_permalink() {
		echo get_the_permalink( $this->post_id );
	}

	/**
	 * パーマリンクを取得
	 *
	 * @return false|string
	 */
	public function get_permalink() {
		return get_the_permalink( $this->post_id );
	}

	/**
	 * 投稿日を取得
	 *
	 * @param string $format
	 *
	 * @return false|string
	 */
	public function get_post_date( $format = "Y.m.d" ) {
		return get_the_date( $format );
	}

	/**
	 * 投稿タイトルを取得する
	 *
	 * @param bool $excerpt
	 *
	 * @return string
	 */
	public function get_post_title( $excerpt = false ) {
		if ( $excerpt ) {
			return esc_html( mb_strimwidth( $this->post->post_title, 0, $excerpt ) );
		}

		return esc_html( $this->post->post_title );
	}

	/**
	 * 最初のタームを取得する
	 *
	 * @param $taxonomy
	 *
	 * @return mixed|null
	 */
	public function get_first_term( $taxonomy ) {
		$terms = $this->get_terms( $taxonomy );

		if ( $terms && isset( $terms[0] ) ) {
			return $terms[0];
		}

		return null;
	}

	/**
	 * 最初のターム名を取得する
	 *
	 * @param $taxonomy
	 *
	 * @return bool
	 */
	public function get_first_term_name( $taxonomy ) {
		$first_term = $this->get_first_term( $taxonomy );

		if ( $first_term && isset( $first_term->name ) ) {
			return $first_term->name;
		}

		return false;
	}


	public function get_first_image( $content = "" ) {
		$first_img = '';
		if ( ! $content ) {
			$content = $this->post->post_content;
		}
		ob_start();
		ob_end_clean();
		preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches );
		if ( isset( $matches[1][0] ) ) {
			$first_img = $matches[1][0];
		}

		return $first_img;
	}

	public function get_content() {
		$content = get_the_content( null, null, $this->post_id );
		$content = apply_filters( "the_content", $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		return $content;
	}

	protected function print_data_table_column( $label, $value, $merge = false ) {
		$colspan = '';
		if ( $merge ) {
			$colspan = "colspan='3'";
		}
		?>
		<th>
			<?php echo $label; ?>
		</th>
		<td <?php echo $colspan; ?>>
			<?php echo $value; ?>
		</td>
		<?php
	}

	protected function get_row_value( $tr ) {

		$value = $this->get_field_value( $tr["key"] );
		if ( $value ) {
			if ( is_array( $value ) && isset( $value["label"] ) ) {
				$value = $tr["before"] . $value["label"] . $tr["after"];
			}
			$value = $tr["before"] . $value . $tr["after"];
		} else {
			$value = false;
		}

		return $value;
	}


	protected function print_data_table( $keys ) {
		$row_column = 0;
		echo '<table class="c-table-lg">';
		foreach ( $keys as $tr ) {
			$value = $this->get_row_value( $tr );
			if ( ! $value ) {
				continue;
			}
			if ( isset( $tr["merge"] ) && $tr["merge"] ) {
				if ( $row_column == 2 ) {
					echo "</tr>";
				}
				echo "<tr>";
				$this->print_data_table_column( $tr["label"], $value, true );
				echo "</tr>";
				$row_column = 0;
			} else if ( isset( $tr["multiple"] ) && $tr["multiple"] ) {
				if ( $value ) {
					foreach ( $value as $item ) {
						echo "<tr>";
						$this->print_data_table_column( $item["th"], $item["td"] );
						echo "</tr>";
						$row_column = 0;
					}
				}
			} else {
				echo "<tr>";
				$this->print_data_table_column( $tr["label"], $value );
				$row_column ++;
				echo "</tr>";
			}
		}
		if ( $row_column != 2 ) {

			echo "</tr>";
		}
		echo '</table>';

	}

	public function generate_table( $keys = [] ) {
		$this->print_data_table( $keys );
	}


}
