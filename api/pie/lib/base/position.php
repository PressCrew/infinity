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
abstract class Pie_Easy_Position
{
	/**
	 * The offset
	 *
	 * Negative offsets may work in some situation. Null means "auto" offset.
	 *
	 * @var null|integer
	 */
	private $offset;

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
	 * @param null|integer $offset If an offset is missing, auto is assumed
	 * @param null|integer $priority If a priority is missing, auto is assumed
	 */
	public function __construct( $offset = null, $priority = null )
	{
		$this->offset = $offset;
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
			case 'offset':
				return $this->offset;
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
			case 'offset':
				if ( empty( $this->offset ) ) {
					return $this->offset = $value;
				} else {
					throw new Exception( 'Cannot overwrite offset once set' );
				}
			case 'priority':
				if ( empty( $this->priority ) ) {
					return $this->priority = $value;
				} else {
					throw new Exception( 'Cannot overwrite priority once set' );
				}
		}
	}
}

?>
