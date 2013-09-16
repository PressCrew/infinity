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
		// buttons script
		$this->render_scripts();
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
	 * Render the control panel menu items
	 *
	 * @param array $items An array of screen objects to render
	 */
	public function render_menu_items( $items = null )
	{
		$close = false;

		if ( empty( $items ) ) {
			$items = $this->policy->registry()->get_roots();
		} else {
			$close = true; ?>
			<ul><?php
		}

		// sort em
		$items = ICE_Position::sort_priority( $items );

		foreach( $items as $item ) {
			$children = $this->policy->registry()->get_children( $item );
			$children_cnt = count( $children ); ?>
			<li>
				<a target="<?php $this->render_button_target( $item ); ?>" id="<?php $this->render_id( 'menu', 'item', $item->property( 'name' ) ) ?>" href="<?php if ( !$children_cnt ) print esc_attr( $item->property( 'url' ) ) ?>" title="<?php print esc_attr( $item->property( 'title' ) ) ?>"><?php print esc_attr( $item->property( 'title' ) ) ?></a>
				<?php if ( $children_cnt ): ?>
					<?php $this->render_menu_items( $children ) ?>
				<?php endif; ?>
			</li><?php
		}

		if ( $close ) : ?>
			</ul>
		<?php endif;
	}

	/**
	 * Render a button target
	 *
	 * @param ICE_Component $item
	 */
	protected function render_button_target( ICE_Component $item )
	{
		// get target
		$target = $item->property( 'target' );

		// was a target set explicitly?
		if ( $target ) {
			// use that target
			print esc_attr( $target );
		}
	}

	/**
	 * Render the control panel toolbar buttons
	 */
	public function render_toolbar_buttons()
	{
		$items = $this->policy->registry()->get_all();
		$items_sorted = ICE_Position::sort_priority( $items );

		foreach( $items_sorted as $item ): ?>
			<?php if ( $item->property( 'toolbar' ) ): ?>
				<a target="<?php $this->render_button_target( $item ); ?>" id="<?php $this->render_id( 'toolbarbutton', $item->property( 'name' ) ) ?>" href="<?php print esc_attr( $item->property( 'url' ) ) ?>" title="<?php print esc_attr( $item->property( 'title' ) ) ?>"><?php print esc_attr( $item->property( 'title' ) ) ?></a>
			<?php endif;
		endforeach;
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
				<li><a href="#<?php  $this->render_id( 'tab', $item->property( 'name' ) ) ?>"><?php echo esc_html( $item->property( 'title' ) ) ?></a></li><?php
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
				<div id="<?php $this->render_id( 'tab', $item->property( 'name' ) ) ?>">
					<p><?php $this->render_tab_content( $item->property( 'name' ) ) ?></p>
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

	/**
	 * Print dynamic buttons javascript
	 */
	protected function render_scripts()
	{
		// get all screens from the registry
		$items = $this->policy->registry()->get_all();

		// make sure we got some items
		if ( count( $items ) ) {

			// new script helper
			$script = new ICE_Script();
			$script->begin_logic();

			foreach( $items as $item ) {
				$conf = null;
				$icons = $item->icon()->config();
				if ( $icons ) {
					$conf = sprintf( '{%s}', $icons );
				}
				// menu button and maybe toolbar button ?>
				$('a#<?php $this->render_id( 'menu', 'item', $item->property( 'name' ) ) ?>').button(<?php print $conf ?>);
				<?php if ( $item->property( 'toolbar' ) ): ?>
					$('a#<?php $this->render_id( 'toolbarbutton', $item->property( 'name' ) ) ?>').button(<?php print $conf ?>);
				<?php endif;
			}

			$logic = $script->end_logic( 'items' );
			$logic->set_property( 'alias', true );
			$logic->set_property( 'ready', true );

		} else {
			return null;
		}

		// begin rendering ?>
		<script type="text/javascript">
			<?php print $script->render(); ?>
		</script><?php
	}
}
