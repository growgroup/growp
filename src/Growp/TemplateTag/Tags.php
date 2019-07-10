<?php

namespace Growp\TemplateTag;

use function class_exists;
use function count;
use ErrorException;
use function esc_html;
use function explode;
use function function_exists;
use function get_field;
use function get_post;
use function get_post_ancestors;
use function get_post_meta;
use function get_post_type;
use function get_post_type_object;
use function get_queried_object;
use function get_taxonomy;
use function get_term;
use function get_term_by;
use function get_term_link;
use function get_the_author;
use function get_the_date;
use function get_the_ID;
use function get_the_terms;
use Growp\Template\Foundation;
use function is_array;
use function is_author;
use function is_callable;
use function is_category;
use function is_day;
use function is_month;
use function is_numeric;
use function is_post_type_archive;
use function is_string;
use function is_tag;
use function is_tax;
use function is_wp_error;
use function is_year;
use function mb_strimwidth;
use function post_type_archive_title;
use function single_cat_title;
use function single_tag_title;
use function single_term_title;
use function sprintf;
use function strpos;
use function wp_get_attachment_image_url;
use function wp_get_post_terms;
use WP_Term;
use WPSEO_Primary_Term;
use Kirki;
use Kirki_Helper;

class Tags {

	/**
	 * サムネイル画像のURLを取得
	 *
	 * @param bool $post_id
	 * @param string $size
	 *
	 * @return false|string
	 */
	public static function get_thumbnail_url( $post_id = false, $size = "full" ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$thumbnail_id = get_post_meta( $post_id, "_thumbnail_id", true );
		$imageurl     = wp_get_attachment_image_url( $thumbnail_id, $size );
		if ( $imageurl ) {
			return $imageurl;
		}

		return GUrl::asset( "/assets/images/img-default-thumbnail.jpg" );
	}


	/**
	 * サムネイル画像のstyle属性を出力
	 *
	 * @param bool $post_id
	 * @param string $size
	 *
	 * @return string
	 */
	public static function the_thumbnail_style_attribute( $post_id = false, $size = "full" ) {
		$url = self::get_thumbnail_url( $post_id, $size );

		$attr = "";
		$attr = ' style="background-image: url(' . $url . ');"';
		echo $attr;
	}

	/**
	 * 最初のタームを取得する
	 *
	 * @param bool $post_id 投稿のID
	 * @param bool $taxonomy タクソノミー
	 * @param string $field 取得したいフィールド
	 *
	 * @return bool
	 */
	public static function get_first_term( $post_id = false, $taxonomy = false, $field = "" ) {

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$terms = get_the_terms( $post_id, $taxonomy );
		if ( is_wp_error( $terms ) ) {
			return false;
		}

		if ( $field && isset( $terms[0]->{$field} ) ) {
			return esc_html( $terms[0]->{$field} );
		}

		return esc_html( $terms[0] );
	}


	/**
	 * アーカイブのタイトルを取得する
	 *
	 * @return string|void
	 */
	public static function get_archive_title() {
		if ( is_category() ) {
			$title = sprintf( __( 'Category: %s' ), single_cat_title( '', false ) );
		} elseif ( is_tag() ) {
			$title = sprintf( __( 'Tag: %s' ), single_tag_title( '', false ) );
		} elseif ( is_author() ) {
			$title = sprintf( __( 'Author: %s' ), '<span class="vcard">' . get_the_author() . '</span>' );
		} elseif ( is_year() ) {
			$title = sprintf( __( 'Year: %s' ), get_the_date( _x( 'Y', 'yearly archives date format' ) ) );
		} elseif ( is_month() ) {
			$title = sprintf( __( 'Month: %s' ), get_the_date( _x( 'F Y', 'monthly archives date format' ) ) );
		} elseif ( is_day() ) {
			$title = sprintf( __( 'Day: %s' ), get_the_date( _x( 'F j, Y', 'daily archives date format' ) ) );
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Asides', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = sprintf( __( 'Archives: %s' ), post_type_archive_title( '', false ) );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf( __( '%1$s: %2$s' ), $tax->labels->singular_name, single_term_title( '', false ) );
		} else {
			$title = __( 'Archives' );
		}

		return $title;

	}

