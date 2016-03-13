<?php
/**
 * このテーマオリジナルのテンプレートタグ
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */

/**
 * ページナビゲーションを出力
 *
 * @return void
 */
function growp_paging_nav() {

	$defaults = array(
		'show_all'           => false,
		'prev_next'          => true,
		'prev_text'          => __( '&laquo; Previous' ),
		'next_text'          => __( 'Next &raquo;' ),
		'end_size'           => 1,
		'mid_size'           => 2,
		'type'               => 'list',
		'add_args'           => array(), // array of query args to add
		'add_fragment'       => '',
		'before_page_number' => '',
		'after_page_number'  => ''
	);
	echo "<div class='text-center'>";
	the_posts_pagination( $defaults );
	echo "</div>";
}


/**
 * 投稿ナビゲーションを出力
 *
 * @return void
 */
function growp_post_nav() {

	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<div class="c-navs-post">
		<ul>
			<li class="c-navs-post__prev">
				<?php
				previous_post_link( '%link', _x( '<span>前のページを見る</span>', '前のページを見る', 'growp' ) ); ?>
			</li>
			<li class="c-navs-post__next">
				<?php
				next_post_link( '%link', _x( '<span>次のページを見る</span>', '次のページを見る', 'growp' ) ); ?>
			</li>
		</ul>
	</div>
	<?php
}

/**
 * 投稿一覧ナビゲーション
 * 次の記事へ、前の記事へ形式
 */
function growp_page_nav() {
	?>
	<div class="row mg-bottom is-xlg">
		<div class="col-md-4 col-sm-4">
			<p class="c-button">
				<?php
				previous_posts_link( '%link', _x( '<span>前のページを見る</span>', '前のページを見る', 'growp' ) ); ?>
			</p>
		</div>
		<div class="col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4">
			<p class="c-button">
				<?php
				next_posts_link( '%link', _x( '<span>次のページを見る</span>', '次のページを見る', 'growp' ) ); ?>
			</p>
		</div>
	</div>
	<?php
}


function next_post_link_addclass($format){
	$format = str_replace('href=', 'class="c-button is-angle is-right" href=', $format);
	return $format;
}
function pre_post_link_addclass($format){
	$format = str_replace('href=', 'class="c-button is-angle is-left" href=', $format);
	return $format;
}

add_filter('next_post_link', 'next_post_link_addclass');
add_filter('previous_post_link', 'pre_post_link_addclass');
add_filter('next_posts_link', 'next_post_link_addclass');
add_filter('previous_posts_link', 'pre_post_link_addclass');

function next_posts_link_attributes() {
	return 'class="c-button is-angle is-left"';
}


/**
 * 投稿メタ情報を出力
 *
 * @return void
 */
function growp_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s"> %2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) )
	);


	$posted_string = __( '<i class="fa fa-calendar-o"></i> <span class="posted-on">Posted on %1$s</span> ', 'growp' );
	$author_string = __( '<i class="fa fa-user"></i><span class="byline"> by %1$s</span>', 'growp' );

	if ( "true" ==  get_theme_mod( 'single_post_date', "true" ) ) {
		printf($posted_string, sprintf('<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url(get_permalink()),
			$time_string
		));
	}

	if ( "true" ==  get_theme_mod( 'single_post_author', "true" ) ) {
		printf(
			$author_string,
			sprintf('<span class="author"><a class="url fn n" href="%1$s">%2$s</a></span>',
				esc_url(get_author_posts_url(get_the_author_meta('ID'))),
				esc_html(get_the_author())
			)
		);
	}
}


function growp_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {

		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			'number'     => 2,
		) );

		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Flush out the transients used in growp_categorized_blog.
 */
function growp_category_transient_flusher() {
	delete_transient( 'all_the_cool_cats' );
}

add_action( 'edit_category', 'growp_category_transient_flusher' );
add_action( 'save_post', 'growp_category_transient_flusher' );


/**
 * パンくずを出力
 *
 * @see classes/class-breadcrumbs.php
 * @return void
 */
function growp_breadcrumb() {

	$templates = array(
		'before'   => '<nav class="c-breadcrumb"><ul class="breadcrumbs">',
		'after'    => '</ul></nav>',
		'standard' => '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">%s</li>',
		'current'  => '<li class="current">%s</li>',
		'link'     => '<a href="%s" itemprop="url"><span itemprop="title">%s</span></a>',
	);

	$options = array(
		'show_htfpt' => true,
		'separator' => ''
	);

	// init
	$breadcrumb = new growp_Breadcrumbs( $templates, $options );

}

/**
 * For function for outputting pagination
 *
 * @param boolean $output
 *
 * @return void
 */
function growp_pagination( $output = true ) {

	global $wp_query, $wp_rewrite;

	$base   = trailingslashit( get_pagenum_link( 1 ) ) . '%_%';
	$format = ( $wp_rewrite->using_permalinks() ) ? 'page/%#%' : '?paged=%#%';
	$args   = array(
		'base'      => $base,
		'format'    => $format,
		'current'   => max( 1, get_query_var( 'paged' ) ),
		'total'     => $wp_query->max_num_pages,
		'prev_next' => true,
		'prev_text' => '&larr;' . __( 'Previous', 'growp' ),
		'next_text' => __( 'Next', 'growp' ) . '&rarr;',
	);

	$before     = apply_filters( 'growp_paginavi_before', '<nav class="pagination primary-links">' );
	$pagination = paginate_links( $args );
	$after      = apply_filters( 'growp_paginavi_after', '</nav>' );

	if ( $output && $pagination ) {
		echo $before . wp_kses_post( $pagination ) . $after;

		return false;
	}

	return $pagination;

}

