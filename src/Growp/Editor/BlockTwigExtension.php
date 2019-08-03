<?php

namespace Growp\Editor;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;
use function wp_get_attachment_image_url;

class BlockTwigExtension extends AbstractExtension {
	public $allowed_functions = [
		'get_template_part',
		'bloginfo',
		'get_bloginfo',
		'wp_get_document_title',
		'wp_title',
		'single_post_title',
		'post_type_archive_title',
		'single_cat_title',
		'single_tag_title',
		'single_term_title',
		'single_month_title',
		'the_archive_title',
		'get_the_archive_title',
		'the_archive_description',
		'get_the_archive_description',
		'get_the_post_type_description',
		'get_archives_link',
		'wp_get_archives',
		'calendar_week_mod',
		'get_calendar',
		'allowed_tags',
		'the_date',
		'get_the_date',
		'the_modified_date',
		'get_the_modified_date',
		'the_time',
		'get_the_time',
		'get_post_time',
		'the_modified_time',
		'get_the_modified_time',
		'get_post_modified_time',
		'the_weekday',
		'the_weekday_date',
		'feed_links',
		'feed_links_extra',
		'rsd_link',
		'wlwmanifest_link',
		'noindex',
		'wp_enqueue_editor',
		'wp_enqueue_code_editor',
		'wp_get_code_editor_settings',
		'get_search_query',
		'the_search_query',
		'paginate_links',
		'checked',
		'selected',
		'disabled',
		'readonly',
		'get_the_author',
		'the_author',
		'get_the_modified_author',
		'the_modified_author',
		'get_the_author_meta',
		'the_author_meta',
		'get_the_author_link',
		'the_author_link',
		'get_the_author_posts',
		'the_author_posts',
		'get_the_author_posts_link',
		'the_author_posts_link',
		'get_author_posts_url',
		'wp_list_authors',
		'is_multi_author',
		'get_category_link',
		'get_category_parents',
		'get_the_category',
		'get_the_category_by_ID',
		'get_the_category_list',
		'in_category',
		'the_category',
		'category_description',
		'wp_dropdown_categories',
		'wp_list_categories',
		'wp_tag_cloud',
		'default_topic_count_scale',
		'wp_generate_tag_cloud',
		'walk_category_tree',
		'walk_category_dropdown_tree',
		'get_tag_link',
		'get_the_tags',
		'get_the_tag_list',
		'the_tags',
		'tag_description',
		'term_description',
		'get_the_terms',
		'get_the_term_list',
		'get_term_parents_list',
		'the_terms',
		'has_category',
		'has_tag',
		'has_term',
		'get_taxonomies',
		'get_object_taxonomies',
		'get_taxonomy',
		'taxonomy_exists',
		'is_taxonomy_hierarchical',
		'get_taxonomy_labels',
		'get_objects_in_term',
		'get_term',
		'get_term_by',
		'get_term_children',
		'get_term_field',
		'get_term_to_edit',
		'get_terms',
		'get_term_meta',
		'has_term_meta',
		'term_exists',
		'term_is_ancestor_of',
		'sanitize_term',
		'sanitize_term_field',
		'wp_count_terms',
		'get_term_link',
		'the_taxonomies',
		'get_the_taxonomies',
		'get_post_taxonomies',
		'is_object_in_term',
		'is_object_in_taxonomy',
		'get_ancestors',
		'is_taxonomy_viewable',
		'locate_template',
		'load_template',
		'is_child_theme',
		'get_stylesheet',
		'get_stylesheet_directory',
		'get_stylesheet_directory_uri',
		'get_stylesheet_uri',
		'get_locale_stylesheet_uri',
		'get_template',
		'get_template_directory',
		'get_template_directory_uri',
		'get_theme_roots',
		'get_theme_root',
		'get_theme_root_uri',
		'get_raw_theme_root',
		'switch_theme',
		'get_theme_mods',
		'get_theme_mod',
		'set_theme_mod',
		'get_header_textcolor',
		'display_header_text',
		'has_header_image',
		'get_header_image',
		'get_header_image_tag',
		'the_header_image_tag',
		'get_uploaded_header_images',
		'get_custom_header',
		'wp_get_custom_css_post',
		'wp_get_custom_css',
		'get_editor_stylesheets',
		'get_theme_starter_content',
		'get_theme_support',
		'current_theme_supports',
		'require_if_theme_supports',
		'get_query_var',
		'get_queried_object',
		'get_queried_object_id',
		'query_posts',
		'wp_reset_query',
		'wp_reset_postdata',
		'is_archive',
		'is_post_type_archive',
		'is_attachment',
		'is_author',
		'is_category',
		'is_tag',
		'is_tax',
		'is_date',
		'is_day',
		'is_feed',
		'is_comment_feed',
		'is_front_page',
		'is_home',
		'is_privacy_policy',
		'is_month',
		'is_page',
		'is_paged',
		'is_preview',
		'is_robots',
		'is_search',
		'is_single',
		'is_singular',
		'is_time',
		'is_trackback',
		'is_year',
		'is_404',
		'is_embed',
		'is_main_query',
		'have_posts',
		'in_the_loop',
		'rewind_posts',
		'the_post',
		'have_comments',
		'the_comment',
		'wp_old_slug_redirect',
		'setup_postdata',
		'generate_postdata',
		'get_attached_file',
		'get_children',
		'get_extended',
		'get_post',
		'get_post_ancestors',
		'get_post_field',
		'get_post_mime_type',
		'get_post_status',
		'get_post_statuses',
		'get_page_statuses',
		'get_post_status_object',
		'get_post_stati',
		'is_post_type_hierarchical',
		'post_type_exists',
		'get_post_type',
		'get_post_type_object',
		'get_post_types',
		'get_post_type_capabilities',
		'get_post_type_labels',
		'get_all_post_type_supports',
		'post_type_supports',
		'get_post_types_by_support',
		'is_post_type_viewable',
		'get_posts',
		'get_post_meta',
		'get_post_custom',
		'get_post_custom_keys',
		'get_post_custom_values',
		'is_sticky',
		'wp_count_posts',
		'wp_count_attachments',
		'get_post_mime_types',
		'wp_match_mime_types',
		'wp_post_mime_type_where',
		'wp_get_post_categories',
		'wp_get_post_tags',
		'wp_get_post_terms',
		'wp_get_recent_posts',
		'wp_unique_post_slug',
		'wp_set_post_tags',
		'wp_set_post_terms',
		'wp_set_post_categories',
		'wp_transition_post_status',
		'get_enclosed',
		'get_pung',
		'trackback_url_list',
		'get_all_page_ids',
		'get_page',
		'get_page_by_path',
		'get_page_by_title',
		'get_page_children',
		'get_page_hierarchy',
		'get_page_uri',
		'get_pages',
		'is_local_attachment',
		'wp_get_attachment_metadata',
		'wp_get_attachment_url',
		'wp_get_attachment_caption',
		'wp_get_attachment_thumb_file',
		'wp_get_attachment_thumb_url',
		'wp_attachment_is',
		'wp_attachment_is_image',
		'wp_mime_type_icon',
		'wp_check_for_changed_slugs',
		'wp_check_for_changed_dates',
		'get_private_posts_cap_sql',
		'get_posts_by_author_sql',
		'get_lastpostdate',
		'get_lastpostmodified',
		'wp_get_post_parent_id',
		'the_ID',
		'get_the_ID',
		'the_title',
		'the_title_attribute',
		'get_the_title',
		'the_guid',
		'get_the_guid',
		'the_content',
		'get_the_content',
		'the_excerpt',
		'get_the_excerpt',
		'has_excerpt',
		'post_class',
		'get_post_class',
		'body_class',
		'get_body_class',
		'post_password_required',
		'wp_link_pages',
		'post_custom',
		'the_meta',
		'wp_dropdown_pages',
		'wp_list_pages',
		'wp_page_menu',
		'walk_page_tree',
		'walk_page_dropdown_tree',
		'the_attachment_link',
		'wp_get_attachment_link',
		'prepend_attachment',
		'get_the_password_form',
		'is_page_template',
		'get_page_template_slug',
		'wp_list_post_revisions',
		'get_categories',
		'get_category',
		'get_category_by_path',
		'get_category_by_slug',
		'get_cat_ID',
		'get_cat_name',
		'cat_is_ancestor_of',
		'get_tags',
		'get_tag',
		'clean_category_cache',
		'wptexturize',
		'wptexturize_primes',
		'wpautop',
		'wp_html_split',
		'get_html_split_regex',
		'wp_replace_in_html_tags',
		'shortcode_unautop',
		'seems_utf8',
		'wp_specialchars_decode',
		'wp_check_invalid_utf8',
		'utf8_uri_encode',
		'remove_accents',
		'sanitize_file_name',
		'sanitize_user',
		'sanitize_key',
		'sanitize_title',
		'sanitize_title_for_query',
		'sanitize_title_with_dashes',
		'sanitize_sql_orderby',
		'sanitize_html_class',
		'convert_chars',
		'convert_invalid_entities',
		'balanceTags',
		'force_balance_tags',
		'format_to_edit',
		'zeroise',
		'backslashit',
		'trailingslashit',
		'untrailingslashit',
		'addslashes_gpc',
		'stripslashes_deep',
		'stripslashes_from_strings_only',
		'urlencode_deep',
		'rawurlencode_deep',
		'urldecode_deep',
		'antispambot',
		'make_clickable',
		'wp_rel_nofollow',
		'wp_rel_nofollow_callback',
		'wp_targeted_link_rel',
		'wp_targeted_link_rel_callback',
		'wp_init_targeted_link_rel_filters',
		'wp_remove_targeted_link_rel_filters',
		'translate_smiley',
		'convert_smilies',
		'is_email',
		'wp_iso_descrambler',
		'get_gmt_from_date',
		'get_date_from_gmt',
		'iso8601_timezone_to_offset',
		'iso8601_to_datetime',
		'sanitize_email',
		'human_time_diff',
		'wp_trim_excerpt',
		'wp_trim_words',
		'ent2ncr',
		'format_for_editor',
		'esc_sql',
		'esc_url',
		'esc_url_raw',
		'htmlentities2',
		'esc_js',
		'esc_html',
		'esc_attr',
		'esc_textarea',
		'tag_escape',
		'wp_make_link_relative',
		'sanitize_option',
		'map_deep',
		'wp_parse_str',
		'wp_pre_kses_less_than',
		'wp_pre_kses_less_than_callback',
		'wp_sprintf',
		'wp_sprintf_l',
		'wp_html_excerpt',
		'links_add_base_url',
		'links_add_target',
		'normalize_whitespace',
		'wp_strip_all_tags',
		'sanitize_text_field',
		'is like sanitize_text_field',
		'sanitize_textarea_field',
		'wp_basename',
		'capital_P_dangit',
		'sanitize_mime_type',
		'sanitize_trackback_urls',
		'wp_slash',
		'wp_unslash',
		'get_url_in_content',
		'wp_spaces_regexp',
		'print_emoji_styles',
		'print_emoji_detection_script',
		'url_shorten',
		'sanitize_hex_color',
		'sanitize_hex_color_no_hash',
		'maybe_hash_hex_color',
		'date_i18n',
		'number_format_i18n',
		'current_time',
		'mysql2date',
		'get_field',
		'the_field',
		'get_the_permalink',
		'the_permalink',
	];

