<?php
/**
 * メニューで作成した一覧を取得する
 */

// // 登録のサンプル
// add_action( "registered_taxonomy", function () {
//	new MenuManagePosts( 'sales_posts', 'セールス' );
//	new MenuManagePosts( 'featured_posts', '特集' );
// } );

class MenuPosts {

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
	public function __construct( $location, $name ) {

		$this->location = $location;
		$this->name     = $name;

		// メニューにセールス用を追加
		add_action( 'init', array( $this, 'register_menu' ) );

		add_shortcode( 'menu_posts', array( $this, 'register_shortcode' ) );
	}

	/**
	 * メニューを登録
	 * @return void
	 */
	public function register_menu() {
		register_nav_menu( $this->location, $this->name );
	}


	/**
	 * 投稿一覧を取得
	 * @return void
	 */
	public function set_menus() {
		$locations   = get_nav_menu_locations();
		$menu        = wp_get_nav_menu_object( $locations[ $this->location ] );
		$this->menus = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );
	}

	/**
	 * レンダリング
	 * @return void
	 */
	public function render() {
		$this->set_menus();
		if ( empty( $this->menus ) ) {
			return false;
		}
		foreach ( $this->menus as $menu ) {
			global $post;
			$post = get_post( $menu->object_id );

			setup_postdata( $post );
			get_template_part( "templates/content" );
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
	public function register_shortcode( $content = "", $attrs = array() ) {
		ob_start();
		$this->render();
		$content = ob_get_contents();
		ob_clean();

		return $content;
	}
}



