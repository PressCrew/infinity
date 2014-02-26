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
	 * @param array $settings
	 * @return ICE_Component|boolean
	 */
	public function create( $name, $settings )
	{
		// set default type if necessary
		if ( empty( $settings['type'] ) ) {
			$settings['type'] = self::DEFAULT_COMPONENT_TYPE;
		}

		// make sure the extension is loaded
		$class_name = $this->policy()->extensions()->load( $settings['type'] );

		// try to create new component
		try {
			// construct it
			$component = new $class_name( $name, $settings['type'], $this->policy() );
			// push settings to component
			$component->import_settings( $settings );
			// return it
			return $component;
		// catch missing reqs exception
		} catch ( ICE_Missing_Reqs_Exception $e ) {
			// failed to create component due to missing reqs
			return false;
		}
	}

}
