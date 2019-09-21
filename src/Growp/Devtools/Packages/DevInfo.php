<?php

namespace Growp\Devtools\Packages;

use function _wp_admin_bar_init;
use function add_action;
use function add_filter;
use function get_delete_post_link;
use function get_edit_post_link;
use function get_post_type_object;
use function get_post_types;
use function get_taxonomies;
use function get_the_ID;
use function get_the_permalink;
use function get_the_title;
use function have_posts;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use Routes;
use function show_admin_bar;
use function the_permalink;
use function wp_footer;
use function wp_get_current_user;
use function wp_head;
use WP_Query;
use function wp_title;

class DevInfo {
	protected static $instance = null;

	/**
	 * Frontend constructor.
	 * 初期化
	 */
	private function __construct() {
		\Routes::map( '/growp/devinfo/', function () {
			add_action( "wp", function () {
				$post_types = get_post_types(['public' => true]);
				$taxonomies = get_taxonomies();
				?>
				<!doctype html>
				<html lang="ja">
				<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
				<meta http-equiv="X-UA-Compatible" content="ie=edge">
				<?php
				_wp_admin_bar_init();
				wp_head();
				?>
				</head>
				<body>
				<div class="l-section">
					<div class="l-container">
						<h1 class="c-heading is-text-left is-xlg">コンテンツ一覧</h1>
						<?php
						foreach ( $post_types as $post_type ) {
							$query = new WP_Query( [
								'post_type'      => $post_type,
								'posts_per_page' => - 1,
							] );
							if ( $query->have_posts() ) {
								$post_type_object = get_post_type_object( $post_type );
								?>
								<h1 class="c-heading is-text-left is-sm is-icon"><?php echo $post_type_object->label ?></h1>
								<table class="c-table is-dev">
									<thead>
										<tr>
											<th>ID</th>
											<th>ページタイトル</th>
											<th>URL</th>
											<th>アクション</th>
										</tr>
									</thead>
									<tbody>
										<?php
										while ( $query->have_posts() ) {
											$query->the_post();
											?>
											<tr>
												<td><code><?php echo get_the_ID() ?></code></td>
												<td>
													<p>
														<a href="<?php the_permalink() ?>"><?php echo get_the_title() ?></a>
													</p>
													<p><?php ?></p>
												</td>
												<td><code><?php echo get_the_permalink() ?></code></td>
												<td>
													<a class="c-label is-sm" href="<?php echo get_edit_post_link() ?>" target="_blank">
														編集
													</a>
													<a class="c-label is-sm" onclick="return (confirm('本当に削除しますか？') ? true : false);" href="<?php echo get_delete_post_link( get_the_ID() ) ?>">
														削除
													</a>
												</td>
											</tr>
											<?php
										}
										?>
									</tbody>
								</table>
								<?php
							}
						}
						?>

					</div>
				</div>
				<?php
				wp_footer();
				?>
				</body>
				</html>
				<?php
				exit;
			} );

			return true;
		}, [], 400, 10 );
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
}
