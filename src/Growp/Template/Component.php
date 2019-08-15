<?php

namespace Growp\Template;

use function acf_is_block_editor;

class Component extends BaseComponent {

	public function set_dir() {
		$this->dir = 'views/object/components';
	}

	/**
	 * 出力する
	 * @return BaseComponent
	 */
	public function render() {
		$file_path = get_theme_file_path( $this->dir . "/" . $this->component_name . ".php" );
		if ( $file_path && file_exists( $file_path ) ) {
			$vars            = $this->vars;
			$this->file_path = $file_path;
			include $file_path;

			return $this;
		}

		return $this;
	}
}
