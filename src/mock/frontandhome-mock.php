<?php
/**
 * トップページとブログのトップページを自動作成する
 */
class GROWP_FrontAndHome_Mock extends GROWP_Create_Mock
{

    public function __construct()
    {
        parent::__construct("frontandhome-mock");
    }

    protected function run()
    {
        $this->insert_post(array(
            'post_title'   => "トップページ",
            'post_type'    => 'page',
            'post_content' => "",
            'post_name'    => "top",
            'post_status'  => "publish",
        ));


        $this->insert_post(array(
            'post_title'   => "ブログ",
            'post_type'    => 'page',
            'post_content' => "",
            'post_name'    => "home",
            'post_status'  => "publish",
        ));


        $top_id  = $this->get_insert_id('top');
        $home_id = $this->get_insert_id('home');

        update_option('page_on_front', $top_id);
        update_option('page_for_posts', $home_id);
        update_option('show_on_front', "page");
    }
}

new GROWP_FrontAndHome_Mock();
