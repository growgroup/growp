<?php

/**
 * WP Admin Ui Customize のデフォルト設定を読み込む
 */
class GROWP_Tinymce_Advanced_Mock extends GROWP_Create_Mock
{

    public function __construct()
    {
        parent::__construct("tinymce-advanced-mock");
    }


    protected function run()
    {
        $options = array(
            'tadv_settings'       => 'a:6:{s:9:"toolbar_1";s:184:"formatselect,fontsizeselect,backcolor,forecolor,underline,strikethrough,bold,italic,blockquote,bullist,numlist,alignleft,aligncenter,alignright,link,unlink,table,undo,redo,removeformat";s:9:"toolbar_2";s:0:"";s:9:"toolbar_3";s:0:"";s:9:"toolbar_4";s:0:"";s:7:"options";s:27:"menubar,advlist,contextmenu";s:7:"plugins";s:25:"table,advlist,contextmenu";}',
            'tadv_admin_settings' => 'a:2:{s:7:"options";s:21:"no_autop,paste_images";s:16:"disabled_editors";s:0:"";}',
            'tadv_version' => 's:4:"4000"',
        );
        foreach ($options as $key => $value) {
            $_temp = unserialize($value);

            update_option($key, $_temp);
        }
    }
}
