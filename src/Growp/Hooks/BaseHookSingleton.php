<?php

namespace Growp\Hooks;

class BaseHookSingleton {

	protected static $instance = null;

	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

}
