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

/**
 * Make managing components with policies easy
 *
 * Custom implementations are handled via policy objects. Any object which needs access to
 * the implementing policy must extend this class.
 *
 * @package ICE
 * @subpackage base
 */
abstract class ICE_Componentable extends ICE_Base
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
	 * Constructor.
	 *
	 * @param ICE_Policy $policy
	 */
	public function __construct( ICE_Policy $policy )
	{
		// set the policy instance
		$this->policy = $policy;
		// set the shortcut instance
		$this->_policy = $policy;
	}

	/**
	 * Return the policy
	 *
	 * @return ICE_Policy
	 */
	final public function policy()
	{
		return $this->policy;
	}
}
