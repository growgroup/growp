<?php
/**
 * Class TestSitemap
 *
 * @package growp
 */

/**
 * Sample test case.
 */
class TestMock extends WP_UnitTestCase
{


    function test_frontandhomemock()
    {
        delete_option("frontandhome-mock");
        $frontandhome = new GROWP_FrontAndHome_Mock();
        $top_id       = $frontandhome->get_insert_id("top");
        $home_id      = $frontandhome->get_insert_id("home");

        return ($this->assertEquals(get_option('page_on_front'),
                $top_id) && $this->assertEquals(get_option('page_for_posts'), $home_id));
    }


    function test_mw_wp_form_mock()
    {
        delete_option("mw-wp-form-mock");
        $mwmock        = new GROWP_Mw_Wp_Form_Mock();
        $contact_id    = $mwmock->get_insert_id("contact");
        $confirm_id    = $mwmock->get_insert_id("confirm");
        $complete_id   = $mwmock->get_insert_id("complete");
        $mw_wp_form_id = $mwmock->get_insert_id("mw-wp-form-contact");

        return (
            $this->assertEquals(get_the_title($mw_wp_form_id), "お問い合わせフォーム") &&
            $this->assertEquals(get_the_title($contact_id), "お問い合わせ") &&
            $this->assertEquals(get_the_title($confirm_id), "お問い合わせ内容のご確認") &&
            $this->assertEquals(get_the_title($complete_id), "お問い合わせありがとうございます")
        );
    }

    function test_privacypolicy()
    {
        delete_option("privacy-policy-mock");
        $pp    = new GROWP_Privacy_Policy_Mock();
        $pp_id = $pp->get_insert_id("privacy-policy");

        return (
        $this->assertEquals(get_the_title($pp_id), "個人情報保護方針")
        );
    }

    function test_sitemap()
    {
        delete_option("sitemap-mock");
        $pp    = new GROWP_Sitemap_Mock();
        $pp_id = $pp->get_insert_id("sitemap");
        return (
        $this->assertEquals(get_the_title($pp_id), "サイトマップ")
        );
    }
}
