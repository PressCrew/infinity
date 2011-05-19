<?php
/**
 * PIE API: options section class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base' );

/**
 * Make an option section easy
 *
 * @package PIE
 * @subpackage options
 * @property-read string $class_title The CSS class for the title of this section
 * @property-read string $class_content The CSS class for the content of this section
 * @property-read string $parent The parent section (slug)
 */
abstract class Pie_Easy_Sections_Section extends Pie_Easy_Component
{
	/**
	 * The CSS class for the title of this section
	 * @var string
	 */
	private $class_title;

	/**
	 * The CSS class for the content of this section
	 *
	 * @var string
	 */
	private $class_content;

	/**
	 * The parent section
	 * @var string
	 */
	private $parent;

	/**
	 * Set the title CSS class
	 *
	 * @param string $class
	 */
	public function set_class_title( $class )
	{
		$this->class_title = $class;
	}

	/**
	 * Set the content CSS class
	 *
	 * @param string $class
	 */
	public function set_class_content( $class )
	{
		$this->class_content = $class;
	}

	/**
	 * Set the parent section
	 *
	 * @param string $section_name
	 */
	public function set_parent( $section_name )
	{
		if ( $this->name != $section_name ) {
			$this->parent = trim( $section_name );
		} else {
			throw new Exception( sprintf( 'The section "%s" cannot be a parent of itself', $this->name ) );
		}
	}

	/**
	 * Returns true if section is parent of given section
	 *
	 * @param Pie_Easy_Sections_Section $section
	 * @return boolean
	 */
	public function is_parent_of( Pie_Easy_Sections_Section $section )
	{
		return $this->name == $section->parent;
	}

}

?>
