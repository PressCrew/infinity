<?php
/**
 * PIE API: widget extensions, title block widget class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage widgets
 * @since 1.0
 */

Pie_Easy_Loader::load( 'components/widgets/component' );

/**
 * Title block widget
 *
 * @package PIE-extensions
 * @subpackage widgets
 */
class Pie_Easy_Exts_Widgets_Title_Block
	 extends Pie_Easy_Widgets_Widget
{
	public function render( $output = true )
	{
		if ( !$output ) {
			ob_start();
		}

		$this->open_block();
		parent::render( true );
		$this->close_block();

		if ( !$output ) {
			return ob_get_clean();
		}
	}

	protected function open_block()
	{
		// render the opening block html ?>
		<div class="<?php print $this->get_element_class( 'block' ) ?> ui-widget">
			<div class="ui-widget-header"><?php print esc_html( $this->title ) ?></div>
			<div class="ui-widget-content"><?php
	}
	
	protected function close_block()
	{
		// render the closing block html ?>
			</div>
		</div><?php
	}
}

?>
