<?php
/**
 * Infinity Theme: screen extensions, cpanel screen class file.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage screens
 * @since 1.0
 */

ICE_Loader::load( 'ui/iconable', 'ui/positionable' );

/**
 * Cpanel screen.
 *
 * @package Infinity
 * @subpackage extensions
 */
class Infinity_Ext_Screen_Cpanel
	extends ICE_Screen
		implements ICE_Iconable, ICE_Positionable
{
	/**
	 * The icon object.
	 * 
	 * @var ICE_Icon
	 */
	private $__icon__;

	/**
	 * The position object.
	 *
	 * @var ICE_Position
	 */
	private $__position__;

	/**
	 * @var string
	 */
	protected $icon_primary;

	/**
	 * @var string
	 */
	protected $icon_secondary;

	/**
	 * @var integer
	 */
	protected $priority;

	/**
	 * @var boolean
	 */
	protected $toolbar;

	/**
	 */
	public function get_property( $name )
	{
		switch ( $name ) {
			case 'icon_primary':
			case 'icon_secondary':
			case 'priority':
			case 'toolbar':
				return $this->$name;
			default:
				return parent::get_property( $name );
		}
	}

	/**
	 */
	protected function configure()
	{
		// RUN PARENT FIRST!
		parent::configure();

		// import settings
		$this->import_settings(
			array(
				'icon_primary',
				'icon_secondary',
				'priority',
				'toolbar'
			),
			array(
				'priority' => 'integer',
				'toolbar' => 'boolean'
			)
		);

		// set up icons
		if ( $this->icon() ) {
			// override existing
			if ( $this->icon_primary ) {
				$this->icon()->set_property( 'primary', $this->icon_primary );
			}
			if ( $this->icon_secondary ) {
				$this->icon()->set_property( 'secondary', $this->icon_secondary );
			}
		} else {
			// new icon
			$this->icon( new ICE_Icon( $this->icon_primary, $this->icon_secondary ) );
		}

		// set up position
		$this->position( new ICE_Position( $this->priority ) );
	}

	/**
	 * Set/Return the icon object.
	 *
	 * @param ICE_Icon $icon
	 * @return ICE_Icon
	 */
	final public function icon( ICE_Icon $icon = null )
	{
		if ( func_num_args() >= 1 ) {
			$this->__icon__ = $icon;
		}

		return $this->__icon__;
	}

	/**
	 * Set/Return the position object.
	 *
	 * @param ICE_Position $position
	 * @return ICE_Position
	 */
	final public function position( ICE_Position $position = null )
	{
		if ( $position ) {
			$this->__position__ = $position;
		}

		return $this->__position__;
	}
}
