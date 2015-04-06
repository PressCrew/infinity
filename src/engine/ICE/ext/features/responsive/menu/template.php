<?php
/**
 * ICE API: feature extensions, responsive menu feature template file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2012 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage features
 * @since 1.0
 */

/* @var $this ICE_Renderer */
/* @var $selector string */
/* @var $options string */
?>
<!-- Responsive Menu Feature -->
<script type="text/javascript">
//<![CDATA[
	jQuery(document).ready(function($)
	{
		// init mobile menu
		$( '<?php echo $selector ?>' ).mobileMenu(<?php echo $options ?>);
	});
//]]>
</script>
