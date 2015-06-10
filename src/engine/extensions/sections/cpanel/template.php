<?php
/**
 * ICE API: section extensions, cpanel section template file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage extensions
 * @since 1.2
 */

/* @var $this ICE_Section_Renderer */
/* @var $option ICE_Option */
?>
<div <?php $this->render_attrs() ?>>
	<div class="ui-widget-header ui-state-active ice-title <?php $this->render_class_title() ?>">
		<?php $this->render_title(); ?> <?php _e( 'Options', 'infinity-engine' ); ?>
	</div>
	<div class="ui-widget-content ice-content <?php $this->render_class_content() ?>">
		<?php //$this->component()->render_components() ?>
		<?php
			foreach( $this->component()->get_components() as $option ):
				include 'template_option.php';
			endforeach;
		?>
	</div>
</div>
