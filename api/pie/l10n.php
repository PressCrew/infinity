<?php
/**
 * PIE localization class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage localization
 * @since 1.0
 */

/**
 * Make L10n Easy
 */
abstract class Pie_Easy_L10n
{
	/**
	 * The text domain to translate for
	 *
	 * @var string
	 */
	private $domain;
	
	/**
	 * Constructor
	 * 
	 * @param string $domain 
	 */
	public function __construct( $domain )
	{
		$this->domain = $domain;
	}

	/**
	 * Load text for the domain
	 *
	 * @param string $custom_dir_path
	 * @return boolean
	 */
	public function load_text( $custom_dir_path = false )
	{
		// .mo file name
		$mofile = sprintf( '%s-%s.mo', $domain, get_locale() );

		// global .mo file path
		$mofile_global = WP_LANG_DIR . '/' . $mofile;

		// custom .mo file path
		$mofile_custom = ( $custom_dir_path ) ? $custom_dir_path . '/' . $mofile : null;

		// try to load it
		if ( file_exists( $mofile_global ) ) {
			return load_textdomain( $domain, $mofile_global );
		} elseif ( ( $mofile_custom ) && file_exists( $mofile_custom ) ) {
			return load_textdomain( $domain, $mofile_custom );
		} else {
			return false;
		}
	}
}

?>
