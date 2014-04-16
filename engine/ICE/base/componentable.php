<?php
/**
 * ICE API: base componentable class file
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
	'base/policeable'
);

/**
 * Make managing components with policies easy
 *
 * Custom implementations are handled via policy objects. Any object which needs access to
 * the implementing policy must extend this class.
 *
 * @package ICE
 * @subpackage base
 */
abstract class ICE_Componentable
	extends ICE_Base
		implements ICE_Policeable
{
	/**
	 * Policy instance.
	 *
	 * @var ICE_Policy
	 */
	private $policy;

	/**
	 * Policy instance shortcut.
	 *
	 * @var ICE_Policy
	 */
	protected $_policy;

	/**
	 * Extensions instance shortcut.
	 *
	 * @var ICE_Extensions
	 */
	protected $_extensions;

	/**
	 * Registry instance shortcut.
	 *
	 * @var ICE_Registry
	 */
	protected $_registry;

	/**
	 * Factory instance shortcut.
	 *
	 * @var ICE_Factory
	 */
	protected $_factory;

	/**
	 * Renderer instance shortcut.
	 *
	 * @var ICE_Renderer
	 */
	protected $_renderer;
	
	/**
	 * Return the policy
	 *
	 * @param ICE_Policy $policy
	 * @return ICE_Policy
	 */
	final public function policy( ICE_Policy $policy = null )
	{
		// setter
		if ( $policy ) {
			if ( empty( $this->policy ) ) {
				// set the policy instance
				$this->policy = $policy;
				// set the shortcut instances
				$this->_policy = $policy;
				$this->_extensions = $policy->extensions();
				$this->_registry = $policy->registry();
				$this->_factory = $policy->factory();
				$this->_renderer = $policy->renderer();
			} else {
				throw new Exception( 'Cannot overwrite policy once set' );
			}
		}

		// getter
		if ( $this->policy instanceof ICE_Policy ) {
			return $this->policy;
		} else {
			throw new Exception( 'No policy has been set' );
		}
	}
}
