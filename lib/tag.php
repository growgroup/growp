<?php

class GTag
{

    /**
     * サムネイル画像のURLを取得
     *
     * @param bool $post_id
     * @param string $size
     *
     * @return false|string
     */
    public static function get_thumbnail_url($post_id = false, $size = "full")
    {
        if ( ! $post_id) {
            $post_id = get_the_ID();
        }

        $thumbnail_id = get_post_meta($post_id, "_thumbnail_id", true);
        $imageurl     = wp_get_attachment_image_url($thumbnail_id, $size);
        if ($imageurl) {
            return $imageurl;
        }
        return GUrl::asset("/assets/images/img-default-thumbnail.jpg");
    }


    /**
     * サムネイル画像のstyle属性を出力
     *
     * @param bool $post_id
     * @param string $size
     *
     * @return string
     */
    public static function the_thumbnail_style_attribute($post_id = false, $size = "full"){
        $url = self::get_thumbnail_url($post_id,$size);

        $attr = "";
        $attr = ' style="background-image: url('.$url.');"';
        echo $attr;
    }

    /**
     * 最初のタームを取得する
     *
     * @param bool $post_id 投稿のID
     * @param bool $taxonomy タクソノミー
     * @param string $field 取得したいフィールド
     *
     * @return bool
     */
    public static function get_first_term($post_id = false, $taxonomy = false, $field = "")
    {

        if ( ! $post_id) {
            $post_id = get_the_ID();
        }

        $terms = get_the_terms($post_id, $taxonomy);
        if (is_wp_error($terms)) {
            return false;
        }

        if ($field && isset($terms[0]->{$field})) {
            return $terms[0]->{$field};
        }

        return $terms[0];
    }


    /**
     * アーカイブのタイトルを取得する
     * @return string|void
     */
    public static function get_archive_title()
    {
        if (is_category()) {
            $title = sprintf(__('Category: %s'), single_cat_title('', false));
        } elseif (is_tag()) {
            $title = sprintf(__('Tag: %s'), single_tag_title('', false));
        } elseif (is_author()) {
            $title = sprintf(__('Author: %s'), '<span class="vcard">' . get_the_author() . '</span>');
        } elseif (is_year()) {
            $title = sprintf(__('Year: %s'), get_the_date(_x('Y', 'yearly archives date format')));
        } elseif (is_month()) {
            $title = sprintf(__('Month: %s'), get_the_date(_x('F Y', 'monthly archives date format')));
        } elseif (is_day()) {
            $title = sprintf(__('Day: %s'), get_the_date(_x('F j, Y', 'daily archives date format')));
        } elseif (is_tax('post_format')) {
            if (is_tax('post_format', 'post-format-aside')) {
                $title = _x('Asides', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-gallery')) {
                $title = _x('Galleries', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-image')) {
                $title = _x('Images', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-video')) {
                $title = _x('Videos', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-quote')) {
                $title = _x('Quotes', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-link')) {
                $title = _x('Links', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-status')) {
                $title = _x('Statuses', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-audio')) {
                $title = _x('Audio', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-chat')) {
                $title = _x('Chats', 'post format archive title');
            }
        } elseif (is_post_type_archive()) {
            $title = sprintf(__('Archives: %s'), post_type_archive_title('', false));
        } elseif (is_tax()) {
            $tax = get_taxonomy(get_queried_object()->taxonomy);
            /* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
            $title = sprintf(__('%1$s: %2$s'), $tax->labels->singular_name, single_term_title('', false));
        } else {
            $title = __('Archives');
        }

        return $title;

    }
    /**
     * 親ページを判断
     *
     * @param $slug
     *
     * @return bool
     */
    function is_parent_page($slug)
    {
        global $post;
        $return = false;
        if (is_string($slug)) {
            $return = self::_is_parent_page($slug);
        }
        if (is_array($slug)) {
            foreach ($slug as $s) {
                $return = self::_is_parent_page($s);
                if ($return === true) {
                    break;
                }
            }
        }
        return $return;
    }


    private static function _is_parent_page( $slug ){
        global $post;
        if ($post->post_name === $slug) {
            return true;
        }

        if ( ! $post->post_parent) {
            return false;
        }

        $parent_post = get_post($post->post_parent);
        if ($parent_post->post_name === $slug) {
            return true;
        }
    }

}
