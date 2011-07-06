<?php
/**
 * PIE API: base positioning class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

/**
 * Make static positioning of components easy
 *
 * @package PIE
 * @subpackage base
 * @property-read null|integer $priority
 */
class Pie_Easy_Position
{
	/**
	 * The priority
	 *
	 * Null means "auto" priority
	 *
	 * @var null|integer
	 */
	private $priority;

	/**
	 * Constructor
	 *
	 * @param null|integer $priority If a priority is missing, auto is assumed
	 */
	public function __construct( $priority = null )
	{
		$this->priority = $priority;
	}

	/**
	 * @ignore
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		switch ( $name ) {
			case 'priority':
				return $this->priority;
			default:
				throw new Exception( 'Invalid property' );
		}
	}

	/**
	 * @ignore
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 */
	public function __set( $name, $value )
	{
		switch ( $name ) {
			case 'priority':
				if ( empty( $this->priority ) ) {
					return $this->priority = $value;
				} else {
					throw new Exception( 'Cannot overwrite priority once set' );
				}
		}
	}

	/**
	 * Sort an array of positionable components by priority
	 * 
	 * @param array $components
	 * @return array
	 */
	final static public function sort_priority( $components )
	{
		uasort( $components, array( 'Pie_Easy_Position', '_sort_priority' ) );
		return $components;
	}

	/**
	 * Comparison function for sorting positionable components on priority
	 *
	 * @param Pie_Easy_Positionable $a
	 * @param Pie_Easy_Positionable $b
	 * @return array
	 */
	final static protected function _sort_priority( Pie_Easy_Positionable $a, Pie_Easy_Positionable $b )
	{
		$ap = $a->position()->priority;
		$bp = $b->position()->priority;

		if ( $ap == $bp ) {
			return 0;
		}
		
		return ($ap < $bp) ? -1 : 1;
	}
}

?>
