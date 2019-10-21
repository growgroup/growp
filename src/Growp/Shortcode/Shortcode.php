<?php

namespace Growp\Shortcode;

use Growp\Template\Component;

class Shortcode {

	public static $instance = null;

	private function __construct() {

		add_shortcode( "growp_children_page_nav", [ $this, "page_nav" ] );

		add_shortcode( 'growp_component', [ $this, 'get_component' ] );

	}

	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * コンポーネントを取得
	 *
	 * @param $attrs
	 *
	 * @return false|string
	 */
	public function get_component( $attrs ) {
		$attrs = shortcode_atts( array(
			'name' => '',
		), $attrs, 'growp_component' );
		if ( empty( $attrs["name"] ) ) {
			return "";
		}
		ob_start();
		Component::get( $attrs["name"] );
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * 現状の子ページを取得し、ナビゲーションとして表示
	 * @return false|string
	 */
	public function page_nav() {
		global $post;
		if ( $post->post_parent === 0 ) {
			return "";
		}
		$parent_id      = $post->post_parent;
		$parent         = get_post( $parent_id );
		$parent_title   = $parent->post_title;
		$children_pages = get_pages( [
			'sort_column' => 'menu_order',
			'parent'      => $parent_id,
		] );
		if ( ! $children_pages ) {
			return "";
		}
		ob_start();
		?>
		<div class="c-relation">
			<div class="l-container">
				<div class="c-relation__title"><span>NEXT CONTENT</span>
					<small>その他<?php echo $parent_title ?></small>
				</div>
				<div class="c-relation__button">
					<ul class="row">
						<?php
						foreach ( $children_pages as $page ) {
							?>
							<div class="large-3 small-6">
								<li class="<?php echo is_page( $page->ID ) ? "is-active" : "" ?>">
									<a class="c-button" href="<?php echo get_the_permalink( $page->ID ) ?>"><span><?php echo get_the_title( $page->ID ) ?></span></a>
								</li>
							</div>
							<?php
						}
						?>
					</ul>
				</div>
			</div>
		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}


}