	public function getFilters() {
		return [
			new TwigFilter( 'esc_html', [ $this, 'esc_html' ], [ 'is_safe' => [ 'html' ] ] ),
			new TwigFilter( 'esc_attr', array( $this, 'esc_attr' ), array( 'is_safe' => array( 'html' ) ) ),
			new TwigFilter( 'esc_textarea', array( $this, 'esc_textarea' ), array( 'is_safe' => array( 'html' ) ) ),
			new TwigFilter( 'esc_url', array( $this, 'esc_url' ), array( 'is_safe' => array( 'html' ) ) ),
			new TwigFilter( 'esc_js', array( $this, 'esc_js' ), array( 'is_safe' => array( 'html' ) ) ),
			new TwigFilter( 'apply_filters', array( $this, 'apply_filters' ) ),
			new TwigFilter( 'the_permalink', array( $this, 'the_permalink' ), array( 'is_safe' => array( 'html' ) ) ),
			new TwigFilter( 'the_post_thumbnail', array( $this, 'the_post_thumbnail' ), array( 'is_safe' => array( 'html' ) ) ),
			new TwigFilter( 'the_excerpt', array( $this, 'the_excerpt' ), array( 'is_safe' => array( 'html' ) ) ),
			new TwigFilter( 'the_content', array( $this, 'the_content' ), array( 'is_safe' => array( 'html' ) ) ),
			new TwigFilter( 'the_author', array( $this, 'the_author' ), array( 'is_safe' => array( 'html' ) ) ),
			new TwigFilter( 'the_date', array( $this, 'the_date' ), array( 'is_safe' => array( 'html' ) ) ),
			new TwigFilter( 'the_title', array( $this, 'the_title' ), array( 'is_safe' => array( 'html' ) ) ),
		];
	}

