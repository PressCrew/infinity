<?php
/**
 * PIE API: feature extensions, BuddyPress activity intro feature class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage features
 * @since 1.0
 */

Pie_Easy_Loader::load( 'components/features/component_bp' );

/**
 * BuddyPress activity intro feature
 *
 * @package PIE-extensions
 * @subpackage features
 */
class Pie_Easy_Exts_Features_Bp_Activity_Intro
	extends Pie_Easy_Features_Feature_Bp
{
	/**
	 * The intro content to render
	 * 
	 * @var string
	 */
	private $__content__;

	/**
	 */
	protected function init()
	{
		// run parent init method
		parent::init();

		// extra activity entry links
		add_action( 'bp_before_activity_loop', array($this,'render') );
	}

	/**
	 */
	public function renderable()
	{
		// get activity into content from sub option
		$this->__content__ = $this->get_suboption('content')->get();

		// if we have content, we are good to go
		return ( !empty( $this->__content__ ) );
	}

	/**
	 */
	public function get_template_vars()
	{
		return array(
			'content' => $this->__content__
		);
	}
}

?>
