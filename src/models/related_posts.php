<?php
/**
 * Class gm_related_posts
 */
class gm_related_posts {

	/**
	 * @var string 投稿タイプ
	 */
	public $post_type = "post";

	/**
	 * @var int 呼び出す記事数
	 */
	public $posts_per_page = 4;

	/**
	 * 関連として利用するタクソノミー
	 * @var array
	 */
	public $source_taxonomies = [
		'category'
	];

	public $tax_query = [
		'relation' => 'AND'
	];

	public $query_args = [];

	public $queryObject = null;

	public $post_id = 0;

	/**
	 * gm_related_posts constructor.
	 *
	 * @param int $post_id
	 */
	public function __construct( $post_id = 0 ) {
		$this->set_post_id( $post_id );
		$this->set_tax_query();
		$this->set_wp_query();
	}

	/**
	 * 投稿IDを取得
	 *
	 * @param $post_id
	 */
	public function set_post_id( $post_id ) {
		if ( ! $post_id ) {
			$this->post_id = get_the_ID();
		} else {
			$this->post_id = $post_id;
		}
	}

	/**
	 * tax_query値をセット
	 */
	public function set_tax_query() {
		foreach ( $this->source_taxonomies as $taxonomy ) {
			$_terms    = get_the_terms( $this->post_id, $taxonomy );
			$_term_ids = [];
			foreach ( $_terms as $t ) {
				$_term_ids[] = $t->term_id;
			}
			$this->tax_query[] = [
				'taxonomy' => $taxonomy,
				'terms'    => $_term_ids,
				'field'    => 'term_id'
			];
		}
	}

	/**
	 * WP_Queryをセット
	 */
	public function set_wp_query() {
		$this->query_args              = [
			'post_type'      => $this->post_type,
			'posts_per_page' => $this->posts_per_page,
			'post__not_in'   => [ $this->post_id ],
		];
		$this->query_args["tax_query"] = $this->tax_query;
		$this->queryObject             = new WP_Query( $this->query_args );
	}

	public function get_query() {
		return $this->queryObject;
	}

	public function have_posts() {
		return $this->queryObject->have_posts();
	}

	public function the_post() {
		$this->queryObject->the_post();

		return $this;
	}

}
