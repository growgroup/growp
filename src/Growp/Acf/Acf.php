<?php

namespace Growp\Acf;

use function acf_add_local_field_group;
use function acf_register_block_type;
use function add_action;
use function get_class;
use function get_class_methods;
use function get_post_types;
use function json_encode;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Acf {

	protected static $instance = null;

	private function __construct() {
		$self = $this;
		add_action( 'acf/init', function () use ( $self ) {
			$self->add_page_header_settings();
		} );
	}

	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * ACFがインストールされているかどうか判断
	 * @return bool
	 */
	public static function is_acf_installed() {
		return ( is_bool( array_search( 'advanced-custom-fields/acf.php', get_option( 'active_plugins' ) ) )
		         &&
		         is_bool( array_search( 'advanced-custom-fields-pro/acf.php', get_option( 'active_plugins' ) ) ) );
	}

	public function add_page_header_settings() {
		$page_header = new FieldsBuilder( "page_header", [ 'title' => 'ページヘッダー設定' ] );
		$page_header->addText( "page_header_title", [ 'label' => 'ページタイトル' ] );
		$page_header->addText( "page_header_subtitle", [ 'label' => 'サブタイトル' ] );
		$page_header->addImage( "page_header_image", [ 'label' => '背景画像' ] );
		$page_header->setLocation( 'post_type', '==', 'page' );
		acf_add_local_field_group( $page_header->build() );
	}

	/**
	 * ACFの設定情報をインポートする
	 *
	 * @param $json
	 *
	 * @return \ACF_Admin_Notice|bool
	 */
	public static function import_acf( $json ) {
		$json = json_decode( $json, true );
		if ( empty( $json ) ) {
			return acf_add_admin_notice( __( "インポートファイルが空です", 'acf' ), 'warning' );
		}
		if ( isset( $json['key'] ) ) {
			$json = array( $json );
		}
		if ( ! function_exists( "_acf_get_field_group_by_key" ) ) {
			// Ensure $json is an array of groups.
			if ( isset( $json['key'] ) ) {
				$json = array( $json );
			}
			// Remeber imported field group ids.
			$ids = array();
			// Loop over json
			foreach ( $json as $field_group ) {
				// Search database for existing field group.
				$post = acf_get_field_group_post( $field_group['key'] );
				if ( $post ) {
					$field_group['ID'] = $post->ID;
				}
				// Import field group.
				$field_group = acf_import_field_group( $field_group );
				// append message
				$ids[] = $field_group['ID'];
			}

			return true;
		}
		$ids      = array();
		$keys     = array();
		$imported = array();
		foreach ( $json as $field_group ) {
			$keys[] = $field_group['key'];
		}
		foreach ( $keys as $key ) {
			$field_group = _acf_get_field_group_by_key( $key );
			if ( ! $field_group ) {
				continue;
			}
			$ids[ $key ] = $field_group['ID'];
		}
		acf_enable_local();
		acf_reset_local();
		foreach ( $json as $field_group ) {
			acf_add_local_field_group( $field_group );
		}
		foreach ( $keys as $key ) {
			$field_group = acf_get_local_field_group( $key );
			$id          = acf_maybe_get( $ids, $key );
			if ( $id ) {
				$field_group['ID'] = $id;
			}
			if ( acf_have_local_fields( $key ) ) {
				$field_group['fields'] = acf_get_local_fields( $key );
			}
			$field_group = acf_import_field_group( $field_group );
			$imported[]  = array(
				'ID'      => $field_group['ID'],
				'title'   => $field_group['title'],
				'updated' => $id ? 1 : 0
			);
		}
		if ( ! empty( $imported ) ) {
			$links   = array();
			$count   = count( $imported );
			$message = sprintf( _n( 'Imported 1 field group', 'Imported %s field groups', $count, 'acf' ), $count ) . '.';
			foreach ( $imported as $import ) {
				$links[] = '<a href="' . admin_url( "post.php?post={$import['ID']}&action=edit" ) . '" target="_blank">' . $import['title'] . '</a>';
			}
			$message .= ' ' . implode( ', ', $links );
			acf_add_admin_notice( $message, 'success' );
		}

		return true;
	}

}
