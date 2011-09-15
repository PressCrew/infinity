<?php
/**
 * PIE API: section extensions, default section template file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage sections
 * @since 1.0
 */
?>
<div class="<?php $this->render_classes( 'pie-easy-sections-block' ) ?>">
	<div class="ui-widget-header ui-state-active <?php $this->render_class_title( 'pie-easy-sections-title' ) ?>">
		<?php $this->render_title(); ?> <?php _e( 'Options', pie_easy_text_domain ); ?>
	</div>
	<div class="<?php $this->render_class_content( 'pie-easy-sections-content' ) ?>">
		<?php $this->component()->render_components() ?>
	</div>
</div>
