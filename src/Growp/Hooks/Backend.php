<?php

namespace Growp\Hooks;

use function add_action;
use function get_role;
use Growp\Mock\FrontAndHome;
use Growp\Mock\MwWpForm;
use Growp\Mock\PrivacyPolicy;
use Growp\Mock\Sitemap;
use Growp\Mock\TinymceAdvanced;
use Growp\Mock\WpAdminUiCustomize;
use function wp_get_current_user;

class Backend extends BaseHookSingleton {

	public function __construct() {
		add_action( 'admin_init', [ $this, 'add_editor_roles' ] );
		add_action( 'editable_roles', [ $this, 'filter_editable_roles' ] );
		add_action( 'admin_head', function () {
			?>
			<style>#the-list .ui-sortable-placeholder {
					display: none;
				}</style>
			<?php
		}
		);
		add_action( "after_switch_theme", function () {
			new Sitemap();
			new MwWpForm();
			new FrontAndHome();
			new PrivacyPolicy();
			new WpAdminUiCustomize();
			new TinymceAdvanced();
		} );

	}

	/**
	 * 編集者の権限を変更し、ユーザーを追加することができるように
	 */
	public function add_editor_roles() {
		$role = get_role( 'editor' );
		$role->add_cap( 'delete_users' );
		$role->add_cap( 'create_users' );
		$role->add_cap( 'remove_users' );
		$role->add_cap( 'edit_users' );
		$role->add_cap( 'list_users' );
	}

	/**
	 * 管理者以外は新規ユーザー登録で管理者権限アカウントを追加できないように
	 *
	 * @param $all_roles
	 *
	 * @return array
	 */
	public function filter_editable_roles( $all_roles ) {
		$current_user = wp_get_current_user();
		if ( isset( $current_user->roles[0] ) && $current_user->roles[0] !== "administrator" ) {
			unset( $all_roles["administrator"] );
		}

		return $all_roles;
	}

}
