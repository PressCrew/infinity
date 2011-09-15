<?php
/**
 * PIE API: widget extensions, debugger template file
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

<div id="<?php print $element_id ?>" class="pie-easy-exts-widget-debugger ui-widget">
	<div class="ui-widget-header">
		<?php $this->render_title() ?>
	</div>
	<div class="ui-widget-content">
		<?php $this->component()->render_items() ?>
	</div>
</div>

<script type="text/javascript">
	jQuery('div#<?php print $element_id ?> div.ui-widget-content')
		.jstree({
			'plugins': ['html_data','themeroller'],
			'core': {'animation': 0}
		});
</script>