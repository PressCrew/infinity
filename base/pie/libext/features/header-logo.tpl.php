<?php
	$heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div';
?>
<<?php print $heading_tag; ?> id="site-logo">
	<a href="<?php echo home_url( '/'  ); ?>" title="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>">
		<img src="<?php print $this->image_url(); ?>">
	</a>
</<?php print $heading_tag; ?>>