<?php
/**
 * Class TestSitemap
 *
 * @package growp
 */

/**
 * Sample test case.
 */
class TestSitemap extends WP_UnitTestCase
{

    /**
     * A single example test.
     */
    function test_sitemap()
    {
        ob_start();
        GROWP_Sitemap::output();
        $contents = ob_get_clean();
        return $this->assertEquals('<div class="c-sitemap"><div class="c-sitemap__title">Test Blog</div><ul></ul></div><div class="c-sitemap"><div class="c-sitemap__title">Posts</div><ul></ul></div><div class="c-sitemap"><div class="c-sitemap__title">Categories</div><ul><li class="cat-item-none">No categories</li></ul></div><div class="c-sitemap"><div class="c-sitemap__title">Tags</div><ul><li class="cat-item-none">No categories</li></ul></div>',$contents);
    }
}