	public function getFunctions() {

		$functions = [];
		foreach ( $this->allowed_functions as $function_name ) {
			$functions[] = new TwigFunction( $function_name, $function_name );
		}

		return $functions;
	}

	/**
	 * Get the date.
	 *
	 * @param int|object $post Post's ID or WP_Post object.
	 * @param string $d Optional. PHP date format defaults to the date_format option if not specified.
	 *
	 * @return string $date The date.
	 */
	public function the_date( $post, $d = '' ) {
		$post = get_post( $post );

		return get_the_date( $d, $post );
	}

	/**
	 * Get the date.
	 *
	 * @param int|object $post Post's ID or WP_Post object.
	 * @param string $d Optional. PHP date format defaults to the date_format option if not specified.
	 *
	 * @return string $date The date.
	 */
	public function the_title( $post ) {
		$post = get_post( $post );

		return get_the_title( $post );
	}

	/**
	 * Get the author.
	 *
	 * @param int|object $post Post's ID or WP_Post object.
	 *
	 * @return string $author The author.
	 */
	public function the_author( $post ) {
		$post = get_post( $post );
		$user = get_user_by( 'id', $post->post_author );

		return apply_filters( 'the_author', $user->data->display_name );
	}

	/**
	 * Get the excerpt.
	 *
	 * @param int|object $post Post's ID or WP_Post object.
	 *
	 * @return string $content The content.
	 */
	public function the_content( $post ) {
		$post = get_post( $post );

		return apply_filters( 'the_content', $post->post_content );
	}

