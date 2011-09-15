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

<ul class="pie-easy-exts-widget-menu" id="<?php print $element_id ?>">
	<?php $this->component()->render_items() ?>
</ul>

<script type="text/javascript">
	// init menu
	jQuery('ul#<?php print $element_id ?>').menu();
	// render all of the button script logic
	<?php print $button_script->export(); ?>
</script>