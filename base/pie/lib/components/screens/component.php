<?php
/**
 * PIE API: screen class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage screens
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/component' );

/**
 * Make a display screen easy
 *
 * @package PIE-components
 * @subpackage screens
 */
abstract class Pie_Easy_Screens_Screen extends Pie_Easy_Component
{
	/**
	 */
	protected function init()
	{
		// run parent
		parent::init();

		// init directives
		$this->url = null;
		$this->target = null;
	}
	
	/**
	 */
	public function configure()
	{
		// RUN PARENT FIRST!
		parent::configure();

		// get config
		$config = $this->config();

		// url where to find the screen
		// @todo add this to documentation when stable
		if ( isset( $config->url ) ) {
			$this->url = $config->url;
		}

		// target of the screen menu link
		// @todo add this to documentation when stable
		if ( isset( $config->target ) ) {
			$this->target = $config->target;
		}
	}
}

?>
