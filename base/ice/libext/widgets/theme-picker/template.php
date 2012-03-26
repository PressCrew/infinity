<?php
/**
 * ICE API: widget extensions, theme picker template file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage widgets
 * @since 1.0
 */

?>

<div id="<?php $this->render_id() ?>" class="<?php $this->render_classes() ?> ui-widget">
	<div class="ui-widget-header">
		<?php $this->render_title() ?>
	</div>
	<div class="ui-widget-content">
		<img src="<?php print esc_attr( $this->component()->get_sshot_url( $theme ) ) ?>" width="175">
		<a href="#"><?php _e( 'Launch Theme Browser', infinity_text_domain ) ?></a>
	</div>
</div>

<div id="<?php $this->render_id( 'dialog' ) ?>" class="<?php $this->render_class( 'dialog' ) ?>">
	<div class="<?php $this->render_class( 'dialog', 'list' ) ?>">
		<?php foreach ( $themes as $a_theme ): ?>
			<div class="<?php $this->render_class( 'dialog', 'list', 'item' ) ?>">
				<span><?php printf(__('%1$s %2$s by %3$s'), $a_theme['Title'], $a_theme['Version'], $a_theme['Author']) ; ?></span>
				<img src="<?php print esc_attr( $this->component()->get_sshot_url( $a_theme ) ) ?>" width="175">
				<div class="<?php $this->render_class( 'dialog', 'list', 'item', 'message' ) ?>"></div>
				<a class="<?php $this->render_class( 'dialog', 'list', 'item', 'activate' ) ?>" href="<?php print esc_url( $this->component()->get_activate_url( $a_theme ) ) ?>" title="<?php print esc_attr( sprintf( __('Activate &#8220;%s&#8221;'), $a_theme['Title'] ) ) ?>"><?php _e('Activate') ?></a> |
				<a class="<?php $this->render_class( 'dialog', 'list', 'item', 'preview' ) ?>" href="<?php print esc_url( $this->component()->get_preview_url( $a_theme ) ) ?>" target="<?php print $this->render_id( 'dialog', 'preview' ) ?>" title="<?php print esc_attr( sprintf( __('Preview of &#8220;%s&#8221;'), $a_theme['Title'] ) ) ?>"><?php _e('Preview') ?></a>
			</div>
		<?php endforeach; ?>
	</div>
	<iframe id="<?php $this->render_id( 'dialog', 'preview' ) ?>" class="<?php $this->render_class( 'dialog', 'preview' ) ?>">
		<!-- Preview link will load here -->
	</iframe>
</div>

<script type="text/javascript">

	// current screenshot
	var tscreen =
		jQuery('div#<?php $this->render_id() ?> div.ui-widget-content img');

	// dialog box
	var tbrowser =
		jQuery('div#<?php $this->render_id( 'dialog' ) ?>')
			.dialog({
				autoOpen: false,
				modal: true,
				minHeight: 720,
				minWidth: 800,
				title: '<?php _e( 'Theme Browser', infinity_text_domain ) ?>'
			});

	// launch browser
	jQuery('div#<?php $this->render_id() ?> div.ui-widget-content a')
		.button()
		.click( function ()
		{
			tbrowser.dialog('open');
			return false;
		});

	// handle browser actions
	widgetThemePickerDialogInit(tbrowser, tscreen);
	
</script>