	/**
	 * Get the excerpt.
	 *
	 * @param int|object $post Post's ID or WP_Post object.
	 *
	 * @return string $excerpt The excerpt of the post.
	 */
	public function the_excerpt( $post ) {
		$post = get_post( $post );

		return apply_filters( 'the_excerpt', $post->post_excerpt );
	}

	/**
	 * Get the permlink.
	 *
	 * @param int|object $post Post's ID or WP_Post object.
	 *
	 * @return string $permalink The permalink of the post.
	 */
	public function the_permalink( $post ) {
		return apply_filters( 'the_permalink', get_the_permalink( $post ) );
	}

	/**
	 * Get the post thumbnail.
	 *
	 * @param int|object $post Post's ID or WP_Post object.
	 *
	 * @return string $permalink The post thumbnail
	 */
	public function the_post_thumbnail( $post, $size = 'post-thumbnail' ) {
		if ( is_object( $post ) && 'WP_Post' === get_class( $post ) ) {
			$post_id = $post->ID;
		} elseif ( intval( $post ) ) {
			$post_id = $post;
		} else {
			return;
		}

		if ( has_post_thumbnail( $post_id ) ) {
			return get_the_post_thumbnail( $post_id, $size );
		}
	}

	public function esc_html( $content ) {
		return esc_html( $content );
	}

	public function esc_attr( $content ) {
		return esc_attr( $content );
	}

	public function esc_textarea( $content ) {
		return esc_textarea( $content );
	}

	public function esc_url( $content ) {
		return esc_url( $content );
	}

	public function esc_js( $content ) {
		return esc_js( $content );
	}

	public function apply_filters( $content, $filter ) {
		return apply_filters( $filter, $content );
	}

	public function getName() {
		return 'wp';
	}
}
