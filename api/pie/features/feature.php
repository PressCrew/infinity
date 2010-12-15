<?php
/**
 * PIE API base feature class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage features
 * @since 1.0
 */

/**
 * Make a feature easy
 */
abstract class Pie_Easy_Feature
{
	/**
	 * All feature handles are prepended with this prefix template
	 */
	const PREFIX_TPL = '%s-';

	/**
	 * Name of the feature
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Title of the feature
	 *
	 * @var string
	 */
	private $title;

	/**
	 * Description of the feature
	 *
	 * @var string
	 */
	private $description;

	/**
	 * Constructor
	 * 
	 * @param string $name Feature name may only contain alphanumeric characters as well as the hyphen for use as a word seperator.
	 * @param string $title
	 * @param string $desc
	 */
	public function __construct( $name, $title, $desc )
	{
		// name must adhere to a strict format
		if ( preg_match( '/^[a-z0-9]+(-[a-z0-9]+)*$/', $name ) ) {
			$this->name = $this->name_prefix() . $name;
		} else {
			throw new Exception( 'Feature name does not match the allowed pattern.' );
		}

		// set basic string properties
		$this->title = $title;
		$this->description = $desc;
	}

	/**
	 * Allow read access to all properties (for now)
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		return $this->$name;
	}

	/**
	 * Returns true if current theme supports this feature
	 *
	 * @return boolean
	 */
	public function supported()
	{
		return current_theme_supports( $this->get_api_name() );
	}

	/**
	 * Override this to prefix all names with a string
	 *
	 * @return string
	 */
	protected function name_prefix()
	{
		return '';
	}
	
	/**
	 * Get the prefix for API feature
	 *
	 * @return string
	 */
	private function get_api_prefix()
	{
		return sprintf( self::PREFIX_TPL, $this->get_api_slug() );
	}

	/**
	 * Get the full name for API feature
	 *
	 * @return string
	 */
	private function get_api_name()
	{
		return $this->get_api_prefix() . $this->name;
	}

	/**
	 * Return the name of the implementing API
	 *
	 * @return string
	 */
	abstract protected function get_api_slug();

}

?>
