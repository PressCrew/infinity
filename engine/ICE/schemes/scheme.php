<?php
/**
 * ICE API: scheme helper class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage schemes
 * @since 1.0
 */

/**
 * Make Scheming Easy
 *
 * @package ICE
 * @subpackage schemes
 */
final class ICE_Scheme extends ICE_Base
{
	/**
	 * Parent theme setting
	 */
	const SETTING_PARENT_THEME = 'parent_theme';
	/**
	 * Image root setting
	 */
	const SETTING_IMAGE_ROOT = 'image_root';
	/**
	 * Style root setting
	 */
	const SETTING_STYLE_ROOT = 'style_root';
	/**
	 * Script root setting
	 */
	const SETTING_SCRIPT_ROOT = 'script_root';
	/**
	 * Feature config key
	 */
	const SETTING_FEATURE = 'feature';
	/**
	 * Options save single setting
	 */
	const SETTING_OPT_SAVE_SINGLE = 'options_save_single';

	/**
	 * Singleton instance
	 *
	 * @var ICE_Scheme
	 */
	static private $instance;

	/**
	 * Name of the base theme
	 *
	 * @var string
	 */
	private $base_theme;

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
	 * Name of the configuration file that is preferred by the API
	 *
	 * @var string
	 */
	private $config_file;
	
	/**
	 * Theme stack.
	 *
	 * @var array
	 */
	private $themes = array();

	/**
	 * Cache of reversed theme stack array
	 *
	 * @var array
	 */
	private $theme_stack_topdown;

	/**
	 * The settings registry instance
	 * 
	 * @var ICE_Init_Settings
	 */
	private $settings;

	/**
	 * This is a singleton
	 */
	private function __construct()
	{
		// initialize settings instance
		$this->settings = new ICE_Init_Settings();
	}

