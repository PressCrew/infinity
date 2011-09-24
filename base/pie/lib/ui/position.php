<?php
/**
 * PIE API: base positioning class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage ui
 * @since 1.0
 */

/**
 * Make static positioning of components easy
 *
 * @package PIE
 * @subpackage ui
 * @property-read null|integer $priority
 */
class Pie_Easy_Position extends Pie_Easy_Base
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
	 */
	public function __get( $name )
	{
		switch ( $name ) {
			case 'priority':
				return $this->priority;
			default:
				return parent::__get( $name );
		}
	}

	/**
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
			default:
				return parent::__set( $name, $value );
		}
	}

	/**
	 */
	public function __isset( $name )
	{
		switch ( $name ) {
			case 'priority':
				return isset( $this->priority );
			default:
				return parent::__isset( $name );
		}
	}

	/**
	 */
	public function __unset( $name )
	{
		switch ( $name ) {
			case 'priority':
				if ( $this->priority !== null ) {
					throw new Exception( 'Cannot unset priority once set' );
				}
			default:
				return parent::__unset( $name );
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
