<?php
/**
 * Navbar module
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */
?>

<nav class="l-global-navigation">
    <div class="l-container">
        <?php
        // メニューをレンダリング
        GNav::render_menus("global_nav");
        ?>
    </div>
</nav>
