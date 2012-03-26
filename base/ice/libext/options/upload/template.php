<?php
/**
 * ICE API: option extensions, upload template file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage options
 * @since 1.0
 */

//
// Available Variables
//
// $edit_url - URL of image editor
// $attach_url - URL of image attachment
// $attach_width - Width of image attachment (pixels)
// $attach_height - Height of image attachment (pixels)
//

?>

<div class="ice-uploader ui-widget">
	<fieldset class="ice-uploader-img ui-widget-content ui-corner-all">
		<legend class="ui-widget-header ui-corner-all"><?php _e('Current Image', infinity_text_domain) ?></legend>
		<p><img src="<?php print esc_attr( $attach_url ) ?>" alt="" /></p>
		<div class="ice-uploader-ibar">
			<a><?php _e('Zoom', infinity_text_domain) ?></a>
			<a><?php _e('Edit', infinity_text_domain) ?></a>
			<a><?php _e('Trash', infinity_text_domain) ?></a>
		</div>
		<div class="ice-uploader-zoom" title="<?php _e('Full Size Image', infinity_text_domain) ?>">
			<img src="<?php print esc_attr( $attach_url ) ?>"  height="<?php print esc_attr( $attach_height ) ?>" width="<?php print esc_attr( $attach_width ) ?>" alt="">
		</div>
	</fieldset>
	<fieldset class="ice-uploader-stat ui-widget-content ui-corner-all">
		<legend class="ui-widget-header ui-corner-all"><?php _e( 'Upload Status', infinity_text_domain ) ?></legend>
		<textarea></textarea><div><p></p></div>
	</fieldset>
	<div class="ice-uploader-btn">
		<input type="button" /><?php
		$this->render_input( 'hidden' ); ?>
	</div>
</div>