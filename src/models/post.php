<?php

class gm_post extends gm_base_post {

	public $post_type = "post";

	public $fields = [];

	public $taxonomies = [
		'category',
		'post_tags',
	];

	public $terms = [];

	public function get_list_image() {
		return $this->get_field_value( "product_image" );
	}

	/**
	 * サムネイル画像のURLを取得
	 *
	 * @param string $size
	 *
	 * @return false|string
	 */
	public function get_thumbnail_url( $size = "full" ) {
		if ( ! get_post_thumbnail_id( $this->post_id ) ) {
			$first_image = $this->get_first_image();
			if ( $first_image ) {
				return $first_image;
			}
		}

		return GTag::get_thumbnail_url( $this->post_id, $size );
	}
}
