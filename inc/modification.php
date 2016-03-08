<?php
/**
 * テーマカスタマイザーの設定
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.2.0
 * =====================================================''
 */
/**
 * テーマカスタマイザーを拡張
 * @since 1.2.0
 */
function epigone_customizer_settings()
{

	$settings['epigone_general'] = array(
		'title' => __('General', 'epigone'),
		'description' => __('Please have a set of general setting.', 'epigone'),
		'section' => array(
			'epigone_google_analytics' => array(
				'title' => __('Google Analytics - Tracking code', 'epigone'),
				'setting' => array(
					'tracking_code' => array(
						'label' => __('Tracking Code', 'epigone'),
						'default' => '',
						'type' => 'textarea',
						'sanitaize_call_back' => '',
					),
				),
			),
			'epigone_meta_description' => array(
				'title' => __('Meta Description', 'epigone'),
				'setting' => array(
					'meta_description' => array(
						'label' => __('Meta Description', 'epigone'),
						'default' => get_bloginfo('description'),
						'type' => 'textarea',
						'sanitaize_call_back' => '',
					),
				),
			),
			'epigone_meta_keyword' => array(
				'title' => __('Meta Keyword', 'epigone'),
				'setting' => array(
					'meta_keyword' => array(
						'label' => __('Meta Keyword', 'epigone'),
						'default' => '',
						'type' => 'text',
						'sanitaize_call_back' => '',
					),
				),
			),
			'epigone_favicon' => array(
				'title' => __('Favicon', 'epigone'),
				'description' => __('16  16 px .ico file or .png', 'epigone'),
				'setting' => array(
					'meta_favicon' => array(
						'label' => __('Favicon', 'epigone'),
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
	$settings['epigone_header'] = array(
		'title' => __('Header', 'epigone'), // Panel title
		'description' => __('Please have a set of headers.', 'epigone'),
		'section' => array(
			'epigone_header_style' => array(
				'title' => __('Header Style', 'epigone'),
				'setting' => array(
					'header_style' => array(
						'label' => __('Header Style', 'epigone'),
						'default' => 'top',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'top' => __('Navigation : Top', 'epigone'),
							'bottom' => __('Navigation : Bottom', 'epigone'),
							'none' => __('Navigation : None', 'epigone'),
							'header_none' => __('Header : None', 'epigone'),
						),
					),
					'header_text_align' => array(
						'label' => __('Header Text Align', 'epigone'),
						'default' => 'left',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'left' => __('Text Align: Left', 'epigone'),
							'center' => __('Text Align: Center', 'epigone'),
							'right' => __('Text Align: Right', 'epigone'),
						),
						'output' => array(
							'.header' => 'text-align',
						),
					),
				),
			),
			'epigone_logo' => array(
				'title' => __('Logo', 'epigone'),
				'setting' => array(
					'logo_image' => array(
						'label' => __('Logo Image', 'epigone'),
						'default' => '',
						'type' => 'multi-image',
						'sanitaize_call_back' => '',
					),
					'logo_font_size' => array(
						'label' => __('Font Size', 'epigone'),
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
						'label' => __('Color', 'epigone'),
						'default' => '#FFFFFF',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'.header__logo a,.header__description' => 'color',
						)
					),
				)
			),

			'epigone_header' => array(
				'title' => __('Background', 'epigone'),
				'setting' => array(
					'header_background_image' => array(
						'label' => __('Background Image', 'epigone'),
						'default' => '',
						'type' => 'multi-image',
						'sanitaize_call_back' => '',
						'output' => array(
							'#masthead' => 'background-image',
						)
					),
					'header_background_attachment' => array(
						'label' => __('Background Attachment', 'epigone'),
						'default' => 'fixed',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'fixed' => __('Fixed', 'epigone'),
							'scroll' => __('Scroll', 'epigone'),
						),
						'output' => array(
							'#masthead' => 'background-attachment',
						)
					),
					'header_background_color' => array(
						'label' => __('Background Color', 'epigone'),
						'default' => '#e8e8e8',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'#masthead' => 'background-color',
						),
					),
					'header_background_size' => array(
						'label' => __('Background Size', 'epigone'),
						'default' => '100% auto',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'auto auto' => __('Horizontal : auto Vertical : auto', 'epigone'),
							'100% auto' => __('Horizontal : 100%, Vertical : auto', 'epigone'),
							'auto 100%' => __('Horizontal : auto, Vertical : 100%', 'epigone'),
							'100% 100%' => __('Horizontal : 100%, Vertical : 100%', 'epigone'),
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
	$settings['epigone_theme_color'] = array(
		'title' => __('Theme Style', 'epigone'), // Panel title
		'section' => array(
			'epigone_theme_style' => array(
				'title' => __('Theme Style ', 'epigone'),
				'description' => __('Setting for Theme color.', 'epigone'),
				'setting' => array(
					'epigone_theme_style' => array(
						'label' => __('Theme Style', 'epigone'),
						'default' => 'left',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'normal' => __('Normal', 'epigone'),
							'blog' => __('Blog', 'epigone'),
						),
						'output' => array(
							'.header' => 'text-align',
						),
					),
				),
			),
			// theme color section
			'epigone_theme_color' => array(
				'title' => __('Theme Color ', 'epigone'),
				'description' => __('Setting for Theme color.', 'epigone'),
				'setting' => array(
					'theme_color' => array(
						'label' => __('Theme Color', 'epigone'),
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
	$settings['epigone_body'] = array(
		'title' => __('Body Settings', 'epigone'), // Panel title
		'description' => __('Please have a set of body.', 'epigone'),
		'section' => array(
			'epigone_body' => array(
				'title' => __('Body ', 'epigone'),
				'setting' => array(
					'body_background_color' => array(
						'label' => __('Background Color', 'epigone'),
						'default' => '#FFFFFF',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'body' => 'background-color',
						),
					),
					'body_background_image' => array(
						'label' => __('Background Image', 'epigone'),
						'default' => 'transparent',
						'type' => 'multi-image',
						'sanitaize_call_back' => '',
						'output' => array(
							'body' => 'background-image',
						)
					),
					'body_background_attachment' => array(
						'label' => __('Background Attachment', 'epigone'),
						'default' => 'fixed',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'fixed' => __('Fixed', 'epigone'),
							'initial' => __('Initial', 'epigone'),
						),
						'output' => array(
							'body' => 'background-attachment',
						)
					),
					'body_background_size' => array(
						'label' => __('Background Size', 'epigone'),
						'default' => '100% auto',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'auto auto' => __('Horizontal : auto Vertical : auto', 'epigone'),
							'100% auto' => __('Horizontal : 100%, Vertical : auto', 'epigone'),
							'auto 100%' => __('Horizontal : auto, Vertical : 100%', 'epigone'),
							'100% 100%' => __('Horizontal : 100%, Vertical : 100%', 'epigone'),
						),
						'output' => array(
							'body' => 'background-size',
						)
					),
				)
			),
			'epigone_heading' => array(
				'title' => __('Headings', 'epigone'),
				'setting' => array(
					'heading_color' => array(
						'label' => __('Heading Color', 'epigone'),
						'default' => '#3695b5',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'.entry__title,.entry__title a,.page-header .page-title,h1,h2,h3,h4,h5,h6,.widget-title' => 'color',
						),
					),
					'heading_1_font_size' => array(
						'label' => __('H1 Font Size', 'epigone'),
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
						'label' => __('H2 Font Size', 'epigone'),
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
						'label' => __('H3 Font Size', 'epigone'),
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
						'label' => __('H4 Font Size', 'epigone'),
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
						'label' => __('H5 Font Size', 'epigone'),
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
						'label' => __('H6 Font Size', 'epigone'),
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
			'epigone_text' => array(
				'title' => __('Text', 'epigone'),
				'setting' => array(
					'text_color' => array(
						'label' => __('Text Color', 'epigone'),
						'default' => '#333',
						'type' => 'color',
						'sanitaize_call_back' => '',
						'output' => array(
							'body' => 'color',
							'.entry__content p,.entry__content blockquote,.entry__content code,.entry__content pre,.entry__content dl,.entry__content dt,.entry__content dd,.entry__content table,.entry__content ul' => 'color',
						),
					),
					'text_font_size' => array(
						'label' => __('Base Font Size', 'epigone'),
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
	$settings['epigone_home'] = array(
		'title' => __('Home Page Settings', 'epigone'), // Panel title
		'description' => __('Please settings for home page.', 'epigone'),
		'section' => array(
			'epigone_home_post_list' => array(
				'title' => __('Post List Style', 'epigone'),
				'description' => __('Please select the style of Posts List', 'epigone'),
				'setting' => array(
					'home_post_list' => array(
						'label' => __('Post List Style', 'epigone'),
						'default' => 'normal',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'normal' => __('Normal', 'epigone'),
							'tile' => __('Tile', 'epigone'),
						),
					),
				)
			),
		)
	);


	/**
	 * 03. 投稿記事設定
	 */

	$settings['epigone_single'] = array(
		'title' => __('Single Post Settings', 'epigone'), // Panel title
		'description' => __('Please settings for single post.', 'epigone'),
		'section' => array(
			'epigone_single_thumbnail' => array(
				'title' => __('Display Thumbnail', 'epigone'),
				'description' => __('Do you want to display a thumbnail?', 'epigone'),
				'setting' => array(
					'single_thumbnail' => array(
						'label' => __('Display Thumbnail', 'epigone'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'epigone'),
							'false' => __('None', 'epigone'),
						),
					),
				)
			),
			'epigone_single_char_num' => array(
				'title' => __('Except Number of characters', 'epigone'),
				'description' => __('Do you want to display a excerpt?', 'epigone'),
				'setting' => array(
					'single_char_num' => array(
						'label' => __('Except Number of characters', 'epigone'),
						'default' => '100',
						'type' => 'number',
						'sanitaize_call_back' => '',

					),
				)
			),
			'epigone_single_post_date' => array(
				'title' => __('Display Date', 'epigone'),
				'description' => __('Do you want to display a posted date?', 'epigone'),
				'setting' => array(
					'single_post_date' => array(
						'label' => __('Display Date', 'epigone'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'epigone'),
							'false' => __('None', 'epigone'),
						),
					),
				)
			),
			'epigone_single_post_author' => array(
				'title' => __('Display Author', 'epigone'),
				'description' => __('Do you want to display a posted author?', 'epigone'),
				'setting' => array(
					'single_post_author' => array(
						'label' => __('Display Author', 'epigone'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'epigone'),
							'false' => __('None', 'epigone'),
						),
					),
				)
			),
			'epigone_single_post_category' => array(
				'title' => __('Display Category', 'epigone'),
				'description' => __('Do you want to display a category?', 'epigone'),
				'setting' => array(
					'single_post_category' => array(
						'label' => __('Display Category', 'epigone'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'epigone'),
							'false' => __('None', 'epigone'),
						),
					),
				)
			),
			'epigone_single_post_tags' => array(
				'title' => __('Display Tags', 'epigone'),
				'description' => __('Do you want to display a tag?', 'epigone'),
				'setting' => array(
					'single_post_tags' => array(
						'label' => __('Display Tags', 'epigone'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'epigone'),
							'false' => __('None', 'epigone'),
						),
					),
				)
			),
			'epigone_single_comment_num' => array(
				'title' => __('Display Comment Number', 'epigone'),
				'description' => __('Do you want to display the comment number?', 'epigone'),
				'setting' => array(
					'single_comment_num' => array(
						'label' => __('Do you want to display the comment number?', 'epigone'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'epigone'),
							'false' => __('None', 'epigone'),
						),
					),
				)
			),
			'epigone_single_related_post' => array(
				'title' => __('Display Related Post', 'epigone'),
				'description' => __('Do you want to display the associated article?', 'epigone'),
				'setting' => array(
					'single_related_post' => array(
						'label' => __('Do you want to display the associated article?', 'epigone'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'epigone'),
							'false' => __('None', 'epigone'),
						),
					),
				)
			),
		)
	);
	/**
	 * 03. Layout
	 */

	$settings['epigone_layout'] = array(
		'title' => __('Layout Settings', 'epigone'), // Panel title
		'description' => __('Please have a set of layout.', 'epigone'),
		'section' => array(
			'epigone_layout_top' => array(
				'title' => __('Top Page', 'epigone'),
				'description' => __('Please select the layout.', 'epigone'),
				'setting' => array(
					'epigone_layout_top' => array(
						'label' => __('Layout of top page', 'epigone'),
						'default' => 'l-right-sidebar',
						'type' => 'layout-picker',
						'sanitaize_call_back' => '',
					),
				)
			),
			'epigone_layout_page' => array(
				'title' => __('static page', 'epigone'),
				'description' => __('Please select the layout.', 'epigone'),
				'setting' => array(
					'epigone_layout_page' => array(
						'label' => __('Layout of static page', 'epigone'),
						'default' => 'l-right-sidebar',
						'type' => 'layout-picker',
						'sanitaize_call_back' => '',
					),
				)
			),
			'epigone_layout_single' => array(
				'title' => __('single page', 'epigone'),
				'description' => __('Please select the layout.', 'epigone'),
				'setting' => array(
					'epigone_layout_single' => array(
						'label' => __('Layout of single page', 'epigone'),
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
	$settings['epigone_footer'] = array(
		'title' => __('Footer', 'epigone'), // Panel title
		'description' => __('Please have a set of footer.', 'epigone'),
		'section' => array(

			'epigone_footer' => array(
				'title' => __('Background', 'epigone'),
				'setting' => array(
					'footer_background_image' => array(
						'label' => __('Background Image', 'epigone'),
						'default' => get_template_directory_uri() . '/assets/images/footer-bg.png',
						'type' => 'multi-image',
						'sanitaize_call_back' => '',
						'output' => array(
							'#colophon' => 'background-image',
						)
					),
					'footer_background_attachment' => array(
						'label' => __('Background Attachment', 'epigone'),
						'default' => 'fixed',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'fixed' => __('Fixed', 'epigone'),
							'scroll' => __('Scroll', 'epigone'),
						),
						'output' => array(
							'#colophon' => 'background-attachment',
						)
					),
					'footer_background_color' => array(
						'label' => __('Background Color', 'epigone'),
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
			'epigone_scrolltop' => array(
				'title' => __('Scroll Top', 'epigone'),
				'description' => __('Setting for Scroll top.', 'epigone'),
				'setting' => array(
					'scroll_display' => array(
						'label' => __('Display', 'epigone'),
						'default' => 'true',
						'type' => 'radio',
						'sanitaize_call_back' => '',
						'choices' => array(
							'true' => __('Yes', 'epigone'),
							'false' => __('None', 'epigone'),
						)
					),
					'scroll_background_color' => array(
						'label' => __('Page Top Background', 'epigone'),
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
			'epigone_copyright' => array(
				'title' => __('Copyright', 'epigone'),
				'description' => __('Setting for Copyright.', 'epigone'),
				'setting' => array(
					'copyright_text' => array(
						'label' => __('Copyright text', 'epigone'),
						'default' => 'copyright © ' . get_the_date('Y') . get_bloginfo('name'),
						'type' => 'text',
						'sanitaize_call_back' => '',
					),
					'copyright_background' => array(
						'label' => __('Copyright Background', 'epigone'),
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

add_filter('epigone_theme_customizer_settings', 'epigone_customizer_settings', 1);

/**
 * Theme Scroll top
 * @since 1.2.0
 */

function epigone_scroll_top()
{
	if ('true' === get_theme_mod('scroll_display', false)) {
		echo '<div id="scroll-top"><a href="#"><i class="fa fa-angle-up"></i></a></div>';
	}
}

add_action('get_footer', 'epigone_scroll_top');

/**
 * Google Analytics - Tracking
 * @since 1.2.0
 */

function epigone_tracking_code()
{
	$traking_code = get_theme_mod('tracking_code', false);
	if ($traking_code) {
		echo '<!-- Google Analytics -->
' . $traking_code .
			'
';
	}
}

add_action('wp_footer', 'epigone_tracking_code');

/**
 * output meta tag to wp_head
 * @since 1.2.0
 */

function epigone_meta_tag()
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

add_action('wp_head', 'epigone_meta_tag', 10);

/**
 * output favicon tag to wp_head
 * @since 1.2.0
 */

function epigone_favicon()
{

	$favicon_tag = '';
	$favicon = get_theme_mod('meta_favicon', false);


	if ($favicon) {
		$favicon_tag .= '<link rel="shortcut icon" href="' . esc_url($favicon) . '">' . "\n";
	}

	echo $favicon_tag;

}

add_action('wp_head', 'epigone_favicon', 10);


/**
 * 抜粋の文字数を制限
 * @param $length
 * @return string
 */
function epigone_excerpt_length($length)
{
	$thememod = get_theme_mod('single_char_num', $length);

	return $thememod;
}

add_filter('excerpt_length', 'epigone_excerpt_length', 999);


/**
 * body タグに付与するクラスを調整
 * @param $classes
 * @return mixed
 */
function epigone_body_class($classes)
{

	$layouts['top'] = get_theme_mod('epigone_layout_top', 'l-two-column');
	$layouts['page'] = get_theme_mod('epigone_layout_page', 'l-two-column');
	$layouts['single'] = get_theme_mod('epigone_layout_single', 'l-two-column');

	$theme_style = get_theme_mod('epigone_theme_style', 'normal');

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
		$classes[] = 'epigone-blog';
	}


	return $classes;
}

add_filter('body_class', 'epigone_body_class');
