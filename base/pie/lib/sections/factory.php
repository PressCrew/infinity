<?php
/**
 * PIE API: sections factory class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage sections
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/factory' );

/**
 * Make creating section objects easy
 *
 * @package PIE
 * @subpackage sections
 */
class Pie_Easy_Sections_Factory extends Pie_Easy_Factory
{
	/**
	 * Return an instance of an options component
	 *
	 * @param string $theme
	 * @param string $name
	 * @param array $config
	 * @return Pie_Easy_Sections_Section
	 */
	public function create( $theme, $name, $config )
	{
		$section = parent::create( $theme, $name, $config );

		// css title class
		if ( isset( $config['class_title'] ) ) {
			$section->set_class_title( $config['class_title'] );
		}

		// css content class
		if ( isset( $config['class_content'] ) ) {
			$section->set_class_content( $config['class_content'] );
		}

		return $section;
	}
}

?>
