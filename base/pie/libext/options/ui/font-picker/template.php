<?php
/**
 * PIE API: option extensions, ui font picker template file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */
?>

<div id="<?php $this->render_id('toolbar') ?>" class="<?php $this->render_class('toolbar') ?>"></div>

<?php $this->load_template( 1 ) ?>

<div id="<?php $this->render_id('preview') ?>" class="<?php $this->render_class('preview') ?>">
	Here is some text
</div>

<script type="text/javascript">
	(function($){
		var options = {};
		// add application options
		options.jsonUrl = '<?php print $webfont_url ?>';
		options.labelSlant = '<?php _e( 'Slant', pie_easy_text_domain ) ?>';
		options.labelNormal = '<?php _e( 'Normal', pie_easy_text_domain ) ?>';
		options.labelItalic = '<?php _e( 'Italic', pie_easy_text_domain ) ?>';
		options.labelService = '<?php _e( 'Service', pie_easy_text_domain ) ?>';
		options.labelVariant = '<?php _e( 'Thickness', pie_easy_text_domain ) ?>';
		options.labelSubset = '<?php _e( 'Script', pie_easy_text_domain ) ?>';
		// add font picker
		$('div#<?php $this->render_id('toolbar') ?>').fontpicker(options);
	})(jQuery);
</script>