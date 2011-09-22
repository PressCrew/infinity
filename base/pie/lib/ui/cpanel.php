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
	private $policy;
	private $title;
	private $css_id;

	public function __construct( Pie_Easy_Screens_Policy $policy )
	{
		$this->policy = $policy;
	}

	public function render_begin( $title, $css_id )
	{
		$this->title = $title;
		$this->css_id = esc_attr( $css_id );
		
		// render opening markup ?>
		<div id="<?php $this->render_id() ?>" class="pie-easy-ui-cpanel"><?php
	}

	public function render_end()
	{
		// render closing markup ?>
		</div><?php
		
		// buttons script
		$this->render_scripts();
	}
	
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

	public function render_header( $template )
	{
		// render the header container ?>
		<div id="<?php $this->render_id('header') ?>" class="pie-easy-ui-cpanel-header">
			<?php Pie_Easy_Scheme::instance()->locate_template( $template, true ) ?>
		</div><?php
	}

	public function render_toolbar()
	{
		// render the toolbar container ?>
		<div id="<?php $this->render_id('toolbar') ?>" class="pie-easy-ui-cpanel-toolbar">
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
				<a target="<?php $this->render_id('tab',$item->name) ?>" id="<?php $this->render_id('toolbar','menu','item',$item->name) ?>" class="pie-easy-ui-cpanel<?php if ( $children_cnt ): ?> pie-easy-ui-cpanel-context-menu<?php endif; ?>" href="<?php print esc_attr( $item->url ) ?>" title="<?php print esc_attr( $item->title ) ?>"><?php print esc_attr( $item->title ) ?></a>
				<?php if ( $children_cnt ): ?>
					<?php $this->render_toolbar_menu( $children ) ?>
				<?php endif; ?>
			</li><?php
		} ?>
		</ul><?php
	}
	
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

	public function render_tabs()
	{
		// render tabs container ?>
		<div id="<?php $this->render_id('tabs') ?>" class="pie-easy-ui-cpanel-tabs">
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