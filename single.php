<?php
/**
 * 投稿テンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */


while (have_posts()) :
    the_post();
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>

        <h1 class="heading is-xlg"><?php the_title(); ?></h1>

        <div class="l-post-content">
            <?php

            the_content();

            wp_link_pages(array(
                'before' => '<div class="page-links">' . __('Pages:', 'growp'),
                'after'  => '</div>',
            ));
            ?>
        </div>
    </article><!-- #post-## -->
    <?php
    GNav::the_post_nav();


    if ((comments_open() || '0' != get_comments_number())) {
        comments_template();
    }
    ?>
    <?php

endwhile; // end of the loop.
