<?php
/**
 * PIE Framework scheme helper class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage scheme
 * @since 1.0
 */

/**
 * Make Scheming Easy
 */
final class Pie_Easy_Scheme
{
	/**
	 * Parent Theme ini setting
	 */
	const SETTING_PARENT_THEME = 'parent_theme';

	/**
	 * Enable Skins ini setting
	 */
	const SETTING_ENABLE_SKINS = 'enable_skins';

	/**
	 * Singleton instance
	 * 
	 * @var Pie_Easy_Scheme
	 */
	static private $instance;

	/**
	 * Relative path to the config dir relative to the theme's template path
	 *
	 * @var string
	 */
	private $config_dir;

	/**
	 * Name of the ini file that is preferred by the API
	 *
	 * @var string
	 */
	private $config_file;

	/**
	 * The parent theme (if any)
	 *
	 * @var string
	 */
	private $parent_theme;

	/**
	 * Parent theme stack
	 *
	 * @var array
	 */
	private $parent_themes = array();

	/**
	 * Set to true if skinning has been enabled in the config
	 *
	 * @var boolean
	 */
	private $skinning_enabled;

	/**
	 * Constructor
	 */
	private function __construct()
	{
		// this is a singleton
	}

	/**
	 * Return the singleton instance
	 *
	 * @return Pie_Easy_Scheme
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
	 * @param string $config_dir
	 * @param string $config_file
	 * @return boolean
	 */
	static public function init( $config_dir, $config_file )
	{
		if ( !self::$instance instanceof self ) {
			self::instance()->set_config_dir( $config_dir );
			self::instance()->set_config_file( $config_file );
			self::instance()->load();
		}

		return true;
	}

	/**
	 * Set the name of the dir under the child themes where the config file lives
	 *
	 * @param string $dir_name
	 * @return boolean
	 */
	public function set_config_dir( $dir_name )
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
	public function set_config_file( $file_name )
	{
		if ( empty( $this->config_file ) ) {
			$this->config_file = $file_name;
			return true;
		}

		return false;
	}

	/**
	 * Load the scheme, using a parent theme as the starting point for the stack
	 *
	 * @param string $parent_theme Parent theme's *directory name*\
	 * @return boolean
	 */
	public function load( $parent_theme = null )
	{
		if ( empty( $parent_theme ) ) {
			$parent_theme = basename( STYLESHEETPATH );
		}

		// build up path to config file
		$ini_file = sprintf(
			'%s/%s/%s/%s.ini',
			get_theme_root(),
			$parent_theme,
			$this->config_dir,
			$this->config_file );

		// does ini file exist?
		if ( is_readable( $ini_file ) ) {

			// parse it
			$ini = parse_ini_file( $ini_file, true );

			// make sure we got something
			if ( $ini !== false ) {

				// grandparent theme?
				$grandparent_theme =
					isset( $ini[self::SETTING_PARENT_THEME] )
						? $ini[self::SETTING_PARENT_THEME]
						: false;

				// recurse up the theme stack if necessary
				if ( $grandparent_theme ) {
					// load it
					$this->load( $grandparent_theme );
				}

				// set parent theme property AFTER recursion
				$this->parent_theme = $parent_theme;

				// push myself onto the beginning of the stack
				array_unshift( $this->parent_themes, $this->parent_theme );

				// enable skinning?
				if ( isset( $ini[self::SETTING_ENABLE_SKINS] ) ) {
					$this->skinning_enabled = $ini[self::SETTING_ENABLE_SKINS];
				}

			} else {
				throw new Exception( 'Failed to parse parent theme ini file: ' . $ini_file );
			}
		} else {
			throw new Exception( 'The parent theme ini file does not exist or is not readable: ' . $ini_file );
		}
	}

	/**
	 * If template exists in scheme, return it, otherwise return the original template
	 *
	 * @param string $template
	 * @return string
	 */
	function filter_template( $template )
	{
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
		// must have at least one parent them to search
		if ( !empty( $this->parent_themes ) ) {

			// convert string arg to array
			if ( !is_array( $template_names ) ) {
				$template_names = array( $template_names );
			}

			// loop through all templates
			foreach ( $template_names as $template_name ) {

				// loop through the entire theme stack
				foreach ($this->parent_themes as $theme_name ) {

					// prepend all template names with theme dir
					$located_template = sprintf( '%s/%s/%s', get_theme_root(), $theme_name, $template_name );

					// does it exist?
					if ( file_exists( $located_template ) ) {
						// load it?
						if ($load) {
							load_template( $located_template );
						}
						// return the located template path
						return $located_template;
					}
				}
			}
		}

		// didn't find a template
		return '';
	}

}

?>
