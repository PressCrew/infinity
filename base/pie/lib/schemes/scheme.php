<?php
/**
 * PIE API: scheme helper class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage schemes
 * @since 1.0
 */

/**
 * Load reqs
 */
Pie_Easy_Loader::load( 'utils/files', 'collections' );

/**
 * Make Scheming Easy
 *
 * @package PIE
 * @subpackage schemes
 */
final class Pie_Easy_Scheme extends Pie_Easy_Base
{
	/**#@+
	 * ini directive enumeration
	 */
	const DIRECTIVE_PARENT_THEME = 'parent_theme';
	const DIRECTIVE_IMAGE_ROOT = 'image_root';
	const DIRECTIVE_STYLE_ROOT = 'style_root';
	const DIRECTIVE_SCRIPT_ROOT = 'script_root';
	const DIRECTIVE_FEATURE = 'feature';
	const DIRECTIVE_STYLE_DEFS = 'style';
	const DIRECTIVE_STYLE_DEPS = 'style_depends';
	const DIRECTIVE_STYLE_ACTS = 'style_actions';
	const DIRECTIVE_STYLE_CONDS = 'style_conditions';
	const DIRECTIVE_SCRIPT_DEFS = 'script';
	const DIRECTIVE_SCRIPT_DEPS = 'script_depends';
	const DIRECTIVE_SCRIPT_ACTS = 'script_actions';
	const DIRECTIVE_SCRIPT_CONDS = 'script_conditions';
	const DIRECTIVE_ADVANCED = 'advanced';
	const DIRECTIVE_UI_STYLESHEET = 'ui_stylesheet';
	const DIRECTIVE_DEV_MODE = 'development_mode';
	const DIRECTIVE_SCRIPT_DOMAIN = 'script_domain';
	const DIRECTIVE_OPT_SAVE_SINGLE = 'options_save_single';
	/**#@-*/

	/**
	 * Singleton instances
	 *
	 * Map of Pie_Easy_Scheme objects, theme names are keys
	 *
	 * @var Pie_Easy_Map
	 */
	static private $instances;

	/**
	 * Name of the root theme
	 *
	 * @var string
	 */
	private $root_theme;

	/**
	 * Relative path to the config dir relative to the theme's template path
	 *
	 * @var string
	 */
	private $config_dir;

	/**
	 * Name of the configuration ini file that is preferred by the API
	 *
	 * @var string
	 */
	private $config_file;

	/**
	 * Relative path to the exts dir relative to the theme's template path
	 *
	 * @var string
	 */
	private $exts_dir;

	/**
	 * Relative path to the docs dir relative to the theme's template path
	 *
	 * @var string
	 */
	private $docs_dir;

	/**
	 * Stack of config files that have been loaded
	 *
	 * @var Pie_Easy_Stack
	 */
	private $config_files_loaded;
	
	/**
	 * Theme stack
	 *
	 * @var Pie_Easy_Stack
	 */
	private $themes;

	/**
	 * @var Pie_Easy_Init_Directive_Registry
	 */
	private $directives;

	/**
	 * @var Pie_Easy_Scheme_Enqueue
	 */
	private $enqueue;

	/**
	 * This is a singleton
	 */
	private function __construct()
	{
		// initialize themes map
		$this->themes = new Pie_Easy_Stack();
		$this->directives = new Pie_Easy_Init_Directive_Registry();
		$this->config_files_loaded = new Pie_Easy_Stack();
	}

	/**
	 * Return the singleton instance of the scheme for a specific theme
	 *
	 * If no start theme is supplied, the active theme will be used
	 *
	 * @param string $start_theme Theme at which to start building the scheme from (bottom up)
	 * @return Pie_Easy_Scheme
	 */
	static public function instance( $start_theme = null )
	{
		if ( empty( $start_theme ) ) {
			$start_theme = get_stylesheet();
		}

		if ( !self::$instances instanceof Pie_Easy_Map ) {
			self::$instances = new Pie_Easy_Map();
		}

		if ( !self::$instances->contains( $start_theme ) ) {
			self::$instances->add( $start_theme, new self() );
		}

		return self::$instances->item_at( $start_theme );
	}

