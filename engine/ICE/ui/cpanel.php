<?php
/**
 * ICE API: UI control panel class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage ui
 * @since 1.0
 */

ICE_Loader::load( 'dom/script' );

/**
 * Make cool control panels easy
 *
 * @package ICE
 * @subpackage ui
 */
final class ICE_Ui_Cpanel extends ICE_Base
{
	/**
	 * The screens policy instance
	 *
	 * @var ICE_Screen_Policy
	 */
	private $policy;

	/**
	 * The CSS id of the control panel (also used as a prefix)
	 *
	 * @var string
	 */
	private $id_prefix;

	/**
	 * Constructor
	 *
	 * @param ICE_Screen_Policy $policy
	 */
	public function __construct( ICE_Screen_Policy $policy )
	{
		$this->policy = $policy;
	}

	/**
	 * Render the opening element of the control panel
	 *
	 * @param string $id_prefix The CSS id prefix for dynamic elements
	 */
	public function render_begin( $id_prefix )
	{
		$this->id_prefix = esc_attr( $id_prefix );
	}

	/**
	 * Render the closing element of the control panel
	 */
	public function render_end()
	{
		// nothing special yet
	}

	/**
	 * Render a css id for a control panel element
	 *
	 * @param string $token,... Suffix tokens
	 */
	public function render_id()
	{
		$tokens = func_get_args();

		if ( !empty( $tokens ) ) {
			foreach( (array) $tokens as $key => $token ) {
				$tokens[$key] = esc_attr( $token );
			}
		} else {
			$tokens = array();
		}

		print $this->id_prefix . implode( '-', $tokens );
	}

	/**
	 * Render a button target
	 *
	 * @param ICE_Component $item
	 */
	protected function render_button_target( ICE_Component $item )
	{
		// get target
		$target = $item->get_property( 'target' );

		// was a target set explicitly?
		if ( $target ) {
			// use that target
			print esc_attr( $target );
		}
	}

	/**
	 * Print ui tabs widget list markup
	 *
	 * @param array $names Array of tab names.
	 */
	public function render_tab_list( $names )
	{
		// make sure we got an array
		if ( is_array( $names ) ) {
			// open list ?>
			<ul><?php
			// loop all names
			foreach( $names as $name ) {
				// get item
				$item = $this->policy->registry()->get( $name );
				// render list item ?>
				<li><a href="#<?php  $this->render_id( 'tab', $item->get_name() ) ?>"><?php echo esc_html( $item->get_property( 'title' ) ) ?></a></li><?php
			}
			// close list ?>
			</ul><?php
		}
	}

	/**
	 * Print ui tabs widget panels markup
	 *
	 * @param array $names Array of tab names.
	 */
	public function render_tab_panels( $names )
	{
		// make sure we got an array
		if ( is_array( $names ) ) {
			// loop all items
			foreach( $names as $name ) {
				// get the screen
				$item = $this->policy->registry()->get( $name );
				// render panel item ?>
				<div id="<?php $this->render_id( 'tab', $item->get_name() ) ?>">
					<p><?php $this->render_tab_content( $item->get_name() ) ?></p>
				</div><?php
			}
		}
	}

	/**
	 * Print ui tabs widget panel content for one item
	 *
	 * @param string $name
	 */
	public function render_tab_content( $name )
	{
		// get the screen
		$screen = $this->policy->registry()->get( $name );

		if ( $screen instanceof ICE_Screen ) {
			$screen->render();
		}
	}

}
