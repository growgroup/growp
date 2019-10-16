<?php

namespace Growp\Devtools\Packages;

use function add_action;
use Growp\TemplateTag\Utils;

class Note {

	protected static $instance = null;

	/**
	 * Frontend constructor.
	 * 初期化
	 */
	private function __construct() {
		add_action( 'init', [ $this, 'register_post_type' ], 0 );
		\Routes::map( "/add_note/", [ $this, 'add_note' ] );
		\Routes::map( "/delete_note/", [ $this, 'delete_note' ] );
		\Routes::map( "/update_sort_notes/", [ $this, 'update_sort_notes' ] );
		\Routes::map( "/get_notes/", [ $this, 'get_note' ] );
		\Routes::map( "/edit_note/", [ $this, 'edit_note' ] );
		add_action( "wp_footer", [ $this, 'render_template' ] );
		add_action( "wp_head", [ $this, 'enqueue_assets' ], 0 );
		add_action( "admin_bar_menu", [ $this, "admin_bar" ] );
	}

	/**
	 * 管理バーに追加
	 * @param $wp_admin_bar
	 * @return string
	 */
	public function admin_bar( $wp_admin_bar ) {
		if ( is_admin() ){
			return false;
		}
		$wp_admin_bar->add_node( [
			'id'     => "devnote",
			'parent' => "growp_dev",
			'title'  => "ノート",
			'href'   => "#note_list",
//				'group'  => true,
			'meta'   => [
				'class'       => 'js-note-button modal-trigger',
				'data-target' => 'note_list',
			],
		] );

		return "";
	}

	/**
	 * シングルトンインスタンスを取得
	 * @return null
	 */
	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * 静的ファイルを登録
	 * @return bool
	 */
	public function enqueue_assets() {
		if ( ! Utils::is_administrator() ) {
			return false;
		}

		Utils::get_relative_url( __DIR__ );
		wp_enqueue_script( "notejs", Utils::get_relative_url( __DIR__ . "/../assets/js/note.js" ), [], time(), true );
		wp_enqueue_style( "notematelializecss", Utils::get_relative_url( __DIR__ . "/../assets/css/materialize.css" ), [], time(), "all" );
		wp_enqueue_style( "notecss", Utils::get_relative_url( __DIR__ . "/../assets/css/style.css" ), [], time(), "all" );
		wp_enqueue_style( "note_materialicon", "https://fonts.googleapis.com/icon?family=Material+Icons", [], time(), "all" );
	}

	/**
	 * テンプレートをフッターに追加
	 * @return bool
	 */
	public function render_template() {
		if ( ! Utils::is_administrator() ) {
			return false;
		}
		?>
		<div class="js-notewrap">
			<div id="new_note" class="modal">
				<div class="modal-content">
					<h4>新しいノートを追加</h4>
					<form action="/add_note/" method="post" id="new_note_form">
						<input type="text" name="post_title" id="new_note_title" class="materialize-text" value="" placeholder="タイトル" />
						<textarea name="post_content" class="materialize-textarea" placeholder="本文を入力" id="new_note_content" cols="30" rows="10"></textarea>
						<button id="submit_add_note" class="waves-effect waves-light  btn-small">ノートを追加</button>
					</form>
				</div>
			</div>
			<div id="edit_note" class="modal">
				<div class="modal-content">
					<h4>ノートを編集</h4>
					<form action="/edit_note/" method="post" id="edit_note_form">
						<input type="text" name="post_title" class="materialize-text" id="edit_note_title" value="" placeholder="タイトル" />
						<textarea name="post_content" class="materialize-textarea" id="edit_note_content" cols="30" rows="10"></textarea>
						<input type="hidden" id="edit_post_id" value="" />
						<button id="submit_edit_note" class="waves-effect waves-light btn-small">ノートを保存</button>
					</form>
				</div>
			</div>
			<!-- Modal Structure -->
			<div id="note_list" class="modal">
				<div class="modal-content">
					<h4>ノート一覧
						<a id="add_note" data-target="new_note" class=" modal-trigger waves-effect waves-light  btn-small right">ノートを追加</a>
					</h4>

					<div id="notelistrender">
						<?php echo $this->note_list_render() ?>
					</div>
				</div>
				<div class="modal-footer">
					<a href="#!" class="modal-close waves-effect waves-green btn-flat">閉じる</a>
				</div>
			</div>

		</div>
		<!-- Compiled and minified JavaScript -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.9.0/Sortable.min.js" integrity="sha256-dTRAC66orJgjxgfw22bvkFVLM8/S+vchcXIzHyYzNFg=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
		<script>
		</script>
		<?php
	}

