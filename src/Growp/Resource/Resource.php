<?php

namespace Growp\Resource;

use function array_filter;
use function basename;
use const DIRECTORY_SEPARATOR;
use DOMNodeList;
use ErrorException;

use function get_class;
use function get_class_methods;
use ParagonIE\Sodium\File;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Finder\Finder;
use YoastSEO_Vendor\Psr\Log\InvalidArgumentException;


class Resource {

	private $base_dir_name = "resource";

	// HTMLが格納されているディレクトリ
	private $html_dir = "";

	// コンパイル後のHTML,CSS,Jsファイルが格納されている場所
	private $dist_dir = "";

	// 静的ファイルが格納されているディレクトリ
	private $asset_dir = "";

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

	private $exclude_component_name = [
		'layout' => [
			'l-container',
			'l-section',
			'l-post-content',
			'l-main',
			'l-wrapper',
		]
	];

	public $css_files = [];
	public $js_files = [];


	private $excludes = [
		'node_modules',
		'styleguide',
//		'app',
		'build',
	];

	private static $instance = null;

	public static $cache = null;
	public static $cache_key = "growp_resource";
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

	private function __construct() {
		$this->sitetree = new SiteTree();
		$cache_hits     = $this->load_from_cache();
		$cache_flag     = true;

		foreach ( $cache_hits as $ch ) {
			if ( ! $ch ) {
				$cache_flag = false;
				break;
			}
		}
		if ( ! $cache_flag ) {
			$this->set_dir();
			$this->parse();
		}

	}

	public static function get_instance() {

		if ( ! static::$instance ) {
			static::$instance = new self();
		}

		return static::$instance;
	}

	public static function get_relative_html_path() {
		$resource = static::get_instance();

		return $resource->relative_html_path;
	}

	public function load_from_cache() {
		$cache = new FilesystemCache();
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

	public function set_cache() {
		$cache = new FilesystemCache();
		$props = [];


		foreach ( static::$cache_prop_keys as $prop_key ) {
			$props[ $prop_key ] = $cache->get( $prop_key );
			if ( ! $props[ $prop_key ] ) {
				$cache->set( $prop_key, $this->{$prop_key}, 60 * 60 * 60 );
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
		$target_dir = "";
		foreach ( $finder as $dir ) {
			if ( $dir->getPathname() !== "." && $dir->getPathname() !== ".." ) {
				$target_dir = $dir->getPathname();
			}
		}

		$this->relative_html_path = $this->base_dir_name . DIRECTORY_SEPARATOR . basename( $target_dir ) . DIRECTORY_SEPARATOR . "dist";
		$this->html_dir           = apply_filters( 'growp/resource/path', $target_dir );
		$this->dist_dir           = $this->get_path( "dist" );
		$this->asset_dir          = $this->get_path( "dist/assets" );
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
			$this->html_metadata["components"]["project"]   = array_merge( $this->html_metadata["components"]["project"], $page->components["project"] );
			$this->html_metadata["components"]["component"] = array_merge( $this->html_metadata["components"]["component"], $page->components["component"] );
			$paths[]                                        = str_replace( $this->dist_dir, "", $page->absolute_path );

			// トップページからサイトのタイトルを取得
			if ( $page->relative_path === "/index.html" ) {
				$this->set_metadata( "site_name", $page->title );
				$this->set_metadata( "site_description", $page->description );
			}
		}
		$this->set_cache();
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
		$crawler           = new Crawler( $contents );
		$page->title       = $crawler->filter( "title" )->first()->html();
		$page->description = $crawler->filter( 'meta[name=\'description\']' )->first()->attr( "content" );

		$page->components['layout']    = $this->parse_component( $crawler, "layout", "*[class^='l-']" );
		$page->components['component'] = $this->parse_component( $crawler, "component", "*[class*='c-']" );
		$page->components['project']   = $this->parse_component( $crawler, "project", "*[class*='p-']" );

		$titles                  = preg_split( "/(｜|\|)/", $page->title );
		$page->page_header_title = isset( $titles[0] ) ? $titles[0] : $page->title;
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
		} );;
		$_list = [];
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


}