	/**
	 * One time initialization helper
	 *
	 * @param string $root_theme
	 * @param string $config_dir
	 * @param string $config_file
	 * @return boolean
	 */
	public function init( $root_theme, $config_dir = 'config', $config_file = null )
	{
		// do not init same scheme twice
		if ( $this->root_theme ) {
			return;
		}

		// setup config
		$this->set_root_theme( $root_theme );
		$this->set_config_dir( $config_dir );
		$this->set_config_file( $config_file );

		// load it
		$this->load();

		// dev mode
		if ( !defined( 'PIE_EASY_DEV_MODE' ) ) {
			define(
				'PIE_EASY_DEV_MODE',
				(boolean) $this->directives->get( self::DIRECTIVE_DEV_MODE )->value
			);
		}

		// add filters
		$this->add_filters();

		// run theme feature support helper
		$this->feature_support();

		// try to load additional functions files after WP theme setup
		add_action( 'after_setup_theme', array($this, 'init_enqueueing') );
		add_action( 'after_setup_theme', array($this, 'load_functions') );
		add_action( 'after_setup_theme', array($this, 'exports_refresh') );

		return true;
	}

	/**
	 * Return directives registry
	 *
	 * @return Pie_Easy_Init_Directive_Registry
	 */
	final public function directives()
	{
		return $this->directives;
	}

	/**
	 * Get scheme enqueue helper
	 *
	 * @return Pie_Easy_Scheme_Enqueue
	 */
	public function enqueue()
	{
		if ( $this->enqueue instanceof Pie_Easy_Scheme_Enqueue ) {
			return $this->enqueue;
		}

		throw new Exception( 'The enqueuer has not been initialized yet' );
	}

	/**
	 * Don't ever call this manually
	 *
	 * @ignore
	 */
	public function init_enqueueing()
	{
		if ( !$this->enqueue instanceof Pie_Easy_Scheme_Enqueue ) {
			$this->enqueue = new Pie_Easy_Scheme_Enqueue( $this );
		}
	}

	/**
	 * Set the name of the root theme
	 *
	 * @param string $name
	 * @return boolean
	 */
	private function set_root_theme( $name )
	{
		if ( empty( $this->root_theme ) ) {
			$this->root_theme = $name;
			return true;
		}

		return false;
	}

	/**
	 * Set the name of the dir under the child themes where the config file lives
	 *
	 * @param string $dir_name
	 * @return boolean
	 */
	private function set_config_dir( $dir_name )
	{
		if ( empty( $this->config_dir ) ) {
			$this->config_dir = $dir_name;
			return true;
		}

		return false;
	}

	/**
	 * Set the name of the config file that your API uses
	 *
	 * @param string $file_name
	 * @return boolean
	 */
	private function set_config_file( $file_name )
	{
		if ( empty( $this->config_file ) ) {
			$this->config_file = ( strlen($file_name) ) ? $file_name : $this->root_theme;
			return true;
		}

		return false;
	}

	/**
	 * Set the name of the dir under the child themes where the documents directory lives
	 *
	 * @param string $dir_name
	 * @return boolean
	 */
	final public function set_docs_dir( $dir_name )
	{
		if ( empty( $this->docs_dir ) ) {
			$this->docs_dir = $dir_name;
			return true;
		}

		return false;
	}

	/**
	 * Set the name of the dir under the child themes where the extensions directory lives
	 *
	 * @param string $dir_name
	 * @return boolean
	 */
	final public function set_exts_dir( $dir_name )
	{
		if ( empty( $this->exts_dir ) ) {
			$this->exts_dir = $dir_name;
			return true;
		}

		return false;
	}

