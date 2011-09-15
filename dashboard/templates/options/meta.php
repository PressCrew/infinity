<?php
/**
 * Infinity Theme: Dashboard options screen, option meta information template
 *
 * DO NOT call template tags to render elements in this template!
 *
 * @package infinity
 * @subpackage dashboard-templates
 */
?>
<div class="infinity-cpanel-options-last-modified">
	<?php _e('Last Modified:', infinity_text_domain) ?> <?php $this->render_date_updated() ?>
</div>
