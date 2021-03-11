<?php
/**
 * サイト共通 ページヘッダー
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */

/**
 * トップページでは非表示に
 */

if ( is_front_page() ) {
	return false;
}

global $post;

define( "HEADER_POST_ID_BLOG", 62 );
define( "HEADER_POST_ID_REQUIREMENTS", 79 );// カスタム投稿タイプがあれば固定ページのIDを入力する

$title_sub  = "";
$title_main = "";
$image      = GUrl::asset( '/assets/images/img-page-header-format.jpg' );
$add_class  = "";
$title_tag  = "h1";
if ( is_404() ) {
	$title_main = "指定されたページは存在しません";
	$title_sub  = "NOT FOUND";
	$image      = GUrl::asset( '/assets/images/img-page-header-format.jpg' );
} elseif ( is_search() ) {
	$title_main = "検索結果";
	$title_sub  = "SEARCH RESULT";
	$image      = GUrl::asset( '/assets/images/img-page-header-format.jpg' );
} else {

	/**
	 * デフォルトの値を設定
	 */
	if ( isset( $post ) ) {
		$title_main = get_the_title();
		$title_sub  = "";//strtoupper( $post->post_name );
		$image      = GUrl::asset( '/assets/images/img-page-header-format.jpg' );
		// カスタムフィールドを取得するIDを設定
		$header_post_id = $post->ID;
		if ( is_page() ) {
			$is_exist = file_exists( get_stylesheet_directory() . '/assets/images/img-page-header-' . $post->post_name . '.jpg' );
			if ( $is_exist ) {
				$image = GUrl::asset( '/assets/images/img-page-header-' . $post->post_name . '.jpg' );
			}
		}

	}

	// ページヘッダータイトル・画像を取得する固定ページを指定する場合
	if ( is_post_type_archive( "requirement" ) || is_singular( "requirement" ) || is_tax( array( "requirement_category" ) ) ) {
		$header_post_id = (int) HEADER_POST_ID_REQUIREMENTS;
	} else if ( is_archive() || is_home() || is_singular( "post" ) || is_date() || is_day() || is_category() || is_tag() || is_year() || is_month() || is_post_type_archive( "post" ) ) {
		$header_post_id = (int) HEADER_POST_ID_BLOG;
	}

	// ページヘッダータイトルのタグを変更する場合＜投稿は記事タイトルをh1にするためこのように指定する＞
	if ( is_singular( "post" ) ) {
		$title_tag = "div";
	}

	// カスタムフィールドの内容でデフォルト値を書き換え
	$h_title_main = get_field( "h_title_main", $header_post_id );
	$h_title_sub  = get_field( "h_title_sub", $header_post_id );
	$h_image      = get_field( "h_image", $header_post_id );
	if ( $h_title_main ) {
		$title_main = $h_title_main;
	}
	if ( $h_title_sub ) {
		$title_sub = $h_title_sub;
	}
	if ( $h_image && isset( $h_image["url"] ) ) {
		$image = $h_image['url'];
	}
	if ( is_singular( "post" ) ) {
		$current_cat = GTag::get_first_term( get_the_ID(), "category" );
		if ( $current_cat ) {
			try {
				$root_term = GTag::_post_check_term( $current_cat, "category" );
				if ( $root_term->slug != "topics" ) {
					$title_main = esc_html( $root_term->name );
					$title_sub  = strtoupper( $root_term->slug );
				}
			} catch ( Exception $e ) {

			}

		}
	}

	// カテゴリーアーカイブなどタームによって制御する場合は以下のように調整
	if ( is_category() ) {
		$current_cat = get_queried_object();
		if ( $current_cat ) {
			try {
				$root_term = GTag::_post_check_term( $current_cat, "category" );
				if ( isset( $root_term->slug ) && ! empty( $root_term->slug ) ) {
					$title_sub  = $title_main;
					$title_main = esc_html( $root_term->name );
				}
			} catch ( Exception $e ) {

			}

		}
	}
}

$title_main  = apply_filters( 'growp/page_header/title', $title_main );
$title_sub   = apply_filters( 'growp/page_header/subtitle', $title_sub );
$title_tag   = apply_filters( 'growp/page_header/title_tag', $title_tag );
$image       = apply_filters( 'growp/page_header/image', $image );
$pageheaders = apply_filters( 'growp/page_header', array(
		'title_main' => $title_main,
		'title_sub'  => $title_sub,
		'image'      => $image,
) );

?>
<div class="l-page-header">
	<div class="l-page-header__image" style="background-image: url(<?php echo $pageheaders["image"] ?>)">
	</div>
	<div class="l-page-header__inner">
		<div class="l-page-header__subtitle"><?php echo $pageheaders["title_sub"] ?></div>
		<<?php echo $title_tag; ?> class="l-page-header__title"><?php echo $pageheaders["title_main"] ?></<?php echo $title_tag; ?>>
	</div>
</div>

<?php GTemplate::get_component( "breadcrumb" ); ?>
