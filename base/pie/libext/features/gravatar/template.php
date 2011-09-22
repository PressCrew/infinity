<?php
/**
 * PIE API: feature extensions, gravatar feature template file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage features
 * @since 1.0
 */
?>
<?php if ( in_the_loop() ): ?>
	<img src="<?php print $this->url() ?>" id="<?php $this->render_id() ?>" class="<?php $this->render_classes( $this->component()->image_class ) ?>">
<?php endif; ?>