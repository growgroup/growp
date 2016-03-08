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

<article id="post-<?php the_ID(); ?>" <?php post_class('hentry--tilecard'); ?>>
    <?php
    if (has_post_thumbnail() && 'true' === get_theme_mod('single_thumbnail', 'false')) { ?>
        <div class="hentry__thumbnail__wrap text-center">
            <?php the_post_thumbnail( 'tile_thumbnail', array('class' => 'hentry__thumbnail th')); ?>
        </div>
        <?php
    } ?>
    <header class="hentry__header">
        <?php if ('post' == get_post_type()) : ?>
            <div class="hentry__meta">
                <?php epigone_posted_on(); ?>
            </div><!-- .hentry__meta -->
        <?php endif; ?>

        <h3 class="hentry__title">
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h3>

    </header>
    <!-- .hentry__header -->


    <div class="hentry__summary clearfix">

        <?php the_excerpt(); ?>
    </div>
    <!-- .hentry-summary -->
    <?php


    if (   "true" == get_theme_mod('single_post_category', 'true')
		|| "true" == get_theme_mod('single_post_tags'    , 'true')
		|| "true" == get_theme_mod('single_comment_num'  , 'true') ) { ?>

        <footer class="hentry__footer">
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

            if ( "true" == get_theme_mod('single_comment_num', 'true')
				  && ( ! post_password_required()
						&& ( comments_open() || '0' !== get_comments_number() )
					 )
				) : ?>
                <span
                    class="comments-link"><?php comments_popup_link(__('Leave a comment', 'epigone'), __('1 Comment', 'epigone'), __('% Comments', 'epigone')); ?></span>        <?php
            endif; ?>

            <?php edit_post_link(__('Edit', 'epigone'), '<span class="edit-link button tiny round"><i class="fa fa-pencil"></i> ', '</span>'); ?>
        </footer>
        <!-- .hentry-footer -->
        <?php
    }
    ?>
</article><!-- #post-<?php the_ID(); ?> -->
