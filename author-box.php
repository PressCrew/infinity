<?php
/**
 * Infinity Theme: author box template
 *
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage templates
 * @since 1.0
 */
?>
<?php if ( get_the_author_description() ): ?>
	<div class="author-box">
		<?php
			do_action( 'open_author_box' );
		?>
		<div id="author-description">
			<h3>
				Something about <?php the_author_link(); ?> 
			</h3>
			<?php
				print get_avatar( get_the_author_meta( 'user_email' ), '50' );
				the_author_description();
			?>
			<div id="author-link">
					<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
						<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'twentyeleven' ), get_the_author() ); ?>
					</a>
			</div>
		</div>
		<?php
			do_action( 'close_author_box' );
		?>
	</div>
<?php endif?>	