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

<div id="<?php $this->component()->render_debugger_id() ?>" class="pie-easy-exts-widget-debugger ui-widget">
	<div class="ui-widget-header">
		<?php print $this->title ?>
	</div>
	<div class="ui-widget-content">
		<?php $this->component()->render_items() ?>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready( function(){
		widgetsDebuggerInit('div#<?php $this->component()->render_debugger_id() ?> div.ui-widget-content');
	});
</script>