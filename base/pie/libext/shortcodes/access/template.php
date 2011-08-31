<?php
/**
 * PIE API: shortcode extensions, access shortcode template file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage shortcodes-ext
 * @since 1.0
 */
?>

<?php if ( $content ): ?>
	<?php echo $content ?>
<?php else: ?>
	<div class="alertbox white"><?php _e( 'Sorry, only registered users can see this text.', pie_easy_text_domain ) ?></div>
<?php endif ?>