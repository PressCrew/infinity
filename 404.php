<?php
/**
 * Infinity Theme: 404 template
 *
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage templates
 * @since 1.0
 */

	infinity_get_header();
?>
	<div id="content" role="main">
		<?php
			do_action( 'open_404' );
		?>
		<div id="post-0" class="post error404 not-found">
			<h1 class="entry-title">
				<?php _e( 'Not Found', infinity_text_domain ); ?>
			</h1>
			<div class="entry-content">
				<p>
					<?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', infinity_text_domain ); ?>
				</p>
				<?php
					infinity_get_search_form();
				?>
			</div>
		</div>
		<?php
			do_action( 'close_404' );
		?>
	</div>
	<?php
		infinity_get_sidebar();
	?>
	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>
<?php
	infinity_get_footer();
?>