	/**
	 * Add template filters
	 */
	private function add_filters()
	{
		// only add filters if there is at least one theme
		if ( count( $this->themes ) ) {

			// template filter callback
			$filter = array( $this, 'filter_template' );

			// add filters
			add_filter( '404_template', $filter );
			add_filter( 'search_template', $filter );
			add_filter( 'taxonomy_template', $filter );
			add_filter( 'front_page_template', $filter );
			add_filter( 'home_template', $filter );
			add_filter( 'attachment_template', $filter );
			add_filter( 'single_template', $filter );
			add_filter( 'page_template', $filter );
			add_filter( 'category_template', $filter );
			add_filter( 'tag_template', $filter );
			add_filter( 'author_template', $filter );
			add_filter( 'date_template', $filter );
			add_filter( 'archive_template', $filter );
			add_filter( 'comments_popup_template', $filter );
			add_filter( 'paged_template', $filter );
			add_filter( 'index_template', $filter );
			add_filter( 'comments_template', $filter );

		}
	}

	/**
	 * Load the scheme, using a theme as the starting point for the stack
	 *
	 * This method recursively crawls UP the theme hiearachy
	 *
	 * @param string $theme Theme's *directory name*
	 * @return boolean
	 */
	public function load( $theme = null )
	{
		if ( empty( $theme ) ) {
			$theme = $this->active_theme();
		}

		// paths to files
		$ini_file = $this->theme_file( $theme, $this->config_dir, $this->config_file . '.ini' );

		// does ini file exist?
		if ( Pie_Easy_Files::cache($ini_file)->is_readable() ) {
			// parse it
			$ini = parse_ini_file( $ini_file, true );
			// push onto loaded stack
			$this->config_files_loaded->push( $ini_file );
		} else {
			// yipes, theme has no ini file.
			// assume that parent theme is the root theme
			$ini[self::DIRECTIVE_PARENT_THEME] = $this->root_theme;
		}

		// make sure we got something
		if ( $ini !== false ) {

			// parent theme?
			$parent_theme =
				isset( $ini[self::DIRECTIVE_PARENT_THEME] )
					? $ini[self::DIRECTIVE_PARENT_THEME]
					: false;

			// recurse up the theme stack if necessary
			if ( $parent_theme ) {
				// load it
				$this->load( $parent_theme );
			}

			// push onto the stack AFTER recursion
			$this->themes->push( $theme );

			// loop through directives and set them
			foreach ( $ini as $name => $value ) {
				if ( $name == self::DIRECTIVE_ADVANCED ) {
					if ( is_array( $value ) ) {
						foreach ( $value as $name_adv => $value_adv ) {
							$this->directives()->set( $theme, $name_adv, $value_adv, true );
						}
					}
					continue;
				} else {
					$this->directives()->set( $theme, $name, $value, true );
				}
			}

		} else {
			throw new Exception( 'Failed to parse theme ini file: ' . $ini_file );
		}
	}

	/**
	 * Enable/disable feature support
	 *
	 * @ignore
	 */
	private function feature_support()
	{
		// any features set?
		if ( $this->directives()->has( self::DIRECTIVE_FEATURE ) ) {
			// at least one feature was set, get map
			$map = $this->directives()->get_map( self::DIRECTIVE_FEATURE );
			// loop through and add theme support for each feature
			foreach ( $map as $directive ) {
				foreach( $directive->value as $feature => $toggle ) {
					// toggled on?
					if ( (boolean) $toggle === true ) {
						add_theme_support( $feature );
					} else {
						remove_theme_support( $feature );
					}
				}
			}
		}
	}

	/**
	 * Try to load function files for themes in stack
	 *
	 * @ignore
	 */
	public function load_functions()
	{
		// loop through theme stack
		foreach ( $this->themes->to_array() as $theme  ) {
			// load functions file if it exists
			$filename = $this->theme_file( $theme, 'functions.php' );
			// try to load it
			if ( Pie_Easy_Files::cache($filename)->is_readable() ) {
				require_once $filename;
			}
		}
	}

