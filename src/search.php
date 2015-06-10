<?php
/**
 * Infinity Theme: search template
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 * @since 1.0
 */

	get_header();
?>
<div id="content" role="main" class="<?php do_action( 'content_class' ); ?>">
	<?php
		do_action( 'open_content' );
		do_action( 'open_search' );

		// have any results?
		if ( have_posts() ):

			// show results ?>
			<header>
				<h1 class="page-title search-title">
					<?php
						printf( __( 'Search Results for: <span>%s</span>', 'infinity-engine' ), get_search_query() );
					?>
				</h1>
			</header>
			<?php
				get_template_part( 'templates/loops/loop', 'search' );

		// no results
		else:

			// show nothing found message ?>
			<div id="post-0" class="post no-results not-found">
				<h2 class="entry-title">
					<?php
						_e( 'Nothing Found', 'infinity-engine' );
					?>
				</h2>
				<div class="entry-content">
					<p>
						<?php
							_e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'infinity-engine' );
						?>
					</p>
					<?php
						get_search_form();
					?>
				</div>
			</div>
			<?php

		// all done
		endif;

		do_action( 'close_search' );
		do_action( 'close_content' );
?>
</div>
<?php
	get_sidebar();
	get_footer();
