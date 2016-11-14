<?php
/**
 * Class GTemplate
 * テンプレートの操作関連
 */


class GTemplate
{
    /**
     * テンプレートを取得する
     *
     * @param $path ファイル名
     *
     * @return bool
     */
    public static function get_template($path)
    {
        $file_path = TEMPLATE_PATH . "/views/" . $path . ".php";
        if (file_exists($file_path)) {
            include $file_path;

            return true;
        }
        self::error();
    }

    /**
     * レイアウトを取得する
     *
     * @param $file ファイル名
     *
     * @return bool
     */
    public static function get_layout($file)
    {
        $file_path = TEMPLATE_PATH . "/views/layout/" . $file . ".php";
        if (file_exists($file_path)) {
            include $file_path;

            return true;
        }
        self::error();
    }

    /**
     * コンポーネントを取得する
     *
     * @param $file コンポーネント名
     *
     * @return bool
     */
    public static function get_component($file)
    {
        $file_path = TEMPLATE_PATH . "/views/object/components/" . $file . ".php";

        if (file_exists($file_path)) {
            include $file_path;

            return true;
        }
        self::error();
    }

    /**
     * コンポーネントを取得する
     *
     * @param $file コンポーネント名
     *
     * @return bool
     */
    public static function get_project($file)
    {
        $file_path = TEMPLATE_PATH . "/views/object/project/" . $file . ".php";

        if (file_exists($file_path)) {
            include $file_path;

            return true;
        }
        self::error();
    }

    /**
     * WordPressにログインしている時に取得する
     */
    private static function error()
    {
        if (is_user_logged_in()) {
            echo "テンプレートがありません";
        }
    }


}
