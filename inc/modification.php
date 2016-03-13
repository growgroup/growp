<?php
/**
 * テーマカスタマイザーの設定
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.2.0
 * =====================================================''
 */
/**
 * テーマカスタマイザーを拡張
 * @since 1.2.0
 */
function growp_customizer_settings()
{

	$settings['growp_general'] = array(
		'title' => __('General', 'growp'),
		'description' => __('Please have a set of general setting.', 'growp'),
		'section' => array(
			'growp_google_analytics' => array(
				'title' => __('Google Analytics - Tracking code', 'growp'),
				'setting' => array(
					'tracking_code' => array(
						'label' => __('Tracking Code', 'growp'),
						'default' => '',
						'type' => 'textarea',
						'sanitaize_call_back' => '',
					),
				),
			),
			'growp_meta_description' => array(
				'title' => __('Meta Description', 'growp'),
				'setting' => array(
					'meta_description' => array(
						'label' => __('Meta Description', 'growp'),
						'default' => get_bloginfo('description'),
						'type' => 'textarea',
						'sanitaize_call_back' => '',
					),
				),
			),
			'growp_meta_keyword' => array(
				'title' => __('Meta Keyword', 'growp'),
				'setting' => array(
					'meta_keyword' => array(
						'label' => __('Meta Keyword', 'growp'),
						'default' => '',
						'type' => 'text',
						'sanitaize_call_back' => '',
					),
				),
			),
			'growp_favicon' => array(
				'title' => __('Favicon', 'growp'),
				'description' => __('16  16 px .ico file or .png', 'growp'),
				'setting' => array(
					'meta_favicon' => array(
						'label' => __('Favicon', 'growp'),
						'default' => '',
						'type' => 'multi-image',
						'sanitaize_call_back' => '',
					),
				),
			),
		),
	);
	/**
	 * 01. ヘッダーの設定
	 */
	$settings['growp_header'] = array(
		'title' => __('Header', 'growp'), // Panel title
		'description' => __('Please have a set of headers.', 'growp'),
		'section' => array(
			'growp_header_style' => array(
				'title' => __('Header Style', 'growp'),
				'setting' => array(
					'header_style' => array(
						'label' => __('Header Style', 'growp'),
						'default' => 'top',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'top' => __('Navigation : Top', 'growp'),
							'bottom' => __('Navigation : Bottom', 'growp'),
							'none' => __('Navigation : None', 'growp'),
							'header_none' => __('Header : None', 'growp'),
						),
					),
					'header_text_align' => array(
						'label' => __('Header Text Align', 'growp'),
						'default' => 'left',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'left' => __('Text Align: Left', 'growp'),
							'center' => __('Text Align: Center', 'growp'),
							'right' => __('Text Align: Right', 'growp'),
						),
						'output' => array(
							'.header' => 'text-align',
						),
					),
				),
			),
			'growp_logo' => array(
				'title' => __('Logo', 'growp'),
				'setting' => array(
					'logo_image' => array(
						'label' => __('Logo Image', 'growp'),
						'default' => '',
						'type' => 'multi-image',
						'sanitaize_call_back' => '',
					),
					'logo_font_size' => array(
						'label' => __('Font Size', 'growp'),
						'default' => 1.0,
						'type' => 'select',
						'sanitaize_call_back' => '',
						'choices' => array(
							'1.0' => '0',
							'1.1' => '1',
							'1.2' => '2',
							'1.3' => '3',
							'1.4' => '4',
							'1.5' => '5',
							'1.6' => '6',
							'1.7' => '7',
							'1.8' => '8',
							'1.9' => '9',
							'2.0' => '10',
						),
						'output' => array(
							'.header__logo a' => 'font-size',
						),
						'output_unit' => 'em',
					),
					'logo_color' => array(
						'label' => __('Color', 'growp'),
						'default' => '#FFFFFF',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'.header__logo a,.header__description' => 'color',
						)
					),
				)
			),

			'growp_header' => array(
				'title' => __('Background', 'growp'),
				'setting' => array(
					'header_background_image' => array(
						'label' => __('Background Image', 'growp'),
						'default' => '',
						'type' => 'multi-image',
						'sanitaize_call_back' => '',
						'output' => array(
							'#masthead' => 'background-image',
						)
					),
					'header_background_attachment' => array(
						'label' => __('Background Attachment', 'growp'),
						'default' => 'fixed',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'fixed' => __('Fixed', 'growp'),
							'scroll' => __('Scroll', 'growp'),
						),
						'output' => array(
							'#masthead' => 'background-attachment',
						)
					),
					'header_background_color' => array(
						'label' => __('Background Color', 'growp'),
						'default' => '#e8e8e8',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'#masthead' => 'background-color',
						),
					),
					'header_background_size' => array(
						'label' => __('Background Size', 'growp'),
						'default' => '100% auto',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'auto auto' => __('Horizontal : auto Vertical : auto', 'growp'),
							'100% auto' => __('Horizontal : 100%, Vertical : auto', 'growp'),
							'auto 100%' => __('Horizontal : auto, Vertical : 100%', 'growp'),
							'100% 100%' => __('Horizontal : 100%, Vertical : 100%', 'growp'),
						),
						'output' => array(
							'#masthead' => 'background-size',
						)
					),
				),
			),
		)
	);

	/**
	 * 02. テーマカラー
	 */
	$settings['growp_theme_color'] = array(
		'title' => __('Theme Style', 'growp'), // Panel title
		'section' => array(
			'growp_theme_style' => array(
				'title' => __('Theme Style ', 'growp'),
				'description' => __('Setting for Theme color.', 'growp'),
				'setting' => array(
					'growp_theme_style' => array(
						'label' => __('Theme Style', 'growp'),
						'default' => 'left',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'normal' => __('Normal', 'growp'),
							'blog' => __('Blog', 'growp'),
						),
						'output' => array(
							'.header' => 'text-align',
						),
					),
				),
			),
			// theme color section
			'growp_theme_color' => array(
				'title' => __('Theme Color ', 'growp'),
				'description' => __('Setting for Theme color.', 'growp'),
				'setting' => array(
					'theme_color' => array(
						'label' => __('Theme Color', 'growp'),
						'default' => '#3695b5',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'a,a:hover,a:active,a:focus,#reply-title,.breadcrumbs ul:before,.hentry__title a,.breadcrumbs > li > a' => 'color',
							'.comment-title' => 'background-color',
							'.sidebar .widget .widget-title,.button, .entry__content h2' => 'border-color',
							'.widget-sidebar li:nth-child(even):hover,.widget-sidebar ul li:hover,.nav-links div:hover ,#secondary .widget ul li:before' => 'background-color',
							'.pagination .page-numbers.current,.widget-title:after,th,.footer-copyright,#scroll-top a' => 'background-color',
							'.top-bar-section li.active:not(.has-form) a:not(.button):hover,.top-bar-section li.active:not(.has-form) a:not(.button),.button' => 'background-color',
						),
					),
				),
			),
		),
	);

	$font_size_choices = array(
		'0.8' => '0.8 em',
		'0.9' => '0.9 em',
		'1.0' => '1.0 em',
		'1.1' => '1.1 em',
		'1.2' => '1.2 em',
		'1.3' => '1.3 em',
		'1.4' => '1.4 em',
		'1.5' => '1.5 em',
		'1.6' => '1.6 em',
		'1.7' => '1.7 em',
		'1.8' => '1.8 em',
		'1.9' => '1.9 em',
		'2.0' => '2.1 em',
	);

	/**
	 * 03. ボディ設定
	 */
	$settings['growp_body'] = array(
		'title' => __('Body Settings', 'growp'), // Panel title
		'description' => __('Please have a set of body.', 'growp'),
		'section' => array(
			'growp_body' => array(
				'title' => __('Body ', 'growp'),
				'setting' => array(
					'body_background_color' => array(
						'label' => __('Background Color', 'growp'),
						'default' => '#FFFFFF',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'body' => 'background-color',
						),
					),
					'body_background_image' => array(
						'label' => __('Background Image', 'growp'),
						'default' => 'transparent',
						'type' => 'multi-image',
						'sanitaize_call_back' => '',
						'output' => array(
							'body' => 'background-image',
						)
					),
					'body_background_attachment' => array(
						'label' => __('Background Attachment', 'growp'),
						'default' => 'fixed',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'fixed' => __('Fixed', 'growp'),
							'initial' => __('Initial', 'growp'),
						),
						'output' => array(
							'body' => 'background-attachment',
						)
					),
					'body_background_size' => array(
						'label' => __('Background Size', 'growp'),
						'default' => '100% auto',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'auto auto' => __('Horizontal : auto Vertical : auto', 'growp'),
							'100% auto' => __('Horizontal : 100%, Vertical : auto', 'growp'),
							'auto 100%' => __('Horizontal : auto, Vertical : 100%', 'growp'),
							'100% 100%' => __('Horizontal : 100%, Vertical : 100%', 'growp'),
						),
						'output' => array(
							'body' => 'background-size',
						)
					),
				)
			),
			'growp_heading' => array(
				'title' => __('Headings', 'growp'),
				'setting' => array(
					'heading_color' => array(
						'label' => __('Heading Color', 'growp'),
						'default' => '#3695b5',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'.entry__title,.entry__title a,.page-header .page-title,h1,h2,h3,h4,h5,h6,.widget-title' => 'color',
						),
					),
					'heading_1_font_size' => array(
						'label' => __('H1 Font Size', 'growp'),
						'default' => '2.0',
						'type' => 'select',
						'sanitaize_call_back' => '',
						'choices' => $font_size_choices,
						'output' => array(
							'.entry__content > h1' => 'line-height',
							'.entry__content h1' => 'font-size',

						),
						'output_unit' => 'em',
					),
					'heading_2_font_size' => array(
						'label' => __('H2 Font Size', 'growp'),
						'default' => '1.8',
						'type' => 'select',
						'sanitaize_call_back' => '',
						'choices' => $font_size_choices,
						'output' => array(
							'.entry__content > h2' => 'line-height',
							'.entry__content h2' => 'font-size',
						),
						'output_unit' => 'em',
					),
					'heading_3_font_size' => array(
						'label' => __('H3 Font Size', 'growp'),
						'default' => '1.6',
						'type' => 'select',
						'sanitaize_call_back' => '',
						'choices' => $font_size_choices,
						'output' => array(
							'.entry__content > h3' => 'line-height',
							'.entry__content h3' => 'font-size',

						),
						'output_unit' => 'em',
					),
					'heading_4_font_size' => array(
						'label' => __('H4 Font Size', 'growp'),
						'default' => '1.4',
						'type' => 'select',
						'sanitaize_call_back' => '',
						'choices' => $font_size_choices,
						'output' => array(
							'.entry__content > h4' => 'line-height',
							'.entry__content h4' => 'font-size',
						),
						'output_unit' => 'em',
					),
					'heading_5_font_size' => array(
						'label' => __('H5 Font Size', 'growp'),
						'default' => '1.2',
						'type' => 'select',
						'sanitaize_call_back' => '',
						'choices' => $font_size_choices,
						'output' => array(
							'.entry__content > h5' => 'line-height',
							'.entry__content h5' => 'font-size',
						),
						'output_unit' => 'em',
					),
					'heading_6_font_size' => array(
						'label' => __('H6 Font Size', 'growp'),
						'default' => '1.1',
						'type' => 'select',
						'sanitaize_call_back' => '',
						'choices' => $font_size_choices,
						'output' => array(
							'.entry__content > h6' => 'font-size',
							'.entry__content h6' => 'line-height',
						),
						'output_unit' => 'em',
					),
				)
			),
			'growp_text' => array(
				'title' => __('Text', 'growp'),
				'setting' => array(
					'text_color' => array(
						'label' => __('Text Color', 'growp'),
						'default' => '#333',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'body' => 'color',
							'.entry__content p,.entry__content blockquote,.entry__content code,.entry__content pre,.entry__content dl,.entry__content dt,.entry__content dd,.entry__content table,.entry__content ul' => 'color',
						),
					),
					'text_font_size' => array(
						'label' => __('Base Font Size', 'growp'),
						'default' => 1.0,
						'type' => 'select',
						'sanitaize_call_back' => '',
						'choices' => array(
							'0.8' => '0.8',
							'0.9' => '0.9',
							'1.0' => '1.0',
							'1.1' => '1.1',
							'1.2' => '1.2',
							'1.3' => '1.3',
							'1.4' => '1.4',
							'1.5' => '1.5',
							'1.6' => '1.6',
							'1.7' => '1.7',
							'1.8' => '1.8',
							'1.9' => '1.9',
							'2.0' => '2.1',
						),
						'output' => array(
							'body' => 'font-size',
							'.entry__content p,.entry__content blockquote,.entry__content code,.entry__content pre,.entry__content dl,.entry__content dt,.entry__content dd,.entry__content table,.entry__content ul' => 'font-size'
						),
						'output_unit' => 'em',
					),
				),
			),
		)
	);
	$settings['growp_home'] = array(
		'title' => __('Home Page Settings', 'growp'), // Panel title
		'description' => __('Please settings for home page.', 'growp'),
		'section' => array(
			'growp_home_post_list' => array(
				'title' => __('Post List Style', 'growp'),
				'description' => __('Please select the style of Posts List', 'growp'),
				'setting' => array(
					'home_post_list' => array(
						'label' => __('Post List Style', 'growp'),
						'default' => 'normal',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'normal' => __('Normal', 'growp'),
							'tile' => __('Tile', 'growp'),
						),
					),
				)
			),
		)
	);


	/**
	 * 03. 投稿記事設定
	 */

	$settings['growp_single'] = array(
		'title' => __('Single Post Settings', 'growp'), // Panel title
		'description' => __('Please settings for single post.', 'growp'),
		'section' => array(
			'growp_single_thumbnail' => array(
				'title' => __('Display Thumbnail', 'growp'),
				'description' => __('Do you want to display a thumbnail?', 'growp'),
				'setting' => array(
					'single_thumbnail' => array(
						'label' => __('Display Thumbnail', 'growp'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'growp'),
							'false' => __('None', 'growp'),
						),
					),
				)
			),
			'growp_single_char_num' => array(
				'title' => __('Except Number of characters', 'growp'),
				'description' => __('Do you want to display a excerpt?', 'growp'),
				'setting' => array(
					'single_char_num' => array(
						'label' => __('Except Number of characters', 'growp'),
						'default' => '100',
						'type' => 'number',
						'sanitaize_call_back' => '',

					),
				)
			),
			'growp_single_post_date' => array(
				'title' => __('Display Date', 'growp'),
				'description' => __('Do you want to display a posted date?', 'growp'),
				'setting' => array(
					'single_post_date' => array(
						'label' => __('Display Date', 'growp'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'growp'),
							'false' => __('None', 'growp'),
						),
					),
				)
			),
			'growp_single_post_author' => array(
				'title' => __('Display Author', 'growp'),
				'description' => __('Do you want to display a posted author?', 'growp'),
				'setting' => array(
					'single_post_author' => array(
						'label' => __('Display Author', 'growp'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'growp'),
							'false' => __('None', 'growp'),
						),
					),
				)
			),
			'growp_single_post_category' => array(
				'title' => __('Display Category', 'growp'),
				'description' => __('Do you want to display a category?', 'growp'),
				'setting' => array(
					'single_post_category' => array(
						'label' => __('Display Category', 'growp'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'growp'),
							'false' => __('None', 'growp'),
						),
					),
				)
			),
			'growp_single_post_tags' => array(
				'title' => __('Display Tags', 'growp'),
				'description' => __('Do you want to display a tag?', 'growp'),
				'setting' => array(
					'single_post_tags' => array(
						'label' => __('Display Tags', 'growp'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'growp'),
							'false' => __('None', 'growp'),
						),
					),
				)
			),
			'growp_single_comment_num' => array(
				'title' => __('Display Comment Number', 'growp'),
				'description' => __('Do you want to display the comment number?', 'growp'),
				'setting' => array(
					'single_comment_num' => array(
						'label' => __('Do you want to display the comment number?', 'growp'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'growp'),
							'false' => __('None', 'growp'),
						),
					),
				)
			),
			'growp_single_related_post' => array(
				'title' => __('Display Related Post', 'growp'),
				'description' => __('Do you want to display the associated article?', 'growp'),
				'setting' => array(
					'single_related_post' => array(
						'label' => __('Do you want to display the associated article?', 'growp'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'growp'),
							'false' => __('None', 'growp'),
						),
					),
				)
			),
		)
	);
	/**
	 * 03. Layout
	 */

	$settings['growp_layout'] = array(
		'title' => __('Layout Settings', 'growp'), // Panel title
		'description' => __('Please have a set of layout.', 'growp'),
		'section' => array(
			'growp_layout_top' => array(
				'title' => __('Top Page', 'growp'),
				'description' => __('Please select the layout.', 'growp'),
				'setting' => array(
					'growp_layout_top' => array(
						'label' => __('Layout of top page', 'growp'),
						'default' => 'l-right-sidebar',
						'type' => 'layout-picker',
						'sanitaize_call_back' => '',
					),
				)
			),
			'growp_layout_page' => array(
				'title' => __('static page', 'growp'),
				'description' => __('Please select the layout.', 'growp'),
				'setting' => array(
					'growp_layout_page' => array(
						'label' => __('Layout of static page', 'growp'),
						'default' => 'l-right-sidebar',
						'type' => 'layout-picker',
						'sanitaize_call_back' => '',
					),
				)
			),
			'growp_layout_single' => array(
				'title' => __('single page', 'growp'),
				'description' => __('Please select the layout.', 'growp'),
				'setting' => array(
					'growp_layout_single' => array(
						'label' => __('Layout of single page', 'growp'),
						'default' => 'l-right-sidebar',
						'type' => 'layout-picker',
						'sanitaize_call_back' => '',
					),
				)
			),
		)
	);

	/**
	 * 04. Footer
	 */
	$settings['growp_footer'] = array(
		'title' => __('Footer', 'growp'), // Panel title
		'description' => __('Please have a set of footer.', 'growp'),
		'section' => array(

			'growp_footer' => array(
				'title' => __('Background', 'growp'),
				'setting' => array(
					'footer_background_image' => array(
						'label' => __('Background Image', 'growp'),
						'default' => get_template_directory_uri() . '/assets/images/footer-bg.png',
						'type' => 'multi-image',
						'sanitaize_call_back' => '',
						'output' => array(
							'#colophon' => 'background-image',
						)
					),
					'footer_background_attachment' => array(
						'label' => __('Background Attachment', 'growp'),
						'default' => 'fixed',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'fixed' => __('Fixed', 'growp'),
							'scroll' => __('Scroll', 'growp'),
						),
						'output' => array(
							'#colophon' => 'background-attachment',
						)
					),
					'footer_background_color' => array(
						'label' => __('Background Color', 'growp'),
						'default' => '#666666',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'#colophon' => 'background-color',
						),
					),
				),
			),

			// navigation section
			'growp_scrolltop' => array(
				'title' => __('Scroll Top', 'growp'),
				'description' => __('Setting for Scroll top.', 'growp'),
				'setting' => array(
					'scroll_display' => array(
						'label' => __('Display', 'growp'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'growp'),
							'false' => __('None', 'growp'),
						)
					),
					'scroll_background_color' => array(
						'label' => __('Page Top Background', 'growp'),
						'default' => '#3695b5',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'#scroll-top a' => 'background-color',
						),
					),
				),
			),
			// navigation section
			'growp_copyright' => array(
				'title' => __('Copyright', 'growp'),
				'description' => __('Setting for Copyright.', 'growp'),
				'setting' => array(
					'copyright_text' => array(
						'label' => __('Copyright text', 'growp'),
						'default' => 'copyright © ' . get_the_date('Y') . get_bloginfo('name'),
						'type' => 'text',
						'sanitaize_call_back' => '',
					),
					'copyright_background' => array(
						'label' => __('Copyright Background', 'growp'),
						'default' => '#3695b5',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'.footer__copyright' => 'background-color',
						),
					),
				),
			),
		)
	);

	return $settings;
}

