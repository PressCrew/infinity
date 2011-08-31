<?php
/**
 * PIE API: feature extensions, gravatar feature template file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage features-ext
 * @since 1.0
 */
?>
<?php if ( in_the_loop() ): ?>
	<img src="<?php print $this->url() ?>" class="<?php print $this->_image_class ?>">
<?php endif; ?>