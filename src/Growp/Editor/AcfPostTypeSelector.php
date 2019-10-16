<?php

namespace Growp\Editor;

use acf_field;
use function explode;
use function is_array;

class AcfPostTypeSelector extends acf_field {
	const SELECTOR_TYPE_SELECT     = 0;
	const SELECTOR_TYPE_RADIO      = 1;
	const SELECTOR_TYPE_CHECKBOXES = 2;
	// vars
	var $settings,
		$defaults;

	public function __construct() {
		// vars
		$this->name     = 'post_type_selector';
		$this->label    = __( '投稿タイプ' );
		$this->category = __( "関連", 'acf' ); // Basic, Content, Choice, etc
		$this->defaults = array(
			'select_type' => self::SELECTOR_TYPE_RADIO,
		);
		// do not delete!
		parent::__construct();
		// settings
		$this->settings = array(
			'path'    => plugin_dir_path( __FILE__ ),
			'dir'     => plugin_dir_url( __FILE__ ),
			'version' => '1.0.0'
		);
	}

	public function render_field_settings( $field ) {

		$field = array_merge( $this->defaults, $field );
		acf_render_field_setting( $field, array(
			'label'        => __( "入力タイプ", 'acf' ),
			'instructions' => '投稿タイプの選択方法を選んでください',
			'type'         => 'select',
			'name'         => 'select_type',
			'choices'      => array(
				AcfPostTypeSelector::SELECTOR_TYPE_SELECT     => __( 'セレクトボックス' ),
				AcfPostTypeSelector::SELECTOR_TYPE_RADIO      => __( 'ラジオボックス' ),
				AcfPostTypeSelector::SELECTOR_TYPE_CHECKBOXES => __( 'チェックボックス' ),
			)
		) );
	}

	public function render_field( $field ) {
		$field      = array_merge( $this->defaults, $field );
		$post_types = acf_get_pretty_post_types();
		$post_types = apply_filters( 'post_type_selector_post_types', $post_types, $field );
		if ( ! $field['required'] ) {
			$post_types = [ '' => __( "なし", 'acf' ) ] + $post_types;
		}
		if ( $field["default_value"] ) {
			$field['select_type'] = 2;
		}
		$checked = array();
		switch ( $field['select_type'] ) {
			case AcfPostTypeSelector::SELECTOR_TYPE_SELECT:
				echo '<select id="' . $field['name'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '">';
				$checked[ $field['value'] ] = 'selected="selected"';
				foreach ( $post_types as $post_type => $post_type_label ) {
					echo '<option ' . ( isset( $checked[ $post_type ] ) ? $checked [ $post_type ] : null ) . ' value="' . $post_type . '">' . $post_type_label . '</option>';
				}
				echo '</select>';
				break;
			case AcfPostTypeSelector::SELECTOR_TYPE_RADIO:
				echo '<ul class="radio_list radio horizontal">';
				$checked[ $field['value'] ] = 'checked="checked"';
				foreach ( $post_types as $post_type => $post_type_label ) {
					?>

					<li>
						<label><input type="radio" <?php echo ( isset( $checked[ $post_type ] ) ) ? $checked[ $post_type ] : null; ?> class="<?php echo $field['class']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $post_type; ?>"> <?php echo $post_type_label; ?>
						</label></li>

					<?php
				}
				echo '</ul>';
				break;
			case AcfPostTypeSelector::SELECTOR_TYPE_CHECKBOXES:
				echo '<ul class="checkbox_list checkbox">';
				if ( ! empty( $field['value'] ) ) {
					if ( ! is_array( $field['value'] ) ) {
						$field['value'] = explode( ",", $field['value'] );
					}
					foreach ( $field['value'] as $val ) {
						$checked[ $val ] = 'checked="checked"';
					}
				}
				foreach ( $post_types as $post_type => $post_type_label ) {
					?>

					<li>
						<label><input type="checkbox" <?php echo ( isset( $checked[ $post_type ] ) ) ? $checked[ $post_type ] : null; ?> class="<?php echo $field['class']; ?>" name="<?php echo $field['name']; ?>[]" value="<?php echo $post_type; ?>"><?php echo $post_type_label; ?>
						</label></li>
					<?php
				}
				echo '</ul>';
				break;
		}
	}

}