add_filter('growp_theme_customizer_settings', 'growp_customizer_settings', 1);

/**
 * Theme Scroll top
 * @since 1.2.0
 */

function growp_scroll_top()
{
	if ('true' === get_theme_mod('scroll_display', false)) {
		echo '<div id="scroll-top"><a href="#"><i class="fa fa-angle-up"></i></a></div>';
	}
}

add_action('get_footer', 'growp_scroll_top');

/**
 * Google Analytics - Tracking
 * @since 1.2.0
 */

function growp_tracking_code()
{
	$traking_code = get_theme_mod('tracking_code', false);
	if ($traking_code) {
		echo '<!-- Google Analytics -->
' . $traking_code .
			'
';
	}
}

add_action('wp_footer', 'growp_tracking_code');

/**
 * output meta tag to wp_head
 * @since 1.2.0
 */

function growp_meta_tag()
{

	$meta_tag = '';
	$meta_description = get_theme_mod('meta_description', false);
	$meta_keyword = get_theme_mod('meta_keyword', false);

	if ($meta_description) {
		$meta_tag .= '<meta name="description" content="' . esc_html($meta_description) . '">' . "\n";
	}

	if ($meta_keyword) {
		$meta_tag .= '<meta name="keywords" content="' . esc_html($meta_keyword) . '">' . "\n";
	}

	echo $meta_tag;

}

