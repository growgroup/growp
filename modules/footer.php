<?php
/**
 * フッターテンプレート
 * =====================================================
 * @package  epigone
 * @since 1.0.0
 * =====================================================
 */
?>
	<footer id="colophon" class="footer" role="contentinfo">

		<div class="row">
			<div class="footer__contents">
				<?php
				epigone_dynamic_sidebar( 'footer-primary' );
				?>
			</div>
		</div>
		<div class="footer__copyright site-info">
			<div class="row text-center">
				<span class="sep"> <?php echo get_theme_mod( 'copyright_text', 'copyright © ' . date( 'Y' ) . ' | ' . get_bloginfo( 'name' ) ); ?></span>
			</div>
		</div>
	</footer>
</div>

<?php wp_footer(); ?>

</body>
</html>
