<?php
/**
 * PIE API: widget extensions, menu template file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage widgets
 * @since 1.0
 */
?>

<ul class="pie-easy-exts-widget-menu" id="<?php print $this->get_menu_id() ?>">
	<?php $this->render_items() ?>
</ul>

<script type="text/javascript">
	jQuery(document).ready( function(){
		widgetMenuInit(
			'ul#<?php print $this->get_menu_id() ?>',
			<?php print $this->get_menu_buttons_func(); ?>
		);
	});
</script>