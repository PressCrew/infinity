<?php
/**
 * PIE API: sections registry
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage sections
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/registry', 'sections/factory' );

/**
 * Make keeping track of sections easy
 *
 * @package PIE
 * @subpackage sections
 */
abstract class Pie_Easy_Sections_Registry extends Pie_Easy_Registry
{
	/**
	 * Load a section into the registry
	 *
	 * @param string $section_name
	 * @param string $section_config
	 * @return Pie_Easy_Component|false
	 */
	protected function load_config_single( $section_name, $section_config )
	{
		// create new section
		$section = $this->policy()->factory()->create(
			'default',
			$this->loading_theme,
			$section_name,
			$section_config['title']
		);

		// css class
		if ( isset( $section_config['class'] ) ) {
			$section->set_class( $section_config['class'] );
		}

		// css title class
		if ( isset( $section_config['class_title'] ) ) {
			$section->set_class_title( $section_config['class_title'] );
		}

		// css content class
		if ( isset( $section_config['class_content'] ) ) {
			$section->set_class_content( $section_config['class_content'] );
		}

		// section parent
		if ( isset( $section_config['parent'] ) ) {
			$section->set_parent( $section_config['parent'] );
		}

		return $section;
	}

}

?>
