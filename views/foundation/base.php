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

use Growp\Template\Component;
use Growp\Template\Foundation;
use Growp\Template\LayoutComponent;

LayoutComponent::get( "head" );
LayoutComponent::get( "header" );
LayoutComponent::get( "global-nav" );
Component::get( "mainvisual" );
Component::get( "page-header" );

echo Foundation::get_content();

Component::get( "offer" );

// フッターを取得
LayoutComponent::get( "footer" );
