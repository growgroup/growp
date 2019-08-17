<?php

namespace Growp\Editor;


class AcfCodeField extends \acf_field {
	public function __construct() {
		$this->name     = 'acf_code_field';
		$this->label    = __( 'コードエリアフィールド', 'acf-code-field' );
		$this->category = 'Code tools';
		$this->defaults = array(
			'mode'  => 'htmlmixed',
			'theme' => 'default',
		);
		$this->l10n     = array(
			'error' => __( 'Error! Please enter a higher value', 'acf-code-field' ),
		);
		parent::__construct();
	}

	public function render_field_settings( $field ) {
		acf_render_field_setting( $field, array(
			'label'        => __( 'Default Value', 'acf' ),
			'instructions' => __( 'Appears when creating a new post', 'acf' ),
			'type'         => 'textarea',
			'name'         => 'default_value',
		) );
		acf_render_field_setting( $field, array(
			'label'        => __( 'Placeholder Text', 'acf' ),
			'instructions' => __( 'Appears within the input', 'acf' ),
			'type'         => 'text',
			'name'         => 'placeholder',
		) );
		acf_render_field_setting( $field, array(
			'label'        => __( 'Editor mode', 'acf' ),
			'instructions' => __( '', 'acf' ),
			'type'         => 'select',
			'name'         => 'mode',
			'choices'      => array(
				'htmlmixed'               => __( "HTML Mixed", 'acf' ),
				'text/javascript'         => __( "JavaScript", 'acf' ),
				'text/html'               => __( "HTML", 'acf' ),
				'text/css'                => __( "CSS", 'acf' ),
				'application/x-httpd-php' => __( "PHP", 'acf' ),
				'text/x-twig'             => __( "Twig", 'acf' ),
				'text/x-scss'             => __( "Scss", 'acf' ),
				'text/x-sass'             => __( "Sass", 'acf' ),
			),
		) );

	}

	function render_field( $field ) {
		$dir = plugin_dir_url( __FILE__ );
//			$safe_slug = str_replace( "-", "_", $field['id'] );
		$o     = array( 'id', 'class', 'name', 'placeholder', 'mode', 'theme' );
		$e     = '';
		$attrs = array();
		foreach ( $o as $k ) {
			if ( isset( $field[ $k ] ) ) {
				$attrs[ $k ] = $field[ $k ];
			}

		}

		$attrs['class'] = 'acf-code-field-box';

		$e .= '<textarea ' . acf_esc_attrs( $attrs ) . ' >';
		$e .= esc_textarea( $field['value'] );
		$e .= '</textarea>';
		echo $e;
	}


	function input_admin_enqueue_scripts() {
		if ( version_compare( $GLOBALS['wp_version'], '4.9', '>=' ) ) {
			wp_enqueue_script( 'wp-codemirror' );
			wp_enqueue_style( 'wp-codemirror' );
			wp_enqueue_script( 'csslint' );
			wp_enqueue_script( 'jshint' );
			wp_enqueue_script( 'jsonlint' );
			wp_enqueue_script( 'htmlhint' );
			wp_enqueue_script( 'htmlhint-kses' );
			//Alias wp.CodeMirror to CodeMirror
			wp_add_inline_script( 'wp-codemirror', 'window.CodeMirror = wp.CodeMirror;' );
		}
	}
}
