<?php
/**
 * PIE API: shortcodes registry
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage shortcodes
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/registry', 'shortcodes/factory' );

/**
 * Make keeping track of shortcodes easy
 *
 * @package PIE
 * @subpackage shortcodes
 */
abstract class Pie_Easy_Shortcodes_Registry extends Pie_Easy_Registry
{
	/**
	 * Load a shortcode into the registry
	 *
	 * @param string $shortcode_name
	 * @param string $shortcode_config
	 * @return Pie_Easy_Component|false
	 */
	protected function load_config_single( $shortcode_name, $shortcode_config )
	{
		// create new shortcode
		$shortcode = $this->policy()->factory()->create(
			$shortcode_config['type'],
			$this->loading_theme,
			$shortcode_name,
			$shortcode_config['title'],
			$shortcode_config['description']
		);

		// parent
		if ( isset( $shortcode_config['parent'] ) ) {
			$shortcode->set_parent( $shortcode_config['parent'] );
		}

		// attribute defaults
		if ( isset( $shortcode_config['attributes'] ) ) {
			$shortcode->set_attributes( $shortcode_config['attributes'] );
		}

		// template
		if ( isset( $shortcode_config['template'] ) ) {
			$shortcode->set_template( $shortcode_config['template'] );
		}

		return $shortcode;
	}

}

?>
