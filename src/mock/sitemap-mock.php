<?php
/**
 * サイトマップページの作成
 */
class GROWP_Sitemap_Mock extends GROWP_Create_Mock
{

    public function __construct()
    {
        parent::__construct("sitemap-mock");
    }

    protected function run()
    {
        $this->insert_post(array(
            'post_title'   => "サイトマップ",
            'post_type'    => 'page',
            'post_content' => " ",
            'post_name'    => "sitemap",
            'post_status'  => "publish",
        ));
    }
}
