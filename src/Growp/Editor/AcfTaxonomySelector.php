<?php

namespace Growp\Editor;

use acf_field;

class AcfTaxonomySelector extends acf_field {

	public function __construct() {
		$this->name     = 'taxonomy_selector';
		$this->label    = __( 'タクソノミー選択' );
		$this->category = 'relational';
		$this->defaults = [
			'field_type'    => 'radio',
			'layout'        => 'vertical',
			'optgroup'      => false,
			'multiple'      => 0,
			'allow_null'    => 0,
			'return_format' => 'id',
		];
		parent::__construct();

	}

	/**
	 * Create the field's settings.
	 *
	 * @type    action
	 *
	 * @param array $field The field being edited.
	 *
	 * @return  void
	 */
	public function render_field_settings( $field ) {
		$field = array_merge( $this->defaults, $field );
		acf_render_field_setting( $field, [
			'label'        => __( '外観', 'acf' ),
			'instructions' => __( '入力フィールドのタイプを選択してください', 'acf' ),
			'type'         => 'select',
			'name'         => 'field_type',
			'optgroup'     => true,
			'choices'      => [
				__( '複数', 'acf' ) => [
					'checkbox'     => __( 'チェックボックス', 'acf' ),
					'multi_select' => __( 'マルチセレクトボックス', 'acf' )
				],
				__( '単一', 'acf' ) => [
					'radio'  => __( 'ラジオボタン', 'acf' ),
					'select' => __( 'セレクトボックス', 'acf' )
				]
			]
		] );
		acf_render_field_setting( $field, [
			'label'        => __( '空を許可するか', 'acf' ),
			'instructions' => '',
			'type'         => 'radio',
			'name'         => 'allow_null',
			'layout'       => 'horizontal',
			'choices'      => [
				1 => __( 'Yes', 'acf' ),
				0 => __( 'No', 'acf' )
			]
		] );
		acf_render_field_setting( $field, [
			'label'        => __( '返値', 'acf' ),
			'instructions' => '',
			'type'         => 'radio',
			'name'         => 'return_format',
			'layout'       => 'horizontal',
			'choices'      => [
				'id'     => __( 'Taxonomy Name', 'acf' ),
				'object' => __( 'Taxonomy Object', 'acf' )
			],
		] );
	}

	/**
	 * Create the HTML interface for your field
	 *
	 * @type    action
	 *
	 * @param array $field The field being rendered.
	 *
	 * @return  void
	 */
	public function render_field( $field ) {
		$field = array_merge( $this->defaults, $field );
		$args  = [
			'public' => true
		];
		/**
		 * Filters the arguments for retrieving a list of registered taxonomy objects.
		 *
		 * @param array $args Array of arguments to match against the taxonomy objects.
		 * @param array $field An array holding all the field's data.
		 */
		$args     = apply_filters( 'acf/fields/taxonomy_selector/args', $args, $field );
		$args     = apply_filters( "acf/fields/taxonomy_selector/args/name={$field['_name']}", $args, $field );
		$args     = apply_filters( "acf/fields/taxonomy_selector/args/key={$field['key']}", $args, $field );
		$excluded = [ 'post_format', 'nav_menu', 'link_category' ];
		/**
		 * Filters the list taxonomies to exclude.
		 *
		 * @param string[] $excluded Array of taxonomy names.
		 * @param array $field An array holding all the field's data.
		 */
		$excluded   = apply_filters( 'acf/fields/taxonomy_selector/excluded_taxonomies', $excluded, $field );
		$excluded   = apply_filters( "acf/fields/taxonomy_selector/excluded_taxonomies/name={$field['_name']}", $excluded, $field );
		$excluded   = apply_filters( "acf/fields/taxonomy_selector/excluded_taxonomies/key={$field['key']}", $excluded, $field );
		$taxonomies = get_taxonomies( $args, 'objects' );
		foreach ( $taxonomies as $taxonomy ) {
			if ( in_array( $taxonomy->name, $excluded ) ) {
				continue;
			}
			$field['choices'][ $taxonomy->name ] = $taxonomy->labels->name;
		}
		switch ( $field['field_type'] ) {
			case 'select':
			case 'multi_select':
				$field['type']     = 'select';
				$field['multiple'] = intval( 'multi_select' === $field['field_type'] );
				break;
			case 'radio':
			case 'checkbox':
				$field['type']     = $field['field_type'];
				$field['multiple'] = intval( 'checkbox' === $field['field_type'] );
				break;
		}
		acf_render_field( $field );
	}


	public function update_value( $value, $post_id, $field ) {
		if ( is_array( $value ) ) {
			$value = array_filter( $value );
		}

		return $value;
	}


	public function format_value( $value, $post_id, $field ) {
		if ( empty( $value ) ) {
			return $value;
		}
		$value = acf_get_array( $value );
		if ( $field['return_format'] == 'object' ) {
			foreach ( $value as &$val ) {
				$tax = get_taxonomy( $val );
				$val = ( $tax ? $tax : null );
			}
		}
		$value = array_filter( $value );
		if ( in_array( $field['field_type'], [ 'select', 'radio' ] ) ) {
			$value = array_shift( $value );
		}

		return $value;
	}
}
