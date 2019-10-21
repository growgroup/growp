<?php

namespace Growp\Resource;

use const DIRECTORY_SEPARATOR;
use Exception;
use Gajus\Dindent\Indenter;
use Symfony\Component\Cache\Exception\InvalidArgumentException;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Finder\Finder;
use function usort;

class Resource {
	/**
	 * テーマ内の静的ファイル格納ディレクトリ
	 * @var string
	 */
	private $base_dir_name = "resource";

	// HTMLが格納されているディレクトリ
	private $html_dir = "";

	// コンパイル後のHTML,CSS,Jsファイルが格納されている場所
	private $dist_dir = "";

	// 静的ファイルが格納されているディレクトリ
	private $asset_dir = "";

	/**
	 * HTMLが格納されているディレクトリまでのテーマディレクトリからのパス
	 * 例: resource/gg-styleguide/dist
	 * @var string
	 */
	public $relative_html_path = "";

	public $sitetree = "";

	public $html_metadata = [
		'site_name'        => '', // Webサイトのタイトル
		'site_description' => '', // Webサイトの説明文
		'files'            => [], // HTMLファイル
		'components'       => [], // コンポーネント
		'css_files'        => "",
		'image_dir'        => "",
		'js_dir'           => "",
		'style'            => [
			'settings' => [],
		],
	];

	/**
	 * サイトへ登録する際に除外するコンポーネント
	 * @var array
	 */
	private $exclude_component_name = [
		'layout'    => [
			'l-container',
			'l-section',
			'l-post-content',
			'l-main',
			'l-header',
			'l-footer',
			'l-wrapper',
			'l-header-minimal',
			'l-footer',
			'l-aside',
			'l-global-nav',
			'l-header-variable',
			'l-footer-normal',
			'l-footer-simple',
		],
		'component' => [
			'c-slidebar-button js-slidebar-button',
			'c-slidebar-menu js-slidebar-menu is-top-to-bottom',
			'c-slidebar-container js-slidebar-container is-top-to-bottom',
			'fa fa-map-maker',
			'c-forms',
			'c-search-result',
			'c-map-search',
			'c-combined-search',
			'c-composite-search',
		],
		'project'   => [
			'fa fa-map-marker',
		]
	];

	public $css_files = [];
	public $js_files = [];

	/**
	 * 解析を除外するディレクトリ
	 * @var array
	 */
	private $excludes = [
		'node_modules',
		'styleguide',
		'build',
		'style.css',
	];

	public $cache_key = "growp_resource_key";

	private static $instance = null;

	public static $cache = null;

	public static $cache_hits = [];

	public static $cache_prop_keys = [
		'base_dir_name',
		'html_dir',
		'dist_dir',
		'asset_dir',
		'css_files',
		'js_files',
		'sitetree',
		'html_metadata',
		'relative_html_path',
	];

	/**
	 * URL置換を行う対応表
	 * @var array
	 */
	public $replace_matrix = [
		'src="/assets/'                    => 'src="{{relative_html_path}}/assets/',
		'style="background-image:url(\'/'  => 'style="background-image:url(\'{{relative_html_path}}/',
		'style="background-image:url(/'    => 'style="background-image:url({{relative_html_path}}/',
		'style="background-image: url(/'   => 'style="background-image: url({{relative_html_path}}/',
		'style="background-image: url(\'/' => 'style="background-image: url(\'{{relative_html_path}}/',
		'style="background-image: url("/'  => 'style="background-image: url("{{relative_html_path}}/',
		' href="/'                         => ' href="/'
	];

	private function __construct() {
		add_action( "update_option", [ $this, "clear_cache" ] );

		$this->sitetree = new SiteTree();
//		$cache_hits     = $this->load_from_cache();
//		$cache_flag     = false;
//
//		foreach ( $cache_hits as $ch ) {
//			if ( ! $ch ) {
//				$cache_flag = false;
//				break;
//			}
//		}
//		if ( ! $cache_flag ) {
		$this->set_dir();
		$this->parse();
//		}


	}

