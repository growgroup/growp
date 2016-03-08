<?php
/**
 * search form module
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */
?>

<form role="form" action="<?php echo site_url( '/' ); ?>" id="searchform" class="block" method="get">
	<label for="s" class="screen-reader-text"><?php _e( 'Search', 'epigone' ); ?></label>
	<div class="row collapse">
		<div class="small-7 columns">
			<input type="text" class="form-control" id="s" name="s" placeholder="<?php _e( 'Search', 'epigone' ); ?>" value=""/>
		</div>
		<div class="small-5 columns">
			<button type="submit" class="button postfix"><?php _e( 'Submit', 'epigone' ); ?> </button>
		</div>
	</div>
	<!-- .input-group -->
</form>
