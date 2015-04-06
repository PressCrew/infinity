<?php
/**
 * ICE API: section extensions, default section template file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage sections
 * @since 1.0
 */

/* @var $this ICE_Section_Renderer */
?>
<div <?php $this->render_attrs() ?>>
	<?php $this->component()->render_components() ?>
</div>
