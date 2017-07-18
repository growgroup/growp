<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Black_Studio_Tinymce_Widget/
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';


/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	register_theme_directory( dirname( __FILE__ ) . '/../../' );
	switch_theme( 'growp' );
	require_once dirname( dirname( __FILE__ ) ) . '/functions.php';
	search_theme_directories();

}

tests_add_filter( 'after_setup_theme', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
