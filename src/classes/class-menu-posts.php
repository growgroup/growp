<?php
/**
 * メニューで作成した一覧を取得する
 */

// // 登録のサンプル
// add_action( "registered_taxonomy", function () {
//	new MenuPosts( 'sales_posts', 'セールス' );
//	new MenuPosts( 'featured_posts', '特集' );
// } );

class TreeNode
{
    /** 本オブジェクトの親要素 */
    private $parent;

    /** 本オブジェクトの子要素 */
    private $children = [];

    /** 名前 */
    private $name;

    /** 値 */
    public $value;

    /**
     * コンストラクタ
     *
     * @param string $name 名前(親の場合は省略可能)
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * 子を追加する
     * すでに同名のものがある場合は追加しない
     *
     * @param string $name 追加する子要素
     *
     * @return TreeNode 追加した子要素
     */
    public function append($name)
    {
        $child = $this->child($name);
        if ($child === false) {
            $child                        = new TreeNode($name);
            $child->parent                = $this;
            $this->children[$child->name] = $child;
        }

        return $child;
    }

    /**
     * 指定した子を削除する
     *
     * @param string $name 削除する子要素
     *
     * @return TreeNode 自分自身
     */
    public function remove($name)
    {
        if ($this->has($name)) {
            unset($this->children[$name]);
        }

        return $this;
    }

    /**
     * 指定した名前の子要素を取得する
     *
     * @param string $name 名前(省略時は全部)
     *
     * @return TreeNode | array | boolean
     */
    public function child($name = null)
    {
        // 引数省略時は全部
        if (is_null($name)) {
            return $this->children;
        }

        // 引数指定時は指定した名前の子要素
        if ( ! $this->has($name)) {
            return false;
        }

        return $this->children[$name];
    }

    /**
     * 指定した名前の子要素があるかどうか
     * 省略時は子要素自体があるかどうか
     *
     * @param string $name 名前
     *
     * @return boolean
     */
    public function has($name = null)
    {
        // 引数省略時
        if (is_null($name)) {
            return (0 < count($this->children));
        }

        return isset($this->children[$name]);
    }

    /**
     * 名前を取得
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * 親要素を取得
     *
     * @return TreeNode 親要素
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * パスの配列を取得する
     * valueは含まれません
     * 例: [NULL, 'HOGE', 'FUGA']
     *
     * @return array
     */
    public function path()
    {
        $result = [$this->name];

        $parent = $this->parent;
        while ($parent != null) {
            $result[] = $parent->name;
            $parent   = $parent->parent;
        }
        $result = array_reverse($result);

        return $result;
    }

}

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
        $temp_menus = array();

        if ( ! is_array($this->menus)) {
            return false;
        }

        // ツリー構造のメニューを整形する
        foreach ($this->menus as $menu) {
            $parse_menus[$menu->menu_item_parent][] = $menu;
        }
        function createTree(&$list, $parent)
        {
            $tree = array();
            foreach ($parent as $k => $l) {
                if (isset($list[$l->ID])) {
                    $l->children = createTree($list, $list[$l->ID]);
                }
                $tree[] = $l;
            }
            return $tree;
        }
        $parse_menus = createTree($parse_menus, $parse_menus[0]);
        return $parse_menus;
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
