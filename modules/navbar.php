<?php
/**
 * Navbar module
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */
?>

<div class="contain-to-grid">

	<nav class="top-bar" data-topbar role="navigation">


			<ul class="title-area">
				<li class="name">

				</li>
				<li class="toggle-topbar menu-icon"><a href="#"><span>メニュー</span></a></li>
			</ul>
			<section class="top-bar-section">
				<?php
				/**
				 * Global Navigation
				 */
				wp_nav_menu(
					array(
						'menu' => 'primary',
						'theme_location' => 'primary',
						'depth' => 5,
						'container' => 'false',
						'container_class' => '',
						'container_id' => 'header-navbar-collapse',
						'menu_class' => 'left',
						'fallback_cb' => false,
						'before' => '',                                 // before each link <a>
						'after' => '',
						'link_before' => '',                            // before each link text
						'link_after' => '',
						'walker' => new Epigone_Walker_Nav(),
					)
				);
				?>
			</section>

	</nav>

</div>
