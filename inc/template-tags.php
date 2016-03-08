<?php
/**
 * このテーマオリジナルのテンプレートタグ
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */

/**
 * ページナビゲーションを出力
 *
 * @return void
 */
function epigone_paging_nav() {

	$defaults = array(
		'show_all' => false,
		'prev_next' => true,
		'prev_text' => __('&laquo; Previous'),
		'next_text' => __('Next &raquo;'),
		'end_size' => 1,
		'mid_size' => 2,
		'type' => 'list',
		'add_args' => array(), // array of query args to add
		'add_fragment' => '',
		'before_page_number' => '',
		'after_page_number' => ''
	);
	echo "<div class='text-center'>";
	the_posts_pagination($defaults);
	echo "</div>";
}


/**
 * 投稿ナビゲーションを出力
 *
 * @return void
 */
function epigone_post_nav() {

	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text navigation-title"><?php _e( 'Post navigation', 'epigone' ); ?></h1>
		<div class="nav-links">
			<?php
			previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'epigone' ) );
			next_post_link( '<div class="nav-next">%link</div>', _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link', 'epigone' ) );
			?>
		</div>
		<!-- .nav-links -->
	</nav><!-- .navigation -->		<?php
}



/**
 * 投稿メタ情報を出力
 *
 * @return void
 */
function epigone_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s"> %2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) )
	);


	$posted_string = __( '<i class="fa fa-calendar-o"></i> <span class="posted-on">Posted on %1$s</span> ', 'epigone' );
	$author_string = __( '<i class="fa fa-user"></i><span class="byline"> by %1$s</span>', 'epigone' );

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


function epigone_categorized_blog() {
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
 * Flush out the transients used in epigone_categorized_blog.
 */
function epigone_category_transient_flusher() {
	delete_transient( 'all_the_cool_cats' );
}

add_action( 'edit_category', 'epigone_category_transient_flusher' );
add_action( 'save_post', 'epigone_category_transient_flusher' );


/**
 * パンくずを出力
 *
 * @see classes/class-breadcrumbs.php
 * @return void
 */
function epigone_breadcrumb() {

	$templates = array(
		'before'   => '<nav><ul class="breadcrumbs">',
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
	$breadcrumb = new Epigone_Breadcrumbs( $templates, $options );

}

/**
 * For function for outputting pagination
 *
 * @param boolean $output
 *
 * @return void
 */
function epigone_pagination( $output = true ) {

	global $wp_query, $wp_rewrite;

	$base   = trailingslashit( get_pagenum_link( 1 ) ) . '%_%';
	$format = ( $wp_rewrite->using_permalinks() ) ? 'page/%#%' : '?paged=%#%';
	$args   = array(
		'base'      => $base,
		'format'    => $format,
		'current'   => max( 1, get_query_var( 'paged' ) ),
		'total'     => $wp_query->max_num_pages,
		'prev_next' => true,
		'prev_text' => '&larr;' . __( 'Previous', 'epigone' ),
		'next_text' => __( 'Next', 'epigone' ) . '&rarr;',
	);

	$before     = apply_filters( 'epigone_paginavi_before', '<nav class="pagination primary-links">' );
	$pagination = paginate_links( $args );
	$after      = apply_filters( 'epigone_paginavi_after', '</nav>' );

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
function epigone_get_header() {

	$header_style = get_theme_mod( 'header_style', '' );

	get_template_part( 'modules/header' );

}


/**
 * Social Icon
 */
add_action( 'get_header', 'epigone_social_icon' );

function epigone_social_icon() {

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
 * epigone_template_path
 *
 * @return template path
 * @since 0.0.1
 */
function epigone_template_path() {
	return Theme_Wrapper::$main_template;
}

/**
 * epigone_template_base
 *
 * @return string template path
 * @since 0.0.1
 */

function epigone_template_base() {
	return Theme_Wrapper::$base;
}

/**
 * Return layout class
 *
 * @return string
 * @since 0.0.1
 */
function epigone_layout_class() {

	$class = '';

	$layouts['top']    = get_theme_mod( 'epigone_layout_top', 'l-two-column' );
	$layouts['page']   = get_theme_mod( 'epigone_layout_page', 'l-two-column' );
	$layouts['single'] = get_theme_mod( 'epigone_layout_single', 'l-two-column' );

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
function e_assets_url( $filename ) {
	echo esc_url( get_stylesheet_directory_uri() . '/assets/' .  $filename );
}
