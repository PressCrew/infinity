<?php
/**
 * PIE API: base componentable class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/policeable' );

/**
 * Make managing components with policies easy
 *
 * Custom implementations are handled via policy objects. Any object which needs access to
 * the implementing policy must extend this class.
 *
 * @package PIE
 * @subpackage base
 */
abstract class Pie_Easy_Componentable implements Pie_Easy_Policeable
{
	/**
	 * Policy instance
	 *
	 * @var Pie_Easy_Policy
	 */
	private $policy;
	
	/**
	 * Return the policy
	 *
	 * @param Pie_Easy_Policy $policy
	 * @return Pie_Easy_Policy
	 */
	final public function policy( Pie_Easy_Policy $policy = null )
	{
		// setter
		if ( $policy ) {
			if ( empty( $this->policy ) ) {
				$this->policy = $policy;
			} else {
				throw new Exception( 'Cannot overwrite policy once set' );
			}
		}

		// getter
		if ( $this->policy instanceof Pie_Easy_Policy ) {
			return $this->policy;
		} else {
			throw new Exception( 'No policy has been set' );
		}
	}
}

?>
