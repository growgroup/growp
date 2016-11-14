<?php
/**
 * [レイアウト]
 * フッター
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */
?>
<footer class="l-footer" role="contentinfo">
    <div class="l-container">
        <div class="row">
            <div class="footer__contents">
                <?php
                growp_dynamic_sidebar('footer-primary');
                ?>
            </div>
        </div>
        <div class="footer__copyright site-info">
            <div class="row text-center">
                <span class="sep">
                    <?php echo get_theme_mod('copyright_text',
                        'copyright © ' . date('Y') . ' | ' . get_bloginfo('name')); ?>
                </span>
            </div>
        </div>
    </div>
</footer>

</div>

<?php wp_footer(); ?>

</body>
</html>
