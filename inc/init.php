<?php
/**
 * 必要なファイルを読み込む
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================''
 */

/**
 * 読み込むファイルを配列として渡す
 */
$include_files = array(
	array( 'inc/setup.php' ),
	array( 'inc/template-tags.php' ),
	array( 'inc/extras.php' ),
	array( 'inc/jetpack.php' ),
	array( 'inc/scripts.php' ),
	array( 'inc/sidebar.php' ),
	array( 'inc/comment.php' ),
	array( 'inc/tgmpa.php' ),
	array( 'classes/class-theme-wrapper.php' ),
	array( 'classes/class-related-post.php' ),
	array( 'classes/class-breadcrumbs.php' ),
	array( 'classes/class-walker-nav.php' ),
	array( 'classes/class-walker-comment.php' ),
	array( 'classes/class-post-type.php' ),
);

/**
 * 読み込むファイルにフィルターを設ける
 */
$include_files = apply_filters( 'epigone_init_files', $include_files );

foreach ( $include_files as $key => $files ) {
	if ( $filename = locate_template( $files, false ) ) {
		load_template( $filename, true );
	}
}
