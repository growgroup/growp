<?php
/**
 * サイドバーの登録
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */


/**
 * dynamic_sidebar のラッパー関数
 * インデクスに対して動的に操作をするために必要。
 * 基本的にテーマ内でウィジェットエリアを設定する時にはこの関数を利用する
 *
 * @param $index
 * @return mixed
 */
function epigone_dynamic_sidebar( $index ){
	return dynamic_sidebar( $index );
}


/**
 * サイドバーの登録
 * @return void
 */
function epigone_pc_sidebar() {

	register_sidebar( array(
		'name'          => __( 'Sidebar Primary', 'epigone' ),
		'id'            => 'sidebar-primary',
		'before_widget' => '<div class="widget widget-sidebar %1$s %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Header Primary Area', 'epigone' ),
		'id'            => 'header-primary',
		'before_widget' => '<div class="widget widget-header %1$s %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Main Visual', 'epigone' ),
		'id'            => 'main-visual',
		'before_widget' => '<div class="widget widget-header %1$s %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Above Contents', 'epigone' ),
		'id'            => 'content-primary',
		'before_widget' => '<div class="widget widget-content %1$s %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Under Content', 'epigone' ),
		'id'            => 'content-secondary',
		'before_widget' => '<div class="widget widget-content %1$s %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Under title (single post)', 'epigone' ),
		'id'            => 'single-under-title',
		'before_widget' => '<div class="widget widget-content %1$s %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Primary', 'epigone' ),
		'id'            => 'footer-primary',
		'before_widget' => '<div class="widget large-3 columns widget-footer %1$s %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title footer-title">',
		'after_title'   => '</h3>',
	) );

}

add_action( 'widgets_init', 'epigone_pc_sidebar' );
