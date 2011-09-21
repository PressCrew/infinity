<?php
/**
 * PIE API: shortcode extensions, access shortcode template file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage shortcodes
 * @since 1.0
 */
?>

<?php if ( $content ): ?>
	<div id="<?php $this->render_id() ?>" class="<?php $this->render_classes() ?>">
		<?php echo $content ?>
	</div>
<?php else: ?>
	<div class="alertbox white"><?php _e( 'Sorry, only registered users can see this text.', pie_easy_text_domain ) ?></div>
<?php endif ?>