	/**
	 * Refresh dynamic css/js file if necessary
	 */
	public function exports_refresh()
	{
		foreach ( $this->config_files_loaded as $file ) {
			// config was last modified...
			if ( PIE_EASY_DEV_MODE ) {
				// in dev mode, use current time
				$mtime = time();
			} else {
				// use file last mod time
				$mtime = @filemtime( $file );
			}
			// try to refresh against every policy
			foreach( Pie_Easy_Policy::all() as $policy ) {
				$policy->registry()->export_css_file()->refresh( $mtime );
				$policy->registry()->export_js_file()->refresh( $mtime );
			}
		}
	}

	/**
	 * Enable components for the scheme by passing a valid policy object
	 *
	 * @param Pie_Easy_Policy $policy
	 * @param string $ini_file_name
	 * @return boolean
	 */
	public function enable_component( Pie_Easy_Policy $policy )
	{
		// loop through entire theme stack BOTTOM UP and try to load options
		foreach( $this->themes->to_array() as $theme ) {

			// path to ini file
			$ini_file = $this->theme_file( $theme, $this->config_dir, $policy->get_handle() . '.ini' );

			// load the option config if it exists
			if ( Pie_Easy_Files::cache($ini_file)->is_readable() ) {

				// skip loaded files
				if ( $this->config_files_loaded->contains( $ini_file ) ) {
					continue;
				}
				
				// try to load ini file
				if ( $policy->registry()->load_config_file( $ini_file, $theme ) ) {
					// push onto loaded stack
					$this->config_files_loaded->push( $ini_file );
				}
			}
		}

		return true;
	}

	/**
	 * If template exists in scheme, return it, otherwise return the original template
	 *
	 * @ignore
	 * @param string $template
	 * @return string
	 */
	public function filter_template( $template )
	{
		// fall back to index
		if ( empty( $template ) ) {
			$template = 'index.php';
		}

		// see if it exists in the scheme
		$scheme_template = $this->locate_template( array( basename( $template ) ) );

		// return scheme template?
		if ( $scheme_template ) {
			return $scheme_template;
		} else {
			return $template;
		}
	}

	/**
	 * Find and optionally load template(s) if it exists anywhere in the scheme
	 *
	 * @param string|array $template_names
	 * @param boolean $load Auto load template if set to true
	 * @return string
	 */
	public function locate_template( $template_names, $load = false )
	{
		// must have at least one theme to search
		if ( count( $this->themes ) ) {

			// convert string arg to array
			if ( !is_array( $template_names ) ) {
				$template_names = array( $template_names );
			}

			// loop through all templates
			foreach ( $template_names as $template_name ) {

				// get all possible template paths
				$template_paths = $this->theme_dirs( $template_name );

				// loop through all templates
				foreach ( $template_paths as $template_path ) {

					// does it exist?
					if ( Pie_Easy_Files::cache($template_path)->is_readable() ) {
						// load it?
						if ($load) {
							load_template( $template_path );
						}
						// return the located template path
						return $template_path;
					}
				}
			}
		}

		// didn't find a template
		return '';
	}

	/**
	 * Return the name of the active theme
	 *
	 * @return string
	 */
	private function active_theme()
	{
		return get_stylesheet();
	}

	/**
	 * Return theme stack as an array
	 *
	 * @param boolean $top_down
	 * @return array
	 */
	public function theme_stack( $top_down = false )
	{
		return $this->themes->to_array( $top_down );
	}

	/**
	 * Return path to a theme directory
	 *
	 * @param string $theme
	 * @return string
	 */
	public function theme_dir( $theme )
	{
		return get_theme_root( $theme ) . DIRECTORY_SEPARATOR . $theme;
	}

