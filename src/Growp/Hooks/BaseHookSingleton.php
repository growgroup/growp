<?php

namespace Growp\Hooks;

class BaseHookSingleton {

	protected static $instance = null;

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}
