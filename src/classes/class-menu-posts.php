<?php
/**
 * メニューで作成した一覧を取得する
 */

// // 登録のサンプル
// add_action( "registered_taxonomy", function () {
//	new MenuPosts( 'sales_posts', 'セールス' );
//	new MenuPosts( 'featured_posts', '特集' );
// } );

class GROWP_MenuPosts
{

    // メニューのロケーション
    public $location = "";

    // メニューのオブジェクト
    public $menus = null;

    /**
     * 初期化
     *
     * @param $location
     * @param $name
     */
    public function __construct($location, $name)
    {

        $this->location = $location;
        $this->name     = $name;

        // メニューにセールス用を追加
        add_action('init', array($this, 'register_menu'));

        add_shortcode('menu_posts', array($this, 'register_shortcode'));
    }

    /**
     * メニューを登録
     * @return void
     */
    public function register_menu()
    {
        register_nav_menu($this->location, $this->name);
    }


    /**
     * 投稿一覧を取得
     * @return void
     */
    public function set_menus()
    {
        $locations = get_nav_menu_locations();

        if (empty($locations[$this->location])) {
            return false;
        }
        $menu = wp_get_nav_menu_object($locations[$this->location]);

        $this->menus = wp_get_nav_menu_items($menu->term_id, array('update_post_term_cache' => false));

    }

    /**
     * 投稿一覧を取得
     * @return void
     */
    public function get_menus()
    {
        $this->set_menus();
        $temp_menus = [];

        if ( ! is_array($this->menus)) {
            return false;
        }
        // ツリー構造のメニューを整形する
        foreach ($this->menus as $menu) {
            if ($menu->menu_item_parent === "0") {
                $temp_menus[$menu->ID] = $menu;
            } else {
                if (empty($temp_menus[$menu->menu_item_parent]->subposts)) {
                    $temp_menus[$menu->menu_item_parent]->subposts = [];
                }
                $temp_menus[$menu->menu_item_parent]->subposts[$menu->ID] = $menu;
            }
        }

        return $temp_menus;
    }

    /**
     * レンダリング
     * @return void
     */
    public function render()
    {
        $this->set_menus();
        if (empty($this->menus)) {
            return false;
        }
        foreach ($this->menus as $menu) {
            global $post;
            $post = get_post($menu->object_id);
            setup_postdata($post);
            get_template_part("templates/content");
        }
    }

    /**
     * ショートコードとして呼び出せるように
     *
     * @param string $content
     * @param array $attrs
     *
     * @return string
     */
    public function register_shortcode($content = "", $attrs = array())
    {
        ob_start();
        $this->render();
        $content = ob_get_contents();
        ob_clean();

        return $content;
    }
}



// 登録のサンプル
// add_action( "registered_taxonomy", function () {
// 	new MenuPosts( 'global_nav', 'グローバルナビゲーション' );
// 	new MenuPosts( 'footer_nav', 'フッターナビゲーション' );
// } );
