<?php
/**
 * モックの作成
 */
require_once dirname(__FILE__) . "/class-create-mock.php";
require_once dirname(__FILE__) . "/frontandhome-mock.php";
require_once dirname(__FILE__) . "/mw-wp-form-mock.php";
require_once dirname(__FILE__) . "/privacy-policy-mock.php";
require_once dirname(__FILE__) . "/sitemap-mock.php";

add_action("after_switch_theme", "growp_mock_init");
function growp_mock_init()
{
    new GROWP_Sitemap_Mock();
    new GROWP_Mw_Wp_Form_Mock();
    new GROWP_FrontAndHome_Mock();
    new GROWP_Privacy_Policy_Mock();
}
