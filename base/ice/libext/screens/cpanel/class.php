<?php
/**
 * ICE API: screen extensions, cpanel screen class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage screens
 * @since 1.0
 */

ICE_Loader::load( 'ui/iconable', 'ui/positionable' );

/**
 * Cpanel screen
 *
 * @package ICE-extensions
 * @subpackage screens
 */
class ICE_Exts_Screens_Cpanel
	extends ICE_Screen
		implements ICE_Iconable, ICE_Positionable
{
	/**
	 * The icon object
	 * 
	 * @var ICE_Icon
	 */
	private $__icon__;

	/**
	 * The position object
	 *
	 * @var ICE_Position
	 */
	private $__position__;

	/**
	 */
	protected function init()
	{
		// run parent
		parent::init();

		// initialize directives
		$this->icon_primary = null;
		$this->icon_secondary = null;
		$this->priority = null;
		$this->toolbar = false;
	}

	/**
	 */
	public function configure()
	{
		// RUN PARENT FIRST!
		parent::configure();

		// get config
		$config = $this->config();

		// set directives
		$this->icon_primary = (string) $config->icon_primary;
		$this->icon_secondary = (string) $config->icon_secondary;
		$this->priority = (integer) $config->priority;
		$this->toolbar = (boolean) $config->toolbar;

		// set up icons
		if ( $this->icon() ) {
			// override existing
			if ( $this->icon_primary ) {
				$this->icon()->primary = $this->icon_primary;
			}
			if ( $this->icon_secondary ) {
				$this->icon()->secondary = $this->icon_secondary;
			}
		} else {
			// new icon
			$this->icon( new ICE_Icon( $this->icon_primary, $this->icon_secondary ) );
		}

		// set up position
		$this->position( new ICE_Position( $this->priority ) );
	}

	/**
	 * Set/Return the icon object
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
	 * Set/Return the position object
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

?>
