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
 * Make a theme feature easy
 *
 * @package pie
 * @subpackage features
 * @property-read string $name The name of the feature
 * @property-read string $title The title of the feature
 * @property-read string $description The description of the feature
 */
abstract class Pie_Easy_Feature
{
	/**
	 * All feature handles are prepended with this prefix template
	 */
	const PREFIX_TPL = '%s-';

	/**
	 * Name of the feature
	 * @var string
	 */
	private $name;

	/**
	 * Title of the feature
	 * @var string
	 */
	private $title;

	/**
	 * Description of the feature
	 * @var string
	 */
	private $description;

	/**
	 * Initialize the feature.
	 * 
	 * @param string $name Feature name may only contain alphanumeric characters as well as the hyphen for use as a word seperator.
	 * @param string $title The title of the feature
	 * @param string $desc A description of the feature
	 */
	public function __construct( $name, $title, $desc )
	{
		// name must adhere to a strict format
		if ( preg_match( '/^[a-z0-9]+(-[a-z0-9]+)*$/', $name ) ) {
			$this->name = $name;
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
	 * @ignore
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		return $this->$name;
	}

	/**
	 * Returns true if the active theme supports this feature
	 *
	 * @see current_theme_supports()
	 * @return boolean
	 */
	public function supported()
	{
		return current_theme_supports( $this->get_api_name() );
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
	 * If you roll your own parent theme using PIE, this would normally be the
	 * name of that theme. It is used in the prefix of the feature name.
	 *
	 * @return string
	 */
	abstract protected function get_api_slug();

}

?>
