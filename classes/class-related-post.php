<?php
/**
 * 関連記事クラス
 */

class Epigone_Related_Post {

	/**
	 * 源氏亜
	 * @var array|null|WP_Post
	 */
	public $current_post = null;

	/**
	 * 取得する記事件数
	 * @var int
	 */
	public $posts_per_page = 4;

	/**
	 * 取得するカテゴリ
	 * @var bool|int
	 */
	public $current_category = 0;

	/**
	 * タイトルを表示するか
	 * @var bool
	 */
	public $show_title = true;

	/**
	 * ラップするか
	 * @var bool
	 */
	public $show_wrapper = true;

	/**
	 * 初期化
	 *
	 */
	public function __construct(){

		$this->current_post = $this->get_current_post();
		$this->current_category = $this->get_category();
		$this->related_posts = $this->retrieve_posts();
		$this->generate();
	}

	/**
	 * 現在の投稿を取得
	 */
	public function get_current_post(){

		$post_id = get_the_ID();
		return get_post($post_id);

	}


	/**
	 * カテゴリを取得し、IDを返す
	 */
	public function get_category(){
		$category = get_the_category( $this->current_post->ID );

		if ( isset( $category[0] ) ){
			return $category[0]->term_id;
		}

		return false;
	}


	/**
	 * 関連記事を検索
	 */
	public function retrieve_posts(){
		$args = array(
			'post_type' => get_post_type($this->current_post->ID),
			'post_status' => 'publish',
			'posts_per_page' => $this->posts_per_page,
			'cat' => $this->current_category,
		);
		$posts = get_posts( $args );

		return $posts;
	}


	/**
	 * HTMLに変換
	 */
	public function generate(){

		if ( is_wp_error( $this->related_posts ) ){
			return false;
		}

		if ( $this->show_wrapper ){
			echo '<div class="relatedpost clearfix">';
		}

		if ( $this->show_title ){
			echo sprintf( '<h2 class="relatedpost__title entry__title">%s</h2>', __( 'Related Posts', 'epigone' ) );
		}

		global $post;
		?>
		<div class="row">
		<?php
		foreach ( $this->related_posts as $r_post ) {
			$post = $r_post;
			setup_postdata( $post ); ?>
				<?php get_template_part( 'templates/content-related-post' ); ?>
			<?php
		}
		?>
		</div>
		<?php
		wp_reset_postdata();

		if ( $this->show_wrapper ){
			echo '</div>';
		}
	}

}


function epigone_related_post(){
	if ( 'true' ===  get_theme_mod( 'single_related_post', 'true' ) ) {
		new Epigone_Related_Post();
	}
}