	public static function get_instance() {

		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * HTMLパスを取得
	 * @return mixed
	 */
	public static function get_relative_html_path() {
		$resource = static::get_instance();

		return $resource->relative_html_path;
	}

	/**
	 * キャッシュから読みこむ
	 * @return array
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 */
	public function load_from_cache() {
		$cache = new FilesystemCache( $this->cache_key );

		$props = [];

		foreach ( static::$cache_prop_keys as $prop_key ) {
			$props[ $prop_key ] = $cache->get( $prop_key );
			if ( $props[ $prop_key ] ) {
				$this->{$prop_key}               = $props[ $prop_key ];
				static::$cache_hits[ $prop_key ] = true;
			} else {
				static::$cache_hits[ $prop_key ] = false;
			}
		}


		return static::$cache_hits;
	}

	/**
	 * キャッシュをクリア
	 */
	public function clear_cache() {
		$cache = new FilesystemCache( $this->cache_key );
		$cache->clear();
	}

	/**
	 * キャッシュをセットする
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 */
	public function set_cache() {
		$cache = new FilesystemCache( $this->cache_key );
		$props = [];
		foreach ( static::$cache_prop_keys as $prop_key ) {
			$props[ $prop_key ] = $cache->get( $prop_key );
			if ( ! $props[ $prop_key ] ) {
				$cache->set( $prop_key, $this->{$prop_key}, 60 );
			}
		};
	}

	/**
	 * ディレクトリ情報をセットする
	 */
	public function set_dir() {
		$resource_dir = get_template_directory() . DIRECTORY_SEPARATOR . $this->base_dir_name;
		$finder       = $this->get_finder( $resource_dir );
		$finder->directories();
		$finder->depth( "==0" );
		$target_dir         = "";
		$default_target_dir = "";
		foreach ( $finder as $dir ) {
			if ( $dir->getPathname() !== "."
			     && $dir->getPathname() !== ".."
			     && strpos( $dir->getPathname(), "resource/default" ) === false ) {
				$target_dir = $dir->getPathname();
			}
			if ( strpos( $dir->getPathname(), "resource/default" ) !== false ) {
				$default_target_dir = $dir->getPathname();
			}
		}
		if ( ! $target_dir ) {
			$target_dir = $default_target_dir;
		}
		$this->relative_html_path = $this->base_dir_name . DIRECTORY_SEPARATOR . basename( $target_dir ) . DIRECTORY_SEPARATOR . "dist";

		$this->html_dir = apply_filters( 'growp/resource/path', $target_dir );
		$this->dist_dir = $this->get_path( "dist" );
		if ( ! file_exists( $this->dist_dir ) ) {
			$dir = get_template_directory();
			wp_die( "HTMLディレクトリを<br><a href='" . $dir . "'>" . get_template_directory() . "/resource/</a><br>ディレクトリ内に格納してください。" );
			exit;
		}
		$this->asset_dir = $this->get_path( "dist/assets" );
	}

	/**
	 * Symfony Finder オブジェクトを取得する
	 *
	 * @param $dir
	 *
	 * @return Finder
	 */
	public function get_finder( $dir ) {
		$finder = new Finder();
		$finder->in( $dir );
		foreach ( $this->excludes as $ex ) {
			$finder->exclude( $ex );
		}

		return $finder;
	}

	/**
	 * HTMLディレクトリのパスを取得する
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public function get_path( $path ) {
		return $this->html_dir . DIRECTORY_SEPARATOR . $path;
	}

	/**
	 * HTMLディレクトリを分析
	 */
	public function parse() {
		$finder = $this->get_finder( $this->dist_dir );
		$finder->files();
		foreach ( $finder as $file_id => $file ) {
			switch ( $file->getExtension() ) {
				case 'html' :
					$this->parse_html( $file, $file_id );
					break;
				case 'css' :
					$this->css_files[] = $this->relative_html_path . DIRECTORY_SEPARATOR . $file->getRelativePathname();
					break;
				case 'js' :
					$this->js_files[] = $this->relative_html_path . DIRECTORY_SEPARATOR . $file->getRelativePathname();
					break;
			}
		}
		$this->html_metadata["components"]["component"] = [];
		$this->html_metadata["components"]["layout"]    = [];
		$this->html_metadata["components"]["project"]   = [];
		$paths                                          = [];

		foreach ( $this->sitetree as $page ) {
			$this->html_metadata["components"]["layout"]    = array_merge( $this->html_metadata["components"]["layout"], $page->components["layout"] );
			$this->html_metadata["components"]["component"] = array_merge( $this->html_metadata["components"]["component"], $page->components["component"] );
			$this->html_metadata["components"]["project"]   = array_merge( $this->html_metadata["components"]["project"], $page->components["project"] );
			$paths[]                                        = str_replace( $this->dist_dir, "", $page->absolute_path );
			// トップページからサイトのタイトルを取得
			if ( $page->relative_path === "/index.html" ) {
				$this->set_metadata( "site_name", $page->title );
				$this->set_metadata( "site_description", $page->description );
			}
		}

		$this->sitetree->uasort( function ( $a, $b ) {
			if ( $a->relative_path == "/index.html" ) {
				return - 10;
			}

			return 0;
		} );

		try {
			$this->set_cache();
		} catch ( InvalidArgumentException $exception ) {

		}

	}

	/**
	 * メタデータをセットする
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return $this
	 */
	public function set_metadata( $key, $value ) {
		$this->html_metadata[ $key ] = $value;

		return $this;
	}

	/**
	 * メタデータを取得する
	 *
	 * @param $key
	 *
	 * @return bool|mixed
	 */
	public function get_metadata( $key ) {
		if ( isset( $this->html_metadata[ $key ] ) ) {
			return $this->html_metadata[ $key ];
		}

		return false;
	}

	public function parse_paths_of_files( $array ) {
		rsort( $array );
		$result = array();
		foreach ( $array as $item ) {
			$parts   = explode( '/', $item );
			$current = &$result;
			for ( $i = 1, $max = count( $parts ); $i < $max; $i ++ ) {
				if ( ! isset( $current[ $parts[ $i - 1 ] ] ) ) {
					$current[ $parts[ $i - 1 ] ] = array();
				}

				$current = &$current[ $parts[ $i - 1 ] ];
			}

			$last = end( $parts );

			if ( ! isset( $current[ $last ] ) && $last ) {
				// Don't add a folder name as an element if that folder has items
				$current[] = end( $parts );
			}
		}

		return $result;
	}

	/**
	 * HTMLを解析する
	 *
	 * @param $file
	 */
	public function parse_html( $file, $file_id ) {

		$page                = new Page();
		$page->id            = $file_id;
		$page->absolute_path = $file->getPathname();
		$page->relative_path = str_replace( $this->dist_dir, "", $page->absolute_path );
		preg_match_all( "/\//", $page->relative_path, $matches );
		$page->depth = count( $matches[0] );

		$contents   = $file->getContents();
		$page->type = 'page';
		preg_match_all( '/(?:<!--(?:\s|)growp(?:\s|)type=(?:\"|\')(.*?)(?:\"|\'))(?:\s|)--\>/', $contents, $matches );
		if ( isset( $matches[1][0] ) && $matches[1][0] ) {
			$page->type = $matches[1][0];
		}
		$crawler                 = new Crawler( $contents );
		$page->title             = $crawler->filter( "title" )->first()->html();
		$page->description       = $crawler->filter( 'meta[name=\'description\']' )->first()->attr( "content" );
		$main_content            = $crawler->filter( "*[class*='l-main']" );
		$page->main_content_html = "";
		$page->raw_html          = "";
		try {
			$page->main_content_html = $this->replace_url( $main_content->first()->html() );
			$page->raw_html          = $crawler->filter( "html" )->first()->html();
		} catch ( Exception $e ) {

		}


		$page->components['layout']    = $this->parse_component( $crawler, "layout", "*[class^='l-']" );
		$page->components['component'] = $this->parse_component( $crawler, "component", "*[class*='c-']" );
		$page->components['project']   = $this->parse_component( $crawler, "project", "*[class^='p-']" );
		$titles                        = preg_split( "/(｜|\|)/", $page->title );
		$page->page_header_title       = isset( $titles[0] ) ? $titles[0] : $page->title;
		if ( $crawler->filter( '*[data-growp-page-header-title]' )->count() ) {
			$page->page_header_title = $crawler->filter( '*[data-growp-page-header-title]' )->first()->text();
		}
		if ( $crawler->filter( '*[data-growp-page-header-image]' )->count() ) {
			$img = $crawler->filter( '*[data-growp-page-header-image]' )->first()->attr( 'style' );
			if ( ! $img ) {
				$img = $crawler->filter( '*[data-growp-page-header-image] img' )->first()->attr( 'src' );
			}
			$page->page_header_image = $img;
		}

		if ( $crawler->filter( '*[data-growp-page-header-subtitle]' )->count() ) {
			$page->page_header_subtitle = $crawler->filter( '*[data-growp-page-header-subtitle]' )->first()->text();
		}

		$this->sitetree->append( $page );
	}

	/**
	 * コンポーネントを解析する
	 *
	 * @param Crawler $crawler
	 * @param $name
	 * @param $pattern
	 *
	 * @return array
	 */
	public function parse_component( Crawler $crawler, $name, $pattern ) {
		$layouts = $crawler->filter( $pattern )->reduce( function ( Crawler $node, $i ) {
			// __を含んでいたら除外する
			if ( strpos( $node->attr( "class" ), "__" ) !== false ) {
				return false;
			}

			return true;
		} );
		$_list   = [];
		foreach ( $layouts as $layout ) {
			$_layout    = new Crawler( $layout );
			$class_name = $_layout->attr( "class" );
			$skip       = false;
			if ( isset( $this->exclude_component_name[ $name ] ) && $this->exclude_component_name[ $name ] ) {
				foreach ( $this->exclude_component_name[ $name ] as $exclude_class_name ) {
					if ( strpos( $class_name, $exclude_class_name ) !== false ) {
						$skip = true;
					}
				}
			}
			if ( $skip ) {
				continue;
			}
			$_layout              = $_layout->getNode( 0 );
			$html                 = $_layout->ownerDocument->saveHTML( $_layout );
			$_list[ $class_name ] = $html;
		}

		return $_list;
	}


	/**
	 * コンポーネントを登録する
	 */
	public function register_components() {
		$resource        = Resource::get_instance();
		$components      = $resource->html_metadata["components"];
		$_component_list = [];
		foreach ( $components as $layout_key => $component ) {
			foreach ( $component as $cname => $c ) {
				$c    = $this->replace_url( $c );
				$skip = false;
				if ( isset( $this->exclude_component_name[ $layout_key ] ) && $this->exclude_component_name[ $layout_key ] ) {
					foreach ( $this->exclude_component_name[ $layout_key ] as $exclude_class_name ) {
						if ( strpos( $cname, $exclude_class_name ) !== false ) {
							$skip = true;
						}
					}
				}
				if ( $skip ) {
					continue;
				}
				$item                                = [];
				$item["category"]                    = $layout_key;
				$item["name"]                        = $cname;
				$item["content"]                     = $c;
				$real_cname                          = explode( " ", $cname );
				$_component_list[ $real_cname[0] ][] = $item;
			}
		}

		foreach ( $_component_list as $real_component_name => $component ) {
			foreach ( $component as $cname => $c ) {

				$block_post_id = wp_insert_post( [
					'post_title'   => trim( $c["name"] ),
					'post_type'    => "growp_acf_block",
					'post_status'  => "publish",
					'post_content' => " ",
				] );

				// コンポーネントのマークアップを一度フォーマット
				$indenter      = new Indenter();
				$block_content = $indenter->indent( $c["content"] );

				update_field( "block_name", str_replace( " ", "--", trim( $c["name"] ) ), $block_post_id );
				update_field( "block_title", $c["name"], $block_post_id );
				update_field( "block_description", "", $block_post_id );
				update_field( "block_align", "", $block_post_id );
				update_field( "block_supports", [
					'align'    => [
						'left',
						'right',
						'wide',
						'full',
					],
					'mode'     => "1",
					'multiple' => "1",
				], $block_post_id );
				update_field( "block_render_callback", $block_content, $block_post_id );
				update_field( "block_category", "growp-blocks-" . $c["category"], $block_post_id );
				update_field( "block_icon", '', $block_post_id );
				update_field( "block_mode", "preview", $block_post_id );
				update_field( "block_post_types", get_post_types( [ "public" => true ] ), $block_post_id );
				update_field( "block_custom_template", $block_content, $block_post_id );
				update_field( "block_acf_settings", [], $block_post_id );
				update_field( "block_custom_template_condition", "0", $block_post_id );
			}
		}
	}

	/**
	 * URLを置換する
	 *
	 * @param $content
	 *
	 * @return mixed
	 */
	public function replace_url( $content ) {
		foreach ( $this->replace_matrix as $before => $after ) {
			$after   = str_replace( "{{relative_html_path}}", get_template_directory_uri() . "/" . $this->relative_html_path, $after );
			$content = str_replace( $before, $after, $content );
		}

		return $content;
	}

	/**
	 * dist/assets/css/style.css ファイルをメインのファイルとして呼び出す
	 * @return bool|mixed
	 */
	public static function get_default_main_css_file_path() {
		$resource = Resource::get_instance();
		foreach ( $resource->css_files as $_css_file ) {
			if ( strpos( $_css_file, "assets/css/style.css" ) !== false ) {
				return $_css_file;
			}
		}

		return false;
	}

	/**
	 * リライト後のCSSファイルを取得する
	 * @return bool|mixed
	 */
	public static function get_rewrite_main_css_file_path() {
		$resource = Resource::get_instance();
		foreach ( $resource->css_files as $_css_file ) {
			if ( strpos( $_css_file, "assets/css/style_rewrite.css" ) !== false ) {
				return $_css_file;
			}
		}

		return static::get_default_main_css_file_path();
	}

}