add_action('wp_head', 'growp_meta_tag', 10);

/**
 * output favicon tag to wp_head
 * @since 1.2.0
 */

function growp_favicon()
{

	$favicon_tag = '';
	$favicon = get_theme_mod('meta_favicon', false);


	if ($favicon) {
		$favicon_tag .= '<link rel="shortcut icon" href="' . esc_url($favicon) . '">' . "\n";
	}

	echo $favicon_tag;

}

add_action('wp_head', 'growp_favicon', 10);


/**
 * 抜粋の文字数を制限
 * @param $length
 * @return string
 */
function growp_excerpt_length($length)
{
	$thememod = get_theme_mod('single_char_num', $length);

	return $thememod;
}

add_filter('excerpt_length', 'growp_excerpt_length', 999);


/**
 * body タグに付与するクラスを調整
 * @param $classes
 * @return mixed
 */
function growp_body_class($classes)
{

	$layouts['top'] = get_theme_mod('growp_layout_top', 'l-two-column');
	$layouts['page'] = get_theme_mod('growp_layout_page', 'l-two-column');
	$layouts['single'] = get_theme_mod('growp_layout_single', 'l-two-column');

	$theme_style = get_theme_mod('growp_theme_style', 'normal');

	if (is_home() || is_front_page() || is_archive()) {

		$class = $layouts['top'];

	} elseif (is_archive() || is_page()) {

		$class = $layouts['page'];

	} elseif (is_single()) {

		$class = $layouts['single'];

	}
	$classes[] = $class;

	if (is_page()) {
		global $post;
		$classes[] = $post->post_name;
	}

	if ( $theme_style == 'blog' ){
		$classes[] = 'growp-blog';
	}


	return $classes;
}

add_filter('body_class', 'growp_body_class');
