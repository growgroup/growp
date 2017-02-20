#!/usr/bin/env bash

WP_TESTS_DIR=${WP_TESTS_DIR-/tmp/wordpress-tests-lib}
WP_CORE_DIR=${WP_CORE_DIR-/tmp/wordpress}

WP_TESTS_CONFIG="$WP_TESTS_DIR/wp-tests-config.php"
WP_CORE_CONFIG="$WP_CORE_DIR/wp-config.php"
install_theme_unit_test_ja() {
  if [ ! -d $WP_TESTS_DIR ]; then
  	exit 0;
  fi
  if [ ! -d $WP_CORE_DIR ]; then
  	exit 0;
  fi

  if [ ! -f $WP_CORE_CONFIG ]; then
  	cp $WP_TESTS_CONFIG $WP_CORE_CONFIG

  	echo 'require_once(ABSPATH . "wp-settings.php");' >> /tmp/wordpress/wp-config.php
  fi

  wp --path=/tmp/wordpress/ plugin install --activate wordpress-importer
  wp --path=/tmp/wordpress/ plugin is-installed wordpress-importer

  wget https://raw.githubusercontent.com/jawordpressorg/theme-test-data-ja/master/wordpress-theme-test-date-ja.xml
  wp --path=/tmp/wordpress/ import "$WP_TESTS_DIR/wordpress-theme-test-date-ja.xml" --authors=create --quiet
  wp --path=/tmp/wordpress/ menu create global_nav
  wp --path=/tmp/wordpress/ menu item global_nav i

}

install_theme_unit_test_ja
