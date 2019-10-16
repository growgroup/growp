<?php

namespace Growp\Hooks;

class Comments {

	protected static $instance = null;

	/**
	 * Frontend constructor.
	 * 初期化
	 */
	private function __construct() {
		add_filter( 'comments_template', [ $this, 'comment_template' ], 10, 1 );
		add_filter( 'comment_form_defaults', [ $this, 'comment_form_defaults' ] );
		add_filter( 'comment_form_default_fields', [ $this, 'comment_form_args' ] );
		add_filter( 'comment_form_field_comment', [ $this, 'comment_form_field_comment' ] );
	}

	/**
	 * シングルトンインスタンスを取得
	 * @return null
	 */
	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function comment_template( $comment_template ) {
		return get_template_directory() . '/views/object/components/comments.php';
	}

	public function comment_form_defaults( $defaults ) {

		$defaults['title_reply']          = __( 'Post a new comment', 'growp' );
		$defaults['comment_notes_before'] = '<p class="comment-notes">' . __( 'Your email address will not be published. Required fields are marked',
				'growp' ) . '</p>';

		return $defaults;

	}

	public function comment_form_args( $args ) {
		$comment_author       = isset( $commenter['comment_author'] ) ? $commenter['comment_author'] : '';
		$comment_author_email = isset( $commenter['comment_author_email'] ) ? $commenter['comment_author_email'] : '';
		$comment_author_url   = isset( $commenter['comment_author_url'] ) ? $commenter['comment_author_url'] : '';
		$args['author']       = '<p class="comment-form-author"><input id="author" name="author" type="text" value="' . esc_attr( $comment_author ) . '" size="40" tabindex="1" aria-required="true" title="' . __( 'Your Name (required)',
				'growp' ) . '" placeholder="' . __( 'Your Name (required)',
				'growp' ) . '" required /><!-- .comment-form-author .form-section --></p>';
		$args['email']        = '<p class="comment-form-email"><input id="email" name="email" type="email" value="' . esc_attr( $comment_author_email ) . '" size="40" tabindex="2" aria-required="true" title="' . __( 'Email Address (required)',
				'growp' ) . '" placeholder="' . __( 'Email Address (required)',
				'growp' ) . '" required /><!-- .comment-form-email .form-section --></p>';
		$args['url']          = '<p class="comment-form-url"><input id="url" name="url" type="url" value="' . esc_attr( $comment_author_url ) . '" size="40" tabindex="3" aria-required="false" title="' . __( 'Website (url)',
				'growp' ) . '" placeholder="' . __( 'Website (url)',
				'growp' ) . '" required /><!-- .comment-form-url .form-section --></p>';

		return $args;
	}

	public function comment_form_field_comment( $field ) {

		$field = '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" tabindex="4" title="' . __( 'Comment',
				'growp' ) . '" placeholder="' . __( 'Comment',
				'growp' ) . '" aria-required="true"></textarea><!-- #form-section-comment .form-section --></p>';

		return $field;

	}


}
