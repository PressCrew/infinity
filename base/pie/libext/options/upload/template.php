<?php
/**
 * PIE API: option extensions, upload template file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
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

<div class="pie-easy-uploader ui-widget">
	<fieldset class="pie-easy-uploader-img ui-widget-content ui-corner-all">
		<legend class="ui-widget-header ui-corner-all"><?php _e('Current Image', pie_easy_text_domain) ?></legend>
		<p><img src="<?php print esc_attr( $attach_url ) ?>" alt="" /></p>
		<div class="pie-easy-uploader-ibar">
			<a><?php _e('Zoom', pie_easy_text_domain) ?></a>
			<a><?php _e('Edit', pie_easy_text_domain) ?></a>
			<a><?php _e('Trash', pie_easy_text_domain) ?></a>
		</div>
		<div class="pie-easy-uploader-zoom" title="<?php _e('Full Size Image', pie_easy_text_domain) ?>">
			<img src="<?php print esc_attr( $attach_url ) ?>"  height="<?php print esc_attr( $attach_height ) ?>" width="<?php print esc_attr( $attach_width ) ?>" alt="">
		</div>
	</fieldset>
	<fieldset class="pie-easy-uploader-stat ui-widget-content ui-corner-all">
		<legend class="ui-widget-header ui-corner-all"><?php _e( 'Upload Status', pie_easy_text_domain ) ?></legend>
		<textarea></textarea><div><p></p></div>
	</fieldset>
	<div class="pie-easy-uploader-btn">
		<input type="button" /><?php
		$this->render_input( 'hidden' ); ?>
	</div>
</div>