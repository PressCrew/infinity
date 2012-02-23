<?php
/**
 * PIE API: screen extensions, cpanel screen class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage screens
 * @since 1.0
 */

Pie_Easy_Loader::load( 'ui/iconable', 'ui/positionable' );

/**
 * Cpanel screen
 *
 * @package PIE-extensions
 * @subpackage screens
 */
class Pie_Easy_Exts_Screens_Cpanel
	extends Pie_Easy_Screens_Screen
		implements Pie_Easy_Iconable, Pie_Easy_Positionable
{
	/**
	 * The icon object
	 * 
	 * @var Pie_Easy_Icon
	 */
	private $__icon__;

	/**
	 * The position object
	 *
	 * @var Pie_Easy_Position
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
			$this->icon( new Pie_Easy_Icon( $this->icon_primary, $this->icon_secondary ) );
		}

		// set up position
		$this->position( new Pie_Easy_Position( $this->priority ) );
	}

	/**
	 * Set/Return the icon object
	 *
	 * @param Pie_Easy_Icon $icon
	 * @return Pie_Easy_Icon
	 */
	final public function icon( Pie_Easy_Icon $icon = null )
	{
		if ( func_num_args() >= 1 ) {
			$this->__icon__ = $icon;
		}

		return $this->__icon__;
	}

	/**
	 * Set/Return the position object
	 *
	 * @param Pie_Easy_Position $position
	 * @return Pie_Easy_Position
	 */
	final public function position( Pie_Easy_Position $position = null )
	{
		if ( $position ) {
			$this->__position__ = $position;
		}

		return $this->__position__;
	}
}

?>