	/**
	 * Return array of all theme root directory paths
	 *
	 * @param string $file_names,...
	 * @return array
	 */
	public function theme_dirs()
	{
		// get all args
		$file_names = func_get_args();

		// paths to return
		$paths = array();

		foreach ( $this->themes->to_array(true) as $theme ) {
			$paths[] = $this->theme_file( $theme, $file_names );
		}

		return $paths;
	}

	/**
	 * Return array of all theme documentation dirs
	 *
	 * @return array
	 */
	public function theme_documentation_dirs()
	{
		return $this->theme_dirs( $this->docs_dir );
	}

	/**
	 * Return path to a theme file
	 *
	 * @param string $theme
	 * @param string $file_names,...
	 */
	public function theme_file( $theme = null )
	{
		// get all args
		$args = func_get_args();
		array_shift($args);

		// handle empty theme
		if ( empty( $theme ) ) {
			$theme = $this->root_theme;
		}

		// one or more args left, then we got some file names
		if ( count($args) >= 1 ) {
			if ( count($args) == 1 && is_array(reset($args)) ) {
				$file_names = $args[0];
			} else {
				$file_names = $args;
			}
		} else {
			throw new Exception( 'No file names passed' );
		}

		return $this->theme_dir( $theme ) . Pie_Easy_Files::path_build( $file_names );
	}

	/**
	 * Locate a theme file, giving priority to top themes in the stack
	 *
	 * If first argument is a Pie_Easy_Map instance, it is expected to be
	 * a map of theme directives whose values are relative path prefixes.
	 *
	 * @param Pie_Easy_Map $prefix_map Optional map of directives which define path prefixes
	 * @param string $file_names,... The file names that make up the RELATIVE path to the theme root
	 * @return string|false
	 */
	public function locate_file()
	{
		// get all args
		$file_names = func_get_args();

		// file paths to be located
		$locate_names = array();

		// no prefix map by default
		$prefix_map = null;

		// prefixes map?
		if ( !empty( $file_names ) ) {
			if ( $file_names[0] instanceof Pie_Easy_Map ) {
				$prefix_map = array_shift( $file_names );
			}
		}

		// still have something?
		if ( empty( $file_names ) ) {
			return false;
		}

		// split all strings in case they contain a static directory separator
		foreach ( $file_names as $file_name ) {
			// split it
			$splits = Pie_Easy_Files::path_split( $file_name );
			// append to array
			foreach ( $splits as $split ) {
				$locate_names[] = $split;
			}
		}

		// loop through stack TOP DOWN
		foreach ( $this->themes->to_array(true) as $theme ) {

			// build path to stackfile
			$stack_file = $this->theme_dir( $theme );

			// inject prefix?
			if ( $prefix_map && $prefix_map->contains($theme) ) {
				$stack_file .= DIRECTORY_SEPARATOR . $prefix_map->item_at($theme)->value;
			}

			// append requested path
			$stack_file .= Pie_Easy_Files::path_build( $locate_names );

			// does stack file exist?
			if ( Pie_Easy_Files::cache($stack_file)->is_readable() ) {
				return $stack_file;
			}
		}

		return false;
	}

	/**
	 * Locate a theme config file, giving priority to top themes in the stack
	 *
	 * @param string $file_names,... The file names that make up the RELATIVE path to the theme config root
	 * @return string|false
	 */
	public function locate_config_file()
	{
		// get all args
		$args = func_get_args();
		// config root is first arg
		array_unshift( $args, $this->config_dir );
		// locate it
		return call_user_func_array( array($this,'locate_file'), $args );
	}

	/**
	 * Locate a theme component extension file, giving priority to top themes in the stack
	 *
	 * @param string $file_names,... The file names that make up the RELATIVE path to the theme extensions root
	 * @return string|false
	 */
	public function locate_extension_file()
	{
		// get all args
		$args = func_get_args();
		// extension root is first arg
		array_unshift( $args, $this->exts_dir );
		// locate it
		return call_user_func_array( array($this,'locate_file'), $args );
	}

