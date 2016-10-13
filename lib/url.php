<?php

/**
 * Class GUrl
 * URL関連の処理
 */
class GUrl
{

    /**
     * URLを取得
     *
     * @param string $path
     *
     * @return string
     */
    public static function url($path = "")
    {
        return esc_url(home_url($path));
    }

    /**
     * 子テーマディレクトリのURLを取得する
     *
     * @param $path
     *
     * @return string
     */
    public static function asset($path = "/assets"){
        return esc_url( get_stylesheet_directory_uri() . $path );
    }

}