	/**
	 * Return the singleton instance of the scheme
	 *
	 * @return ICE_Scheme
	 */
	static public function instance()
	{
		if ( !self::$instance instanceof self ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * One time initialization helper
	 *
	 * @param string $base_theme
	 * @return ICE_Scheme
	 */
	public function init( $base_theme )
	{
		// do not init same scheme twice
		if ( $this->base_theme ) {
			return;
		}

		// set base theme
		$this->base_theme = $base_theme;

		// root theme is always template
		$this->root_theme = get_template();

		// load it
		$this->load();

		// add filters
		$this->add_filters();

		// run theme feature support helper
		$this->feature_support();

		// some scheme initializations must occur after WP theme setup
		add_action( 'after_setup_theme', array($this, 'finalize'), 9 );
		add_action( 'wp_head', array($this, 'render_assets'), 11 );
		add_action( 'admin_head', array($this, 'render_assets'), 11 );

		return $this;
	}

	/**
	 * Return settings registry
	 *
	 * @return ICE_Init_Settings
	 */
	final public function settings()
	{
		return $this->settings;
	}

	/**
	 * Set the name of the dir under the child themes where the config file lives
	 *
	 * @param string $dir_name
	 * @return boolean
	 */
	final public function set_config_dir( $dir_name )
	{
		if ( null === $this->config_dir ) {
			$this->config_dir = $dir_name;
			return true;
		} else {
			throw new Exception( 'Cannot set config dir, already set' );
		}
	}

	/**
	 * Set the name of the config file that your API uses
	 *
	 * @param string $file_name
	 * @return boolean
	 */
	final public function set_config_file( $file_name )
	{
		if ( null === $this->config_file ) {
			$this->config_file = $file_name;
			return true;
		} else {
			throw new Exception( 'Cannot set config file, already set' );
		}
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
		// was a theme passed?
		if ( empty( $theme ) ) {
			// fall back to using active theme
			$theme = ICE_ACTIVE_THEME;
		}

		// get path to config file
		$config_file = $this->theme_config_file( $theme, $this->config_file );

		// does config file exist?
		if ( is_readable( $config_file )  ) {
			// parse it
			$config = $this->parse_config_file( $config_file );
		} else {
			// yipes, theme has no config file.
			// assume that parent theme is the root theme
			$config[self::SETTING_PARENT_THEME] = $this->root_theme;
		}

		// make sure we got something
		if ( $config !== false ) {

			// parent theme?
			$parent_theme =
				isset( $config[self::SETTING_PARENT_THEME] )
					? $config[self::SETTING_PARENT_THEME]
					: false;

			// recurse up the theme stack if necessary
			if ( $parent_theme ) {
				// load it
				$this->load( $parent_theme );
			}

			// push onto the stack AFTER recursion
			$this->themes[] = $theme;

			// loop through config
			foreach ( $config as $name => $value ) {
				// apply setting for theme
				$this->settings()->set( $theme, $name, $value );
			}

		} else {
			throw new Exception( 'Failed to parse theme config file: ' . $config_file );
		}
	}

	private function parse_config_file( $filename )
	{
		// try to load config array from file
		$config = require_once( $filename );

		// get a valid config?
		if ( is_array( $config ) ) {
			// yep, return it
			return $config;
		} else {
			// not good, return empty array
			return array();
		}
	}

	/**
	 * Enable/disable feature support
	 */
	private function feature_support()
	{
		// try to get features
		$setting = $this->settings()->get_stack( self::SETTING_FEATURE );

		// any features set?
		if ( $setting ) {
			// loop through and add theme support for each feature
			foreach ( $setting as $theme => $features ) {
				// loop all features
				foreach( $features as $feature => $toggle ) {
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
	 * Try to manually load a functions.php file for a theme.
	 *
	 * @param string $theme The theme's slug.
	 * @return boolean
	 */
	public function load_functions( $theme )
	{
		// get filename
		$filename = $this->theme_file( $theme, 'functions.php' );

		// does it exist?
		if ( is_readable( $filename ) ) {
			// yep, load it
			require_once $filename;
			// success
			return true;
		}

		// failed to load
		return false;
	}

	/**
	 * Inject dynamic assets.
	 */
	public function render_assets()
	{
		// render em! ?>
		<!-- dynamic styles -->
		<style type="text/css">
			<?php $this->render_styles(); ?>
		</style>
		<!-- dynamic scripts -->
		<script type="text/javascript">
		//<![CDATA[
			<?php $this->render_scripts(); ?>
		//]]>
		</script><?php
	}

	/**
	 * Render all injected styles.
	 */
	final protected function render_styles()
	{
		// loop all component registries
		foreach ( ICE_Policy::all() as $policy ) {
			// loop all components
			foreach ( $policy->registry()->get_all() as $component ) {
				// render styles
				$component->style()->render();
			}
		}
	}

	/**
	 * Render all injected scripts.
	 */
	final protected function render_scripts()
	{
		// loop all component registries
		foreach ( ICE_Policy::all() as $policy ) {
			// loop all components
			foreach ( $policy->registry()->get_all() as $component ) {
				// render scripts
				$component->script()->render();
			}
		}
	}

	/**
	 * Enable components for the scheme by passing a valid component type.
	 *
	 * @param string $type The component type to enable.
	 * @return boolean
	 */
	public function enable_component( $type )
	{
		// get the policy for the type
		$policy = ICE_Policy::instance( $type );

		// loop through entire theme stack BOTTOM UP and try to load options
		foreach( $this->theme_stack( false ) as $theme ) {

			// path to config file
			$config_file = $this->theme_config_file( $theme, $policy->get_handle() );

			// does the option config exist?
			if ( is_readable( $config_file ) ) {
				// yep, try to load it
				$policy->registry()->register_file( $config_file );
			}
		}

		return true;
	}

	/**
	 * Finalize the scheme.
	 */
	final public function finalize()
	{
		// loop all component registries
		foreach ( ICE_Policy::all() as $policy ) {
			// finalize component policy
			$policy->finalize();
		}
	}

	/**
	 * If template exists in scheme, return it, otherwise return the original template
	 *
	 * @internal
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
					if ( is_readable( $template_path ) ) {
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
	 * Return theme stack as an array
	 *
	 * @param boolean $top_down
	 * @return array
	 */
	public function theme_stack( $top_down = true )
	{
		// is there anything in the stack yet?
		if ( 0 === count( $this->themes ) ) {
			// not good. throw an exception here so we don't have to
			// act paranoid and check the result of every call to this.
			throw new Exception( 'You are trying to get the theme stack before it has been loaded' );
		}

		// top down?
		if ( true === $top_down ) {
			// empty cache?
			if ( null === $this->theme_stack_topdown ) {
				// populate cache
				$this->theme_stack_topdown = array_reverse( $this->themes, true );
			}
			// return reversed array
			return $this->theme_stack_topdown;
		} else {
			// return array as is
			return $this->themes;
		}
		
	}

	/**
	 * Return path to a theme directory
	 *
	 * @param string $theme
	 * @return string
	 */
	final public function theme_dir( $theme )
	{
		return ICE_Files::theme_dir( $theme );
	}

	/**
	 * Return array of all theme root directory paths
	 *
	 * @param string $file_names,...
	 * @return array
	 */
	final public function theme_dirs()
	{
		// get all args
		$file_names = func_get_args();

		// paths to return
		$paths = array();

		// loop through theme stack
		foreach ( $this->theme_stack() as $theme ) {
			// add to list of paths
			$paths[] = $this->theme_file( $theme, $file_names );
		}

		return $paths;
	}

	/**
	 * Return path to a theme file
	 *
	 * @param string $theme
	 * @param string $file_names,...
	 */
	final public function theme_file( $theme = null )
	{
		// get all args
		$args = func_get_args();
		array_shift($args);

		// handle empty theme
		if ( empty( $theme ) ) {
			$theme = $this->base_theme;
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

		return $this->theme_dir( $theme ) . '/' . implode( '/', $file_names );
	}

	/**
	 * Return URL to a theme file
	 *
	 * @param string $theme
	 * @param string|array $file_names,... Zero or more file name parameters
	 */
	final public function theme_file_url( $theme )
	{
		// get all args
		$args = func_get_args();
		
		return call_user_func_array( array( 'ICE_Files', 'theme_file_url' ), $args );
	}

	/**
	 * Return path to a specific theme's configuration file
	 *
	 * @param string $theme
	 * @return string
	 */
	final public function theme_config_dir( $theme )
	{
		// return config dir as is
		return $this->config_dir;
	}

	/**
	 * Return path to a specific theme's configuration file
	 *
	 * @param string $theme
	 * @return string
	 */
	final public function theme_config_file( $theme, $filename )
	{
		// the relative config dir path
		$config_dir = $this->theme_config_dir( $theme );

		// return absolute path to theme file
		return $this->theme_file( $theme, $config_dir, $filename . '.php' );
	}

	/**
	 * Return the first theme in which a relative file path is found.
	 *
	 * @param string $relative_path
	 * @return string|false
	 */
	public function locate_theme( $relative_path )
	{
		// loop through theme stack
		foreach ( $this->theme_stack() as $theme ) {

			// build possible absolute path to file
			$absolute_path = $this->theme_dir( $theme ) . '/' . $relative_path;

			// does file exist?
			if ( is_readable( $absolute_path ) ) {
				// yes, return the *theme*
				return $theme;
			}
		}

		// file not found in any theme
		return false;
	}

	/**
	 * Locate a theme file, giving priority to top themes in the stack.
	 *
	 * @param string $file_names,... The file names that make up the RELATIVE path to the theme root
	 * @return string|false
	 */
	public function locate_file()
	{
		// get all args
		$file_names = func_get_args();

		// have something?
		if ( empty( $file_names ) ) {
			// nope, bail out
			return false;
		}

		// loop through theme stack
		foreach ( $this->theme_stack() as $theme ) {

			// build path to stackfile
			$stack_file = $this->theme_dir( $theme );

			// append requested path
			$stack_file .= '/' . implode( '/', $file_names );

			// does stack file exist?
			if ( is_readable( $stack_file ) ) {
				// yep, return it
				return $stack_file;
			}
		}

		// file not located
		return false;
	}

	/**
	 * Locate a theme asset, giving priority to top themes in the stack
	 *
	 * @param string $path_setting The scheme setting which contains the asset path
	 * @return string|false
	 */
	private function locate_asset( $path_setting )
	{
		// try to get path setting
		$path = $this->settings()->get_value( $path_setting );

		// image root must be set
		if ( $path ) {
			// get all args
			$args = func_get_args();
			// throw out the first one
			array_shift( $args );
			// add setting path
			array_unshift( $args, $path );
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
		array_unshift( $args, self::SETTING_IMAGE_ROOT );
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
		array_unshift( $args, self::SETTING_STYLE_ROOT );
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
		array_unshift( $args, self::SETTING_SCRIPT_ROOT );
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
	 * @param boolean $echo Set to false to return markup instead of printing
	 * @return mixed
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