	/**
	 * Locate a theme asset, giving priority to top themes in the stack
	 *
	 * @param string $file_names,... The file names that make up the RELATIVE path to the theme root
	 * @return string|false
	 */
	private function locate_asset( $path_directive )
	{
		// image root must be set
		if ( $this->directives()->has( $path_directive ) ) {
			// get all args
			$args = func_get_args();
			// throw out the first one
			array_shift( $args );
			// add directive path
			array_unshift( $args, $this->directives()->get($path_directive)->value );
			// locate it
			return call_user_func_array( array($this,'locate_file'), $args );
		}

		return false;
	}

	/**
	 * Locate a theme image
	 *
	 * @param string $file_names,... The file names that make up the RELATIVE path to the theme root
	 * @return string|false
	 */
	public function locate_image()
	{
		$args = func_get_args();
		array_unshift( $args, self::DIRECTIVE_IMAGE_ROOT );
		return call_user_func_array( array($this, 'locate_asset'), $args );
	}

	/**
	 * Locate a theme style
	 *
	 * @param string $file_names,... The file names that make up the RELATIVE path to the theme root
	 * @return string|false
	 */
	public function locate_style()
	{
		$args = func_get_args();
		array_unshift( $args, self::DIRECTIVE_STYLE_ROOT );
		return call_user_func_array( array($this, 'locate_asset'), $args );
	}

	/**
	 * Locate a theme script
	 *
	 * @param string $file_names,... The file names that make up the RELATIVE path to the theme root
	 * @return string|false
	 */
	public function locate_script()
	{
		$args = func_get_args();
		array_unshift( $args, self::DIRECTIVE_SCRIPT_ROOT );
		return call_user_func_array( array($this, 'locate_asset'), $args );
	}

	/**
	 * Look for a header in the scheme stack
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function get_header( $name = null )
	{
		$templates = array();

		if ( isset($name) )
			$templates[] = "header-{$name}.php";

		$templates[] = "header.php";

		$located_template = $this->locate_template( $templates );

		if ( $located_template ) {
			do_action( 'get_header', $name );
			return load_template( $located_template );
		} else {
			return get_header( $name );
		}
	}

	/**
	 * Look for a footer in the scheme stack
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function get_footer( $name = null )
	{
		$templates = array();

		if ( isset($name) )
			$templates[] = "footer-{$name}.php";

		$templates[] = "footer.php";

		$located_template = $this->locate_template( $templates );

		if ( $located_template ) {
			do_action( 'get_footer', $name );
			return load_template( $located_template );
		} else {
			return get_footer( $name );
		}
	}

	/**
	 * Look for a sidebar in the scheme stack
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function get_sidebar( $name = null )
	{
		$templates = array();

		if ( isset($name) )
			$templates[] = "sidebar-{$name}.php";

		$templates[] = "sidebar.php";

		$located_template = $this->locate_template( $templates );

		if ( $located_template ) {
			do_action( 'get_sidebar', $name );
			return load_template( $located_template );
		} else {
			return get_sidebar( $name );
		}
	}

	/**
	 * Look for a template part in the scheme stack
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialised template.
	 */
	function get_template_part( $slug, $name = null )
	{
		$templates = array();
		if ( isset($name) )
			$templates[] = "{$slug}-{$name}.php";

		$templates[] = "{$slug}.php";

		$located_template = $this->locate_template( $templates );

		if ( $located_template ) {
			do_action( "get_template_part_{$slug}", $slug, $name );
			load_template( $located_template, false );
		} else {
			get_template_part( $slug, $name );
		}
	}

	/**
	 * Look for a search form in the scheme stack
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialised template.
	 */
	function get_search_form( $echo = true )
	{
		$located_template = $this->locate_template( 'searchform.php' );

		if ( $located_template ) {
			do_action( 'get_search_form' );
			load_template( $located_template, false );
		} else {
			return get_search_form( $echo );
		}
	}
}

?>
