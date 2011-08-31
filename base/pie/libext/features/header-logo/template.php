<?php
/**
 * PIE API: feature extensions, header logo feature template file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage features
 * @since 1.0
 */
?>

<?php
	$heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div';
?>
<<?php print $heading_tag; ?> id="site-logo">
	<a href="<?php echo home_url( '/'  ); ?>" title="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>">
		<img src="<?php print $this->image_url(); ?>">
	</a>
</<?php print $heading_tag; ?>>