	/**
	 * ノートの編集
	 */
	public function edit_note() {
		$user = wp_get_current_user();
		if ( ! isset( $_POST["post_title"] ) || ! trim( $_POST["post_title"] ) ) {
			wp_send_json_error( [ "message" => "タイトルが入力されていません" ] );
			exit;
		}
		if ( ! isset( $_POST["post_content"] ) || ! trim( $_POST["post_content"] ) ) {
			wp_send_json_error( [ "message" => "内容が入力されていません" ] );
			exit;
		}
		if ( ! isset( $_POST["post_id"] ) || ! trim( $_POST["post_id"] ) ) {
			wp_send_json_error( [ "message" => "不正なアクセス" ] );
			exit;
		}
		$post_id = wp_update_post( [
			'ID'           => $_POST["post_id"],
			'post_type'    => "notes",
			'post_title'   => esc_html( strip_tags( $_POST["post_title"] ) ),
			'post_content' => $_POST["post_content"],
			'post_status'  => 'publish',
			'post_author'  => $user->ID,
		] );
		if ( $post_id ) {
			wp_send_json_success( [ "message" => "保存しました" ] );
		}
		wp_send_json_error( [ "message" => "エラーが発生しました。" ] );
		exit;
	}

	/**
	 * ノートの順番を保存
	 * @return bool
	 */
	public function update_sort_notes() {
		if ( ! is_user_logged_in() ) {
			return false;
		}
		if ( isset( $_POST["indexes"] ) && $_POST["indexes"] ) {
			$indexes = $_POST["indexes"];
			foreach ( $indexes as $i ) {
				$p = wp_update_post( [ 'ID' => intval( $i["post_id"] ), "menu_order" => intval( $i["menu_order"] ) ] );
			}
		}
		exit;
	}

	/**
	 * ノートを削除
	 * @return bool
	 */
	public function delete_note() {

		$user = wp_get_current_user();
		if ( ! isset( $_POST["post_id"] ) || ! trim( $_POST["post_id"] ) ) {
			wp_send_json_error( [ "message" => "エラーが起きました" ] );
			exit;
		}

		$post_id = esc_attr( $_POST["post_id"] );

		$note = get_post( $post_id );
		if ( ! $note || $note->post_type !== "notes" ) {
			wp_send_json_error( [ "message" => "エラーが発生しました。" ] );
			exit;
		}
		if ( (int) $note->post_author !== (int) $user->ID ) {
			wp_send_json_error( [ "message" => "エラーが発生しました。" ] );
			exit;
		}
		if ( $post_id ) {
			wp_delete_post( $post_id );
			wp_send_json_success( [ "message" => "削除しました" ] );
			exit;
		}
		wp_send_json_error( [ "message" => "エラーが発生しました。" ] );
		exit;
	}


