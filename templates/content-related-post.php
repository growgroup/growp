<?php
/**
 * デフォルトのコンテンツテンプレート
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('relatedpost__item columns large-3 small-6'); ?>>

    <header class="relatedpost__item__header">
        <?php if ('post' == get_post_type()) : ?>
            <div class="relatedpost__item__meta">
                <?php epigone_posted_on(); ?>
            </div><!-- .relatedpost__meta -->
        <?php endif; ?>

        <h3 class="relatedpost__item__title">
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h3>

    </header>
    <!-- .relatedpost__header -->


    <div class="relatedpost__item__summary clearfix">
        <?php
        if (has_post_thumbnail() && 'true' === get_theme_mod('single_thumbnail', 'false')) { ?>
            <?php the_post_thumbnail('thumbnail', array('class' => 'relatedpost__item__thumbnail left')); ?>
            <?php
        } ?>
        <?php the_excerpt(); ?>
    </div>
    <!-- .relatedpost-summary -->
    <?php


    if ("true" == get_theme_mod('single_post_category', 'true') || "true" == get_theme_mod('single_post_tags', 'true')) { ?>

        <footer class="relatedpost__item__footer">
            <?php
            if ('post' == get_post_type()) :
                if ("true" == get_theme_mod('single_post_category', 'true')) {
                    $categories_list = get_the_category_list(__(', ', 'epigone'));
                    if ($categories_list && epigone_categorized_blog()) :
                        ?>
                        <span class="cat-links">
						<i class="fa fa-folder"></i> <?php printf(__('Posted in %1$s', 'epigone'), $categories_list); ?>
					</span>
                        <?php
                    endif; // End if categories
                } ?>

                <?php
                if ("true" == get_theme_mod('single_post_tags', 'true')) {
                    /* translators: used between list items, there is a space after the comma */
                    $tags_list = get_the_tag_list('', __(', ', 'epigone'));
                    if ($tags_list) :
                        ?>
                        <span class="tags-links">
						<i class="fa fa-tags"></i> <?php printf(__('Tagged %1$s', 'epigone'), $tags_list); ?>
					</span>
                        <?php
                    endif; // End if $tags_list
                }
            endif;

            if (!post_password_required() && (comments_open() || '0' !== get_comments_number())) : ?>
                <span
                    class="comments-link"><?php comments_popup_link(__('Leave a comment', 'epigone'), __('1 Comment', 'epigone'), __('% Comments', 'epigone')); ?></span>        <?php
            endif; ?>

        </footer>
        <!-- .relatedpost-footer -->
        <?php
    }
    ?>
</article><!-- #post-<?php the_ID(); ?> -->
