<?php
/**
 * PIE API: section class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage sections
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base' );

/**
 * Make an option section easy
 *
 * @package PIE
 * @subpackage sections
 * @property-read string $class_title The CSS class for the title of this section
 * @property-read string $class_content The CSS class for the content of this section
 */
abstract class Pie_Easy_Sections_Section extends Pie_Easy_Component
{
	/**
	 * Set the title CSS class
	 *
	 * @param string $class
	 */
	public function set_class_title( $class )
	{
		$this->directives()->set( $this->theme, 'class_title', $class );
	}

	/**
	 * Set the content CSS class
	 *
	 * @param string $class
	 */
	public function set_class_content( $class )
	{
		$this->directives()->set( $this->theme, 'class_content', $class );
	}

}

?>
