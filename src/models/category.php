<?php

class gm_category extends gm_base_term {

	public $taxonomy = "category";

	public static function get_all_items() {
		$terms      = get_terms( [
			"taxonomy"   => "category",
			'hide_empty' => false
		] );
		$repository = [];
		foreach ( $terms as $term ) {
			$repository[] = new gm_category( $term->term_id );
		}
		return $repository;
	}
}
