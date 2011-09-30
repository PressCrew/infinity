<?php
/**
 * PIE API: UI control panel class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage ui
 * @since 1.0
 */

Pie_Easy_Loader::load( 'ui/script' );

/**
 * Make cool control panels easy
 *
 * @package PIE
 * @subpackage ui
 */
final class Pie_Easy_Ui_Cpanel extends Pie_Easy_Base
{
	/**
	 * The screens policy instance
	 *
	 * @var Pie_Easy_Screens_Policy
	 */
	private $policy;

	/**
	 * The title of the control panel
	 *
	 * @var string
	 */
	private $title;

	/**
	 * The CSS id of the control panel (also used as a prefix)
	 *
	 * @var string
	 */
	private $css_id;

	/**
	 * Constructor
	 *
	 * @param Pie_Easy_Screens_Policy $policy
	 */
	public function __construct( Pie_Easy_Screens_Policy $policy )
	{
		$this->policy = $policy;
	}

	/**
	 * Render the opening element of the control panel
	 *
	 * @param string $title The title of the control panel
	 * @param string $css_id The CSS id of the control panel (also used as a prefix)
	 */
	public function render_begin( $title, $css_id )
	{
		$this->title = $title;
		$this->css_id = esc_attr( $css_id );
		
		// render opening markup ?>
		<div id="<?php $this->render_id() ?>" class="pie-easy-ui-cpanel ui-widget ui-corner-all"><?php
	}

	/**
	 * Render the closing element of the control panel
	 */
	public function render_end()
	{
		// render closing markup ?>
		</div><?php
		
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

		array_unshift( $tokens, $this->css_id );
		
		print implode( '-', $tokens );
	}

	/**
	 * Render the control panel header
	 *
	 * @param string $template Path to header contents template, relative to theme's root
	 */
	public function render_header( $template )
	{
		// render the header container ?>
		<div id="<?php $this->render_id('header') ?>" class="pie-easy-ui-cpanel-header ui-widget-header ui-corner-top">
			<?php Pie_Easy_Scheme::instance()->locate_template( $template, true ) ?>
		</div><?php
	}

	/**
	 * Render the control panel toolbar
	 */
	public function render_toolbar()
	{
		// render the toolbar container ?>
		<div id="<?php $this->render_id('toolbar') ?>" class="pie-easy-ui-cpanel-toolbar ui-widget-header">
			<a id="<?php $this->render_id('toolbar','menu') ?>" class="pie-easy-ui-cpanel-toolbar-menu pie-easy-ui-cpanel-context-menu" title="<?php print esc_attr( $this->title ) ?>"><?php print esc_html( $this->title ) ?></a>
			<?php $this->render_toolbar_menu() ?>
			<?php $this->render_toolbar_buttons() ?>
			<a id="<?php $this->render_id('toolbar','refresh') ?>" class="pie-easy-ui-cpanel-toolbar-refresh" title="<?php _e('Refresh current tab', pie_easy_text_domain ) ?>">
				<?php _e('Refresh', pie_easy_text_domain ) ?>
			</a>
			<input id="<?php $this->render_id('toolbar','scroll') ?>" class="pie-easy-ui-cpanel-toolbar-scroll" type="checkbox" />
			<label for="<?php $this->render_id('toolbar','scroll') ?>" title="<?php _e('Toggle scroll bars on/off', pie_easy_text_domain ) ?>"><?php _e('Scrolling', pie_easy_text_domain) ?></label>
		</div><?php
	}

	/**
	 * Render the control panel toolbar menu
	 *
	 * @param array $items An array of screen objects to render
	 */
	protected function render_toolbar_menu( $items = null )
	{
		if ( empty( $items ) ) {
			$items = $this->policy->registry()->get_roots(); ?>
			<ul id="<?php $this->render_id('toolbar','menu','items') ?>" class="pie-easy-ui-cpanel-toolbar-menu-items"><?php
		} else { ?>
			<ul><?php
		}

		// sort em
		$items = Pie_Easy_Position::sort_priority( $items );

		foreach( $items as $item ) {
			$children = $this->policy->registry()->get_children( $item );
			$children_cnt = count( $children ); ?>
			<li>
				<a target="<?php $this->render_button_target( $item ) ?>" id="<?php $this->render_id('toolbar','menu','item',$item->name) ?>" class="pie-easy-ui-cpanel<?php if ( $children_cnt ): ?> pie-easy-ui-cpanel-context-menu<?php endif; ?>" href="<?php print esc_attr( $item->url ) ?>" title="<?php print esc_attr( $item->title ) ?>"><?php print esc_attr( $item->title ) ?></a>
				<?php if ( $children_cnt ): ?>
					<?php $this->render_toolbar_menu( $children ) ?>
				<?php endif; ?>
			</li><?php
		} ?>
		</ul><?php
	}

	/**
	 * Render a button target
	 *
	 * @param Pie_Easy_Component $item
	 */
	protected function render_button_target( Pie_Easy_Component $item )
	{
		// was a target set explicitly?
		if ( $item->target ) {
			// use that target
			print esc_attr( $item->target );
		} else {
			// generate a tab target from the name
			$this->render_id( 'tab', $item->name );
		}
	}

	/**
	 * Render the control panel toolbar buttons
	 */
	protected function render_toolbar_buttons()
	{
		$items = $this->policy->registry()->get_all();
		$items = Pie_Easy_Position::sort_priority( $items );

		foreach( $items as $item ): ?>
			<?php if ( $item->toolbar ): ?>
				<a target="<?php $this->render_id('tab',$item->name) ?>" id="<?php $this->render_id('toolbar',$item->name) ?>" class="pie-easy-ui-cpanel-toolbar-button" href="<?php print esc_attr( $item->url ) ?>" title="<?php print esc_attr( $item->title ) ?>"><?php print esc_attr( $item->title ) ?></a>
			<?php endif;
		endforeach;
	}

	/**
	 * Render the control panel tabs container
	 */
	public function render_tabs()
	{
		// render tabs container ?>
		<div id="<?php $this->render_id('tabs') ?>" class="pie-easy-ui-cpanel-tabs ui-widget-content">
			<ul><!-- tabs are injected here --></ul>
		</div><?php
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
			$script = new Pie_Easy_Script();
			$script->begin_logic();

			foreach( $items as $item ) {
				$conf = null;
				$icons = $item->icon()->config();
				if ( $icons ) {
					$conf = sprintf( '{%s}', $icons );
				}
				// menu button and maybe toolbar button ?>
				$('a#<?php $this->render_id() ?>-toolbar-menu-item-<?php print $item->name ?>').button(<?php print $conf ?>);
				<?php if ( $item->toolbar ): ?>
					$('a#<?php $this->render_id() ?>-toolbar-<?php print $item->name ?>').button(<?php print $conf ?>);
				<?php endif;
			}

			$logic = $script->end_logic();
			$logic->alias = true;
			$logic->ready = true;

		} else {
			return null;
		}

		// begin rendering ?>
		<script type="text/javascript">
			<?php print $script->export(); ?>
		</script><?php
	}
}

?>