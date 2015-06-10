<?php
/**
 * Template Name: Archive Template
 *
 * A archive template that displays the latest posts.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package Infinity
 * @subpackage templates
 */

	get_header();
?>
<div id="content" role="main" class="<?php do_action( 'content_class' ); ?>">
	<?php
		do_action( 'before_content' );
		do_action( 'before_page' );
	?>
	<div class="page-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
			// have an posts?
			if ( have_posts() ):
				// loop all posts
				while ( have_posts() ):
					// set up this loop
					the_post();
					// open loop action
					do_action( 'open_loop' );
					// show header ?>
					<header>
						<h1 class="page-title">
							<?php
								the_title();
							?>
						</h1>
					</header>
					<!-- show page thumb -->
					<?php
						get_template_part( 'templates/parts/post-meta-top');
					?>
					<!-- the post -->
					<div class="post" id="post-<?php the_ID(); ?>">
						<?php
							do_action( 'open_loop_page' );
						?>
						<div class="entry">
							<?php
								the_content( '<p class="serif">' . __( 'Read the rest of this page &rarr;', 'infinity-engine' ) . '</p>' );
								get_search_form();
							?>
							<div id="archives" class="grid_24">
								<div id="archives-recent" class="grid_16 alpha">
									<h4>
										<?php _e( 'Last 30 Posts', 'infinity-engine'); ?>
									</h4>
									<ul>
										<?php
											// get some posts
											$archive_30 = get_posts( 'numberposts=10' );
											// loop all posts
											foreach( $archive_30 as $post ):
												// print this item ?>
												<li>
													<a href="<?php the_permalink(); ?>"><?php the_title();?></a>
												</li>
												<?php
											endforeach;
										?>
									</ul>
								</div>
								<div id="archives-month" class="grid_8 omega">
									<h4>
										<?php _e( 'Archives by Month:', 'infinity-engine'); ?>
									</h4>
									<ul>
										<?php
											wp_get_archives( 'type=monthly' );
										?>
									</ul>
								</div>
							</div>
						<div id="archives-subject" class="grid_24">
							<h4>
								<?php _e( 'Archives by Subject:', 'infinity-engine'); ?>
							</h4>
							<ul>
								<?php
									wp_list_categories( 'title_li=' );
								?>
							</ul>
						</div>
					</div>
					<?php
						do_action( 'close_loop_page' );
					?>
				</div>
				<?php
					do_action( 'close_loop' );
				endwhile;
			endif;
	?>
	</div><!-- .page -->
	<?php
		do_action( 'after_content' );
		do_action( 'after_page' );
	?>
</div><!-- #content -->
<?php
	get_sidebar();
	get_footer();