	/**
	 * 親ページを判断する
	 *
	 * @param $slug
	 *
	 * @return bool
	 */
	public static function is_parent_page( $slug ) {
		$return = false;
		if ( is_string( $slug ) ) {
			$return = self::_is_parent_page( $slug );
		}
		if ( is_array( $slug ) ) {
			foreach ( $slug as $s ) {
				$return = self::_is_parent_page( $s );
				if ( $return === true ) {
					break;
				}
			}
		}

		return $return;
	}


	private static function _is_parent_page( $slug, $post_id = 0 ) {
		if ( ! $post_id ) {
			global $post;
		} else {
			$post = get_post( $post_id );
		}

		if ( $post->post_name === $slug ) {
			return true;
		}

		if ( ! $post->post_parent ) {
			return false;
		}
		$parent_post = get_post( $post->post_parent );
		if ( $parent_post->post_name === $slug ) {
			return true;
		}

		return self::_is_parent_page( $slug, $parent_post->ID );
	}

	/**
	 * テンプレートのパスを取得する
	 * @return mixed
	 */
	public static function get_template_path() {
		return Foundation::get_instance()->get_template_path();
	}

	/**
	 * @return mixed
	 */
	public static function get_template_base() {
		return Foundation::$base;
	}


	/**
	 * 投稿に対して、親カテゴリを指定し、その親タームに属するタームが設定されているかどうか判断する
	 *
	 * @param $post_id
	 * @param $term
	 * @param string $taxonomy
	 *
	 * @return bool
	 */
	public static function post_in_parent_term( $post_id, $term, $taxonomy = "category" ) {

		$terms = get_the_terms( $post_id, $taxonomy );

		if ( is_wp_error( $terms ) ) {
			echo $terms->get_error_message();
			exit;
		}
		if ( is_string( $term ) ) {
			$term = get_term_by( "slug", $term, $taxonomy );
		}
		$check_term = get_term( $term );

		foreach ( $terms as $_term ) {
			if ( $_term->term_id === $check_term->term_id ) {
				return true;
			}
			$parent_term = self::_post_check_term( $_term, $taxonomy );
			if ( $parent_term->slug === $_term->slug ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * 再帰関数
	 *
	 * @param $term
	 * @param $taxonomy
	 *
	 * @return mixed
	 */
	public static function _post_check_term( $term, $taxonomy ) {
		if ( $term->parent ) {
			$_term  = get_term( $term->parent, $taxonomy );
			$return = self::_post_check_term( $_term, $taxonomy );
		} else {
			$return = $term;
		}

		return $return;
	}

	/**
	 * アタッチメントのURLを取得する
	 *
	 * @param $attachment_id アタッチメントID
	 * @param string $size
	 *
	 * @return string 存在する場合はファイルのURL。しない場合はデフォルト画像
	 */
	public static function get_attachment_url( $attachment_id, $size = "full" ) {
		$image_url = wp_get_attachment_image_url( $attachment_id, $size, false );

		if ( $image_url ) {
			return $image_url;
		}

		return GUrl::asset( "/assets/images/img-default-thumbnail.jpg" );
	}

	/**
	 * スマートフォンかどうか判断する
	 * @return bool
	 */
	public static function is_smartphone() {
		$mb = new \Mobile_Detect();

		return ( $mb->isMobile() && ! $mb->isTablet() );
	}

	/**
	 * 投稿に設定されている特定のタクソノミーのタームのリストを出力する
	 *
	 * @param int $post_id 投稿ID
	 * @param string $taxonomy タクソノミー
	 *
	 * @return string
	 */
	public static function get_the_terms_label_list( $post_id = 0, $taxonomy = "category" ) {

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$terms = get_the_terms( $post_id, $taxonomy );
		$list  = "<ul>";
		foreach ( $terms as $t ) {
			$list .= '<li><a href="' . get_term_link( $t, $taxonomy ) . '" class="c-label">' . $t->name . '</a></li>';
		}
		$list .= "</ul>";

		return $list;
	}


	/**
	 * タクソノミーアーカイブページにて、
	 * 現在表示しているタームを親カテゴリを含めて判断する
	 *
	 * @param $parent 含まれているかどうか判断したい親ターム
	 * @param string $taxonomy タクソノミー
	 *
	 * @return bool true: 親タームに属する false: 属さない
	 */
	public static function archive_in_parent_term( $parent, $taxonomy = "category" ) {
		global $wp_query;
		if ( empty( $wp_query->queried_object->term_id ) ) {
			return false;
		}

		/**
		 * IDではない場合にスラッグからterm_idを取得する
		 */
		if ( ! is_numeric( $parent ) ) {
			$_term  = get_term_by( "slug", $parent, $taxonomy );
			$parent = $_term->term_id;
		}

		if ( $wp_query->queried_object->term_id === $parent ) {
			return true;
		}
		$current_term_parent = $wp_query->queried_object->parent;

		if ( $current_term_parent ) {
			$is_parent = self::archive_check_term_parent( $parent, $current_term_parent, $taxonomy );
		}

		return $is_parent;
	}

	/**
	 * 再帰
	 *
	 * @param $targetparent
	 * @param $term
	 * @param $taxonomy
	 *
	 * @return bool
	 */
	public static function archive_check_term_parent( $targetparent, $term, $taxonomy ) {
		$_term = get_term( $term, $taxonomy );

		if ( $targetparent === $_term->term_id ) {
			return true;
		}
		$return = false;
		if ( $_term->parent ) {
			$return = self::archive_check_term_parent( $targetparent, $_term->parent, $taxonomy );
		}

		return $return;
	}

	/**
	 * タームのラベルを出力する
	 *
	 * @param string $taxonomy
	 */
	public static function term_label_list( $taxonomy = "category" ) {
		$post_id = get_the_ID();
		$terms   = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => "all" ) );
		foreach ( $terms as $term_key => $term ) {
			?>
			<div class="p-post__category"><span class="c-label is-primary"><?php echo $term->name ?></span>
			</div>
			<?php
		}
	}

	/**
	 * タームのリンク付きラベル一覧を出力する
	 *
	 * @param string $taxonomy タクソノミースラッグ
	 */
	public static function category_label_links( $taxonomy = "category" ) {
		$post_id = get_the_ID();
		$terms   = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => "all" ) );
		foreach ( $terms as $term_key => $term ) {
			?>
			<div class="p-post__category">
				<a href="<?php echo get_term_link( $term ) ?>" class="c-label is-primary"><?php echo $term->name ?></a>
			</div>
			<?php
		}
	}

	/**
	 * 文字列を丸める(mb_strimwidthのエイリアス)
	 *
	 * @param string $text 加工する文字列
	 * @param integer $length 丸める文字列
	 * @param string $after 付与する文字列
	 *
	 * @return 文字列
	 *
	 */
	public static function trimwidth( $text, $length = 50, $after = "..." ) {
		return mb_strimwidth( $text, 0, $length, $after );
	}

	/**
	 * ACFフィールドの出力
	 *
	 * @param $key string | array 配列で指定した場合、どちらかが存在する場合に出力する
	 * @param $callback
	 * @param $post_id
	 *
	 * @return bool|void
	 *
	 * @throws ErrorException AdvancedCustomFields プラグインがインストールされていない場合には例外を投げる
	 */
	public static function acf( $key, $callback = null, $post_id = 0 ) {
		if ( ! function_exists( 'get_field' ) ) {
			throw new ErrorException( "500", "Advanced Custom Fields プラグインがインストールされていません" );
			exit;
		}
		if ( is_numeric( $callback ) ) {
			$post_id = $callback;
		}
		if ( is_callable( $post_id ) ) {
			$callback = $post_id;
		}
		if ( $post_id === 0 ) {
			$post_id = get_the_ID();
		}
		// グループに対応
		$value = get_field( $key, $post_id );
		if ( ! $value && strpos( $key, ".", 1 ) > - 1 ) {
			$keys  = explode( ".", $key );
			$value = "";
			foreach ( $keys as $kk => $k ) {
				if ( $value && isset( $value[ $k ] ) ) {
					$value = $value[ $k ];
				} else {
					$value = "";
				}
				if ( ! $value ) {
					$value = get_field( $keys[ $kk ], $post_id );
				}
			}
		}
		/**
		 * キーが配列指定の場合
		 */
		if ( is_array( $key ) ) {
			$value    = [];
			$exitflag = false;
			foreach ( $key as $kk => $k ) {
				$value[ $k ] = get_field( $k, $post_id );
				if ( $value[ $k ] ) {
					$exitflag = true;
				}
			}
			if ( ! $exitflag ) {
				return false;
			}
		}
		if ( $value && is_callable( $callback ) ) {
			return $callback( $value );
		}

		return $value;
	}

	/**
	 * WordPress SEO のプライマリカテゴリを取得する
	 *
	 * @param string $taxonomy
	 *
	 * @return bool|int
	 */
	public static function get_primary_term( $taxonomy = "category" ) {
		if ( ! class_exists( "WPSEO_Primary_Term" ) ) {
			return false;
		}
		$p = new WPSEO_Primary_Term( $taxonomy, get_the_ID() );
		if ( ! $p->get_primary_term() ) {
			$terms = get_the_terms( get_the_ID(), $taxonomy );
			if ( is_wp_error( $terms ) ) {
				return false;
			}

			return ( isset( $terms[0] ) ) ? $terms[0] : false;
		}

		return $p->get_primary_term();
	}

	/**
	 * 親のタームを取得
	 *
	 * @param $term
	 * @param $taxonomy
	 *
	 * @return WP_Term
	 */
	public static function get_parent_term( $term, $taxonomy = "category" ) {
		if ( $term->parent === 0 ) {
			return $term;
		}

		return self::get_parent_term( get_term( $term->parent, $taxonomy ), $taxonomy );
	}

	/**
	 * ページの階層を取得
	 *
	 * @param int $post_id
	 *
	 * @return int
	 */
	public static function get_page_level( $post_id = 0 ) {
		if ( ! $post_id ) {
			global $post;
			$post_id = $post->ID;
		}
		// 投稿タイプが階層対応していない場合は0を返す
		$post_type = get_post_type( $post_id );
		$pt_obj    = get_post_type_object( $post_type );
		if ( ! $pt_obj->hierarchical ) {
			return 0;
		}
		$levels = get_post_ancestors( $post_id );

		return count( $levels );
	}

	public static function get_option( $key, $default = "" ) {
		return apply_filters( "growp/customizer/" . $key, self::get_theme_mod( $key, $default ) );
	}

	public static function option( $key, $callback = false, $default = "" ) {
		if ( is_callable( $callback ) ) {
			$val = self::get_option( $key, $default );
			if ( ! $val ) {
				return false;
			}
			$callback( $val, $default );

			return true;
		} else {
			return self::get_option( $key, $default );
		}
	}

	public static function the_option( $key, $default = "" ) {
		echo static::get_option( $key, $default );
	}

	public static function get_theme_mod( $field_id, $default_value = '' ) {
		if ( $field_id ) {
			if ( ! $default_value ) {
				if ( class_exists( 'Kirki' ) && isset( Kirki::$fields[ $field_id ] ) && isset( Kirki::$fields[ $field_id ]['default'] ) ) {
					$default_value = Kirki::$fields[ $field_id ]['default'];
				}
			}
			$value = get_theme_mod( $field_id, $default_value );

			return $value;
		}

		return false;
	}

}
