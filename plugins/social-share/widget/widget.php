<?php
class WP_SocialWidget extends WP_Widget {

	/**
	 * Sets up a new Text widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops  = array(
			'classname'                   => 'widget_social',
			'description'                 => __( 'ソーシャルシェアボタン表示用ウィジェット' ),
			'customize_selective_refresh' => true,
		);
		$control_ops = array( 'width' => 400, 'height' => 350 );
		parent::__construct( 'widget_social', __( '[GARUMAX] ソーシャルボタン' ), $widget_ops, $control_ops );
	}

	/**
	 * Outputs the content for the current Text widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Text widget instance.
	 */
	public function widget( $args, $instance ) {

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance,
			$this->id_base );

		$widget_text = ! empty( $instance['text'] ) ? $instance['text'] : '';

		$text                  = apply_filters( 'widget_social', $widget_text, $instance, $this );
		$widget_active_buttons = isset( $instance['active_buttons'] ) ? $instance['active_buttons'] : array();
		$widget_type           = $instance['type'];

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<div class="l-section is-none-left-right">
			<div class="c-social-share-list js-sharebutton">
				<ul>
					<?php
					$objSnsButton                 = growp_get_sharebutton();
					$objSnsButton->active_buttons = $widget_active_buttons;
					
					if ( $widget_type === "share" ) {
						$objSnsButton->unsetButton( "rss" );
					} else {
						$objSnsButton->unsetButton( "pocket" );
					}
					echo $objSnsButton->get( $widget_type );
					?>
				</ul>
			</div>
		</div>

		<?php
		echo $args['after_widget'];
	}

	/**
	 * Handles updating settings for the current Text widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                     = $old_instance;
		$instance['type']             = $new_instance['type'];
		$instance['activate_buttons'] = $new_instance['activate_buttons'];

		return $instance;
	}

	/**
	 * Outputs the Text widget settings form.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance         = wp_parse_args( (array) $instance, array( 'activate_buttons' => '', 'type' => '' ) );
		$activate_buttons = isset( $instance['activate_buttons'] ) ? $instance['activate_buttons'] : 0;

		$type = isset( $instance['type'] ) ? $instance['type'] : "account";
		$sns  = growp_social_buttons();
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>">
				<strong>有効にするボタンを設定してください。</strong>
			</label>
		</p>
		<?php
		foreach ( $sns as $button => $title ) {

			?>
			<p>

				<input id="<?php echo $this->get_field_id( 'activate_buttons' ); ?>_<?php echo $button; ?>" value="true" name="<?php echo $this->get_field_name( 'activate_buttons' ); ?>[<?php echo $button; ?>]" type="checkbox"<?php checked( "true",
					$activate_buttons[ $button ] ); ?> />&nbsp;
				<label for="<?php echo $this->get_field_id( 'activate_buttons' ); ?>_<?php echo $button; ?>"><?php echo $title; ?></label>
			</p>
			<?php

		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>">
				<strong>ボタンのタイプを指定してください。</strong>
			</label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'type' ); ?>" type="radio" name="<?php echo $this->get_field_name( 'type' ); ?>" value="account" <?php checked( "account",
				$type ) ?> />
			フォローボタン
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'type' ); ?>" type="radio" name="<?php echo $this->get_field_name( 'type' ); ?>" value="share" <?php checked( "share",
				$type ) ?>/>
			シェアボタン
		</p>


		<?php
	}
}


add_action( 'widgets_init', function () {
	register_widget( "WP_SocialWidget" );
} );
