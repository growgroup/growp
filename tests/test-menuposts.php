<?php
/**
 * Class TestMenuPosts
 *
 * @package growp
 */

/**
 * Sample test case.
 */
class TestMenuPosts extends WP_UnitTestCase
{

	function setup(){

	}

	function test_frontandhomemock()
	{

		$menuposts = new GROWP_MenuPosts("global-nav", "グローバルナビゲーション");
		echo "<pre>";
		var_dump($menuposts->get_menus());
		echo "</pre>";

		return ($this->assertEquals(get_option("test_menu"),"tet"));
	}


}
