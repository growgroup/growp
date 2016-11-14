<?php
/**
 * [レイアウト]
 * ヘッダー
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */
?>
<header class="l-header" role="banner">
    <div class="l-container">
        <div class="row">
            <div class="large-12">
                <h1 class="l-header__logo">
                    <a href="<?php echo home_url(); ?>">
                        <img src="<?php echo get_theme_mod('logo_image', '') ?>" alt="<?php bloginfo('name'); ?>" />
                    </a>
                </h1>
            </div>
        </div>
    </div>
</header>