/**
 * get_header
 *
 * @return void
 */
function growp_get_header() {

	$header_style = get_theme_mod( 'header_style', '' );

	get_template_part( 'modules/header' );

}


/**
 * Social Icon
 */
add_action( 'get_header', 'growp_social_icon' );

function growp_social_icon() {

	$icons                 = '';
	$social['facebook']    = get_theme_mod( 'socal_facebook', '' );
	$social['twitter']     = get_theme_mod( 'socal_twitter', '' );
	$social['github']      = get_theme_mod( 'socal_github', '' );
	$social['google_plus'] = get_theme_mod( 'socal_google_plus', '' );

	if ( $social['facebook'] ) {
		$icons .= '	<a href="' . esc_url( $social['facebook'] ) . '" target="_blank"><i class="fa fa-facebook"></i></a>
		';
	}

	if ( $social['twitter'] ) {
		$icons .= '	<a href="' . esc_url( $social['twitter'] ) . '" target="_blank"><i class="fa fa-twitter"></i></a>
		';
	}

	if ( $social['github'] ) {
		$icons .= '	<a href="' . esc_url( $social['github'] ) . '" target="_blank"><i class="fa fa-github"></i></a>
		';
	}

	if ( $social['google_plus'] ) {
		$icons .= '	<a href="' . esc_url( $social['google_plus'] ) . '" target="_blank"><i class="fa fa-google-plus"></i></a>
		';
	}

	if ( $icons ) {
		echo '<div class="social-icons">
		' . $icons . '</div>
		';
	}

}

/**
 * growp_template_path
 *
 * @return template path
 * @since 0.0.1
 */
function growp_template_path() {
	return Theme_Wrapper::$main_template;
}

/**
 * growp_template_base
 *
 * @return string template path
 * @since 0.0.1
 */

function growp_template_base() {
	return Theme_Wrapper::$base;
}

/**
 * Return layout class
 *
 * @return string
 * @since 0.0.1
 */
function growp_layout_class() {

	$class = '';

	$layouts['top']    = get_theme_mod( 'growp_layout_top', 'l-two-column' );
	$layouts['page']   = get_theme_mod( 'growp_layout_page', 'l-two-column' );
	$layouts['single'] = get_theme_mod( 'growp_layout_single', 'l-two-column' );

	if ( is_home() || is_front_page() ) {

		$class = $layouts['top'];

	} elseif ( is_archive() || is_page() ) {

		$class = $layouts['page'];

	} elseif ( is_single() ) {

		$class = $layouts['single'];

	}

	return $class;

}

/**
 * 静的なファイルのURLを出力
 *
 * @param string $filename ファイルのパス
 *
 * @return string  URL
 */
function e_assets_url( $filename = "" ) {
	echo esc_url( get_stylesheet_directory_uri() .  $filename );
}


function growp_get_previous_posts_link( $label = null ) {
	global $paged;

	if ( null === $label )
		$label = __( '&laquo; Previous Page' );

	if ( !is_single() && $paged > 1 ) {
		/**
		 * Filter the anchor tag attributes for the previous posts page link.
		 *
		 * @since 2.7.0
		 *
		 * @param string $attributes Attributes for the anchor tag.
		 */

		return '<a href="' . previous_posts( false ) . "\" class=\"c-button is-angle is-left\"><span>". preg_replace( '/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label ) .'</span></a>';
	}
}

/**
 * Return the next posts page link.
 *
 * @since 2.7.0
 *
 * @global int      $paged
 * @global WP_Query $wp_query
 *
 * @param string $label    Content for link text.
 * @param int    $max_page Optional. Max pages.
 * @return string|void HTML-formatted next posts page link.
 */
function growp_get_next_posts_link( $label = null, $max_page = 0 ) {
	global $paged, $wp_query;

	if ( !$max_page )
		$max_page = $wp_query->max_num_pages;

	if ( !$paged )
		$paged = 1;

	$nextpage = intval($paged) + 1;

	if ( null === $label )
		$label = __( 'Next Page &raquo;' );

	if ( !is_single() && ( $nextpage <= $max_page ) ) {
		/**
		 * Filter the anchor tag attributes for the next posts page link.
		 *
		 * @since 2.7.0
		 *
		 * @param string $attributes Attributes for the anchor tag.
		 */


		return '<a href="' . next_posts( $max_page, false ) . "\" class=\"c-button is-angle is-right\"><span>" . preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</span></a>';
	}
}
function growp_posts_navigation( $args = array() ) {
	$navigation = '';

	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages > 1 ) {
		$args = wp_parse_args( $args, array(
			'prev_text'          => __( 'Older posts' ),
			'next_text'          => __( 'Newer posts' ),
			'screen_reader_text' => __( 'Posts navigation' ),
		) );

		$next_link = growp_get_previous_posts_link( $args['next_text'] );
		$prev_link = growp_get_next_posts_link( $args['prev_text'] );

		if ( $prev_link ) {
			$navigation .= '<div class="c-navs-post__prev">' . $prev_link . '</div>';
		}

		if ( $next_link ) {
			$navigation .= '<div class="c-navs-post__next">' . $next_link . '</div>';
		}

		$navigation = _navigation_markup( $navigation, 'c-navs-post', " " );
	}

	echo $navigation;
}
