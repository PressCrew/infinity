<?php
/**
 * ICE API: i18n class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage utils
 * @since 1.0
 */

/**
 * Make i18n Easy
 *
 * @package ICE
 * @subpackage utils
 */
abstract class ICE_I18n extends ICE_Base
{
	/**
	 * The text domain to translate for
	 *
	 * @var string
	 */
	private $domain;

	/**
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
		if ( ICE_Files::cache($mofile_global)->is_readable() ) {
			return load_theme_textdomain( $domain, $mofile_global );
		} elseif ( ( $mofile_custom ) && ICE_Files::cache($mofile_custom)->is_readable() ) {
			return load_theme_textdomain( $domain, $mofile_custom );
		} else {
			return false;
		}
	}
}

?>
