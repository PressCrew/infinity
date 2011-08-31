<?php
/**
 * PIE API: shortcodes factory class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage shortcodes
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/factory' );

/**
 * Make creating shortcode objects easy
 *
 * @package PIE-components
 * @subpackage shortcodes
 */
class Pie_Easy_Shortcodes_Factory extends Pie_Easy_Factory
{
	/**
	 * Return an instance of an options component
	 *
	 * @param string $theme
	 * @param string $name
	 * @param array $config
	 * @return Pie_Easy_Shortcodes_Shortcode
	 */
	public function create( $theme, $name, $config )
	{
		$shortcode = parent::create( $theme, $name, $config );

		// attribute defaults
		if ( isset( $config['attributes'] ) ) {
			$shortcode->set_attributes( $config['attributes'] );
		}

		return $shortcode;
	}
}

?>
