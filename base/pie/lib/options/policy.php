<?php
/**
 * PIE API: options policy class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/policy' );

/**
 * Make customizing options implementations easy
 *
 * This object is passed to each option allowing the implementing API to
 * customize the implementation without confusing hooks and such.
 *
 * @package PIE
 * @subpackage options
 */
abstract class Pie_Easy_Options_Policy extends Pie_Easy_Policy
{
	/**
	 * @var Pie_Easy_Options_Uploader
	 */
	private $uploader;

	/**
	 * @return string
	 */
	public function get_handle()
	{
		return 'options';
	}

	/**
	 * Return a new instance of the options uploader class
	 *
	 * @return Pie_Easy_Options_Uploader
	 */
	abstract public function new_uploader();
	
	/**
	 * Return the options uploader instance
	 *
	 * @return Pie_Easy_Options_Uploader
	 */
	final public function uploader()
	{
		if ( !$this->uploader instanceof Pie_Easy_Options_Uploader ) {
			$this->uploader = $this->new_uploader();
		}
		return $this->uploader;
	}
	
}

?>
