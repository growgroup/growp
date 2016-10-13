<?php
/**
 * growp breadcrumbs
 *
 * @package growp
 * @since 1.0.0
 */
/**
 * @see inc/template-tags.php
 */

// WordPress SEO by Yoast のパンくずを利用する
if (function_exists('yoast_breadcrumb')) {
    yoast_breadcrumb('<div class="c-breadcrumb"', '</div>');
}
