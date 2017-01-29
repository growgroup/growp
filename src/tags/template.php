<?php

/**
 * Class GTemplate
 * テンプレートの操作関連
 */
class GTemplate
{
    /**
     * テンプレートをインクルード
     * @param $file
     * @param array $data
     *
     * @return bool
     */
    public static function get_template($file, $data = array())
    {
        $file_path = TEMPLATE_PATH . "/views/" . $file . ".php";
        if (file_exists($file_path)) {
            include $file_path;

            return true;
        }
        self::error();
    }

    /**
     * レイアウト用コンポーネントをインクルード
     * @param $file
     * @param array $data
     */
    public static function get_layout($file, $data = array())
    {
        self::get_template('layout/' . $file, $data);
    }

    /**
     * コンポーネントをインクルード
     * @param $file
     * @param array $data
     */
    public static function get_component($file, $data = array())
    {
        self::get_template('object/components/' . $file, $data);
    }

    /**
     * プロジェクト依存コンポーネントをインクルード
     * @param $file
     * @param array $data
     */
    public static function get_project($file, $data = array())
    {
        self::get_template('object/project/' . $file, $data);
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
