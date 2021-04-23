<?php
/**
 * ベーステンプレート
 * : テンプレート階層を上書きし、
 * 基本的にこのテンプレートを先に読み込みます。
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */

$content_save = GTemplate::get_content();
$content      = do_shortcode( $content_save );

// 見出し内の改行によるスペースを無くすための処理
$content = str_replace( "\t", "", $content );
$content = str_replace( "\n", "", $content );
$content = str_replace( "\r", "", $content );

wp_reset_query();

//if ( is_page() ) {
//        $post_id = get_the_ID();
//        $_post   = get_post( $post_id );
//        if ( ! $_post->post_content ) {
//                // 改行は取り除いた上で挿入する＜ビジュアルエディタからHTMLエディタに変えたときの変な改行を防ぐため＞
//                $insert_content = str_replace( "\n", "", $content_save);
//                $insert_content .= '<div style="display:none;"><p>&nbsp;</p></div>';
//                wp_update_post( [
//                        "ID"           => $post_id,
//                        "post_content" => $insert_content,
//                        "page_template" => "template-allhtml.php"
//                ] );
//        }
//}

GTemplate::get_template( "foundation/head" );
GTemplate::get_layout( "header" );
//GTemplate::get_layout( "global-nav" );
GTemplate::get_component( "mainvisual" );
GTemplate::get_component( "page-header" );
$wrapper = apply_filters( 'growp/wrapper', 'onecolumn' );

// 1カラム用
if ( $wrapper === "onecolumn" ) {
        ?>
        <section class="l-main">
                <?php
                echo $content;
                unset( $content );
                ?>
        </section>
        <?php
// 2カラム用
} else {
        ?>
        <div class="l-wrapper">
                <div class="l-container is-two-columns">
                        <section class="l-main is-two-columns">
                                <?php
                                echo $content;
                                unset( $content );
                                ?>
                        </section>
                        <aside class="l-aside" data-sticky-container>
                                <?php
                                // サイドバー
                                GTemplate::get_layout( "sidebar" );
                                ?>
                        </aside>
                </div>
        </div>
        <?php
}

// フッター取得前のアクションフック
do_action( 'get_footer' );
// フッターを取得
GTemplate::get_layout( "footer" );
