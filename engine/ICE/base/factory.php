<?php
/**
 * ICE API: base factory class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage base
 * @since 1.0
 */

ICE_Loader::load(
	'base/componentable',
	'base/extensions'
);

/**
 * Make creating concrete components easy
 *
 * @package ICE
 * @subpackage base
 */
abstract class ICE_Factory extends ICE_Componentable
{
	/**
	 * Component type to use when none configured
	 */
	const DEFAULT_COMPONENT_TYPE = 'default';

	/**
	 * Return an instance of a component
	 *
	 * @param string $name
	 * @param string $type
	 * @return ICE_Component|boolean
	 */
	public function create( $name, $type )
	{
		// set default type if necessary
		if ( empty( $type ) ) {
			$type = self::DEFAULT_COMPONENT_TYPE;
		}

		// call extension create
		return $this->policy()->extensions()->create( $type, $name );
	}

}
