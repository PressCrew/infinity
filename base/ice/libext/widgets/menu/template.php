<?php
/**
 * ICE API: widget extensions, menu template file
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

<ul id="<?php $this->render_id() ?>" class="<?php $this->render_classes() ?>">
	<?php $this->component()->render_items() ?>
</ul>

<script type="text/javascript">
	// init menu
	jQuery('ul#<?php $this->render_id() ?>').menu();
	// render all of the button script logic
	<?php print $button_script->export(); ?>
</script>