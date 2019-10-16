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

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<?php
LayoutComponent::get( "head" );
?>
<body <?php body_class(); ?>>
<?php
do_action( "wp_body_open" );
Component::get( "slidebar" );
?>
<?php
echo Foundation::get_content();
?>

<?php
?>
<?php wp_footer(); ?>
</body>
</html>

