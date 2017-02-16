<?php

/**
 * MW WP FORM への初期コンテンツの追加
 */
class GROWP_Mw_Wp_Form_Mock extends GROWP_Create_Mock
{

    public function __construct()
    {
        parent::__construct("mw-wp-form-mock");
    }

    protected function run()
    {
        $defaults_mw_wp_form = array(
            'post_title'   => "お問い合わせフォーム",
            'post_type'    => 'mw-wp-form',
            'post_name'    => 'mw-wp-form-contact',
            'post_status'  => 'publish',
            'post_content' => " ",
        );
        $mw_wp_form_id       = $this->insert_post($defaults_mw_wp_form)->get_insert_id($defaults_mw_wp_form["post_name"]);
        /**
         * postmeta 初期値
         */
        $defaults_meta = array(
            'querystring'           => false,
            'usedb'                 => '1',
            'mail_subject'          => '【自動返信】 お問い合わせありがとうございます。',
            'mail_from'             => '',
            'mail_sender'           => '',
            'mail_content'          => '※このメールはお問い合わせをいただいた方へ自動で返信されています。
お心当たりのない場合は、URLにはアクセスせずメールを破棄してください。

{c_name}様
この度はお問い合わせいただき、誠にありがとうございます。

ご確認後、担当者より追ってご返答いたしますので、
今しばらくお待ちくださいませ。

ご入力いただいたお問い合わせ内容は以下の通りです。

‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥

【 お問い合わせ内容 】

[お問い合わせ種別]
{c_type}

[氏名]
{c_name}

[フリガナ]
{c_furigana}

[メールアドレス]
{c_email}

[お問合わせ内容]
{c_message}

‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥

また、ご記入内容に不備がある場合は大変お手数をお掛け致しますが、
もう一度お問い合わせをお願い致します。
',
            'automatic_reply_email' => 'c_email',
            'mail_to'               => '',
            'mail_cc'               => '',
            'mail_bcc'              => '',
            'admin_mail_subject'    => '【ご担当者様へ】御社Webサイトよりお問い合わせが届きました。',
            'mail_return_path'      => '',
            'admin_mail_from'       => '',
            'admin_mail_sender'     => '',
            'admin_mail_content'    => '※このメールはWEBサイトにお問い合わせが届いたことを、
自動でお知らせするメールです。特に関係のない場合は、URLにアクセスせずメールを破棄してください。

サイト名
ご担当者様

御社WEBサイトから、以下の内容でお問い合わせが届きました。
ご対応をお願い申し上げます。

────────────────────────────────

【 お問い合わせのあったサイト 】
' . site_url("/") . '

────────────────────────────────

【 お問い合わせ内容 】

[お問い合わせ種別]
{c_type}

[氏名]
{c_name}

[フリガナ]
{c_furigana}

[メールアドレス]
{c_email}

[お問合わせ内容]
{c_message}

────────────────────────────────
',
            'akismet_author'        => '',
            'akismet_author_email'  => '',
            'akismet_author_url'    => '',
            'complete_message'      => '',
            'input_url'             => '/contact/',
            'confirmation_url'      => '/contact/confirm/',
            'complete_url'          => '/contact/complete/',
            'validation_error_url'  => '',
            'validation'            => array(
                0 =>
                    array(
                        'target' => 'c_type',
                    ),
                1 =>
                    array(
                        'target' => 'c_name',
                    ),
                2 =>
                    array(
                        'target'  => 'c_furigana',
                        'noempty' => '1',
                    ),
                3 =>
                    array(
                        'target'  => 'c_email',
                        'noempty' => '1',
                    ),
                4 =>
                    array(
                        'target'  => 'c_message',
                        'noempty' => '1',
                    ),
            ),
            'style'                 => '',
            'scroll'                => '1',
        );

        update_post_meta($mw_wp_form_id, "mw-wp-form", $defaults_meta);

        $common = array(
            'post_type'      => "page",
            'post_status'    => "publish",
            'post_date'      => date_i18n('Y-m-d H:i:s'),
            'comment_status' => false
        );

        $pages = array();

        $pages["contact"] = array(
            'post_title' => "お問い合わせ",
            'post_name'  => "contact",
        );

        $pages["confirm"] = array(
            'post_title'   => "お問い合わせ内容のご確認",
            'post_name'    => "confirm",
            'post_content' => '<p>ご入力いただいた内容は下記の通りです。<br>
お間違いがなければ送信を、入力内容を変更する場合はお手数ですが、<br>
再入力をお願い致します。</p>'
        );

        $pages["complete"] = array(
            'post_title'   => "お問い合わせありがとうございます",
            'post_name'    => "complete",
            'post_content' => '<p>この度はお問い合わせいただき、誠にありがとうございます。<br>
いただいた内容を確認後、担当者より再度ご連絡いたします。<br>
大変恐縮ですが、今しばらくお待ちくださいませ。<br>
<br>
※尚、ご入力いただいたメールアドレス宛に<br>
お問い合わせ内容を記載した自動返信メールが配信されております。<br>
そちらも併せてご確認くださいませ。<br>
<p>
<p class="u-text-center">
<a href="/" class="c-button">トップへ戻る</a>
</p>'
        );

        $shortcode = '[mwform_formkey key="' . $mw_wp_form_id . '"]';

        $contact_post_id = 0;
        foreach ($pages as $key => $p) {
            $_page = wp_parse_args($p, $common);
            if (empty($_page["post_content"])) {
                $_page["post_content"] = "";
            }
            $_page["post_content"] = $_page["post_content"] . "" . $shortcode;
            $_page["post_parent"]  = $contact_post_id;
            $insert_id             = $this->insert_post($_page)->get_insert_id($_page["post_name"]);
            if ($key === "contact") {
                $contact_post_id = $insert_id;
            }
        }
    }
}