	/**
	 * ノートを追加する
	 */
	public function add_note() {
		if ( ! isset( $_POST["post_title"] ) || ! trim( $_POST["post_title"] ) ) {
			wp_send_json_error( [ "message" => "タイトルが入力されていません" ] );
			exit;
		}
		if ( ! isset( $_POST["post_content"] ) || ! trim( $_POST["post_content"] ) ) {
			wp_send_json_error( [ "message" => "内容が入力されていません" ] );
			exit;
		}
		$post_id = wp_insert_post( [
			'post_type'    => "notes",
			'post_title'   => esc_html( strip_tags( $_POST["post_title"] ) ),
			'post_content' => $_POST["post_content"],
			'post_status'  => 'publish'
		] );
		if ( $post_id ) {
			wp_send_json_success( [ "message" => "保存しました" ] );
		}
		wp_send_json_error( [ "message" => "エラーが発生しました。" ] );
		exit;
	}

	/**
	 * ノートを取得
	 * @return bool
	 */
	public function get_note() {
		if ( ! Utils::is_administrator() ) {
			return false;
		}
		if ( ! is_user_logged_in() ) {
			return false;
		}
		wp_send_json_success( [ "html" => $this->note_list_render() ] );
		exit;
	}


	/**
	 * ノート一覧を出力
	 * @return bool|false|string
	 */
	public function note_list_render() {

		ob_start();
		$user  = wp_get_current_user();
		$notes = get_posts( [
			'post_type'      => 'notes',
			'posts_per_page' => - 1,
			'order'          => "ASC",
			'orderby'        => "menu_order",
			'author'         => $user->ID,
		] );
		if ( $notes ) {
			?>
			<ul class="collapsible" id="note_items">
				<?php
				foreach ( $notes as $notekey => $note ) {
					?>
					<li class="note-item " data-id="<?php echo $note->ID ?>">
						<div class="collapsible-header note-date">
							<div>
								<div class="note-date__date">
									<span class="badge" style="float: none">ID: <?php echo $note->ID ?></span> 最終更新日時 : <?php echo date_i18n( "Y-m-d H:i:s", strtotime( $note->post_modified ) ) ?>
									<div class="note-date__excerpt js-note-title">
										<strong><?php echo mb_strimwidth( $note->post_title, 0, 40, "..." ) ?></strong>
									</div>
								</div>
							</div>
						</div>
						<div class="collapsible-body note-text" style="display: block;">
							<div class="js-note-text"><?php echo nl2br( $note->post_content ) ?></div>
							<form action="/delete_note/" class="delete_note_form right-align">
								<a href="#" data-post-id="<?php echo $note->ID ?>" class="edit_note_submit waves-effect pink lighten-2 waves-light  btn-small ">編集</a>
								<button type="submit" class="delete_note_submit waves-effect waves-light  btn-small ">削除</button>
								<input type="hidden" name="post_id" value="<?php echo $note->ID ?>" />
							</form>
						</div>
					</li>
					<?php
				}
				?>
			</ul>
			<?php
		} else {
			?>
			<li>ノートがありません</li>
			<?php
		}
		$con = ob_get_contents();
		ob_end_clean();
		return $con;

	}

	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'ノート', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'ノート', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'ノート', 'text_domain' ),
			'name_admin_bar'        => __( 'ノート', 'text_domain' ),
			'archives'              => __( 'ノート一覧', 'text_domain' ),
			'attributes'            => __( '', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'すべてのノート', 'text_domain' ),
			'add_new_item'          => __( '新しいノート', 'text_domain' ),
			'add_new'               => __( '新しいノートを追加', 'text_domain' ),
			'new_item'              => __( '新しいノートを追加', 'text_domain' ),
			'edit_item'             => __( 'ノートを編集', 'text_domain' ),
			'update_item'           => __( 'ノートを更新', 'text_domain' ),
			'view_item'             => __( 'ノートを表示', 'text_domain' ),
			'view_items'            => __( 'ノート一覧を表示', 'text_domain' ),
			'search_items'          => __( 'ノートを検索', 'text_domain' ),
			'not_found'             => __( '見つかりませんでした', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
			'items_list'            => __( 'Items list', 'text_domain' ),
			'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
		);
		$args   = array(
			'label'               => __( 'ノート', 'text_domain' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
		);
		register_post_type( 'notes', $args );
	}
}


