<?php
/**
 * Infinity Theme: comments template
 *
 * Forked from Twenty Fifteen as of  Infinity v1.2
 *
 * @package Infinity
 * @subpackage templates
 * @since 1.0
 */

	// is password required for this post?
	if ( post_password_required() ) {
		// yes, bail
		return;
	}

	// custom comment callback is null by default
	$custom_comment_callback = null;

	// is custom comment function defined?
	if ( function_exists( 'infinity_list_comments' ) ) {
		// yes, use it
		$custom_comment_callback = 'infinity_list_comments';
	}

	// custom pings callback is null by default
	$custom_pings_callback = null;

	// is custom pings function defined?
	if ( function_exists( 'infinity_list_pings' ) ) {
		// yes, use it
		$custom_pings_callback = 'infinity_list_pings';
	}

?>
<div id="comments" class="comments-area">
	<?php
		// are there any comments?
		if ( have_comments() ):

			// Human Comments

			// try to get some human comments
			$human_comments = 
				wp_list_comments( array(
					'style'       => 'ol',
					'callback' => $custom_comment_callback,
					'type' => 'comment',
					'echo' => false
				) );

			// get any?
			if ( count( $human_comments ) ):
				// have some human comments, continue
				?>
				<h3 class="comments-title">
					<?php
						printf(
							_nx(
								'There is one thought on &ldquo;%2$s&rdquo;',
								'There are %1$s thoughts on &ldquo;%2$s&rdquo;',
								count( $wp_query->comments_by_type['comment'] ),
								'comments title',
								'twentyfifteen'
							),
							number_format_i18n( count( $wp_query->comments_by_type['comment'] ) ),
							get_the_title()
						);
					?>
				</h3>
				<ol class="comment-list">
					<?php
						echo $human_comments;
					?>
				</ol><!-- .comment-list -->
				<?php
					infinity_comment_nav();
			endif;

			// Comment Form

			// are comments still open?
			if ( comments_open() ):
				// spit out comment form
				comment_form();
			else:
				// no further comments message ?>
				<p class="no-comments">
					<?php
						_e( 'Closed to further comments.', 'infinity-engine' );
					?>
				</p>
				<?php
			endif;

			// Ping Comments

			// try to get some ping comments
			$ping_comments =
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'callback' => $custom_pings_callback,
					'type' => 'pings',
					'echo' => false
				) );

			// get any?
			if ( count( $ping_comments ) ):
				// have some ping comments, continue
				?>
				<h4 class="comments-title">
					<?php
						printf(
							_nx(
								'There is one ping to &ldquo;%2$s&rdquo;',
								'There are %1$s pings to &ldquo;%2$s&rdquo;',
								count( $wp_query->comments_by_type['pings'] ),
								'pings title',
								'infinity-engine'
							),
							number_format_i18n( count( $wp_query->comments_by_type['pings'] ) ),
							get_the_title()
						);
					?>
				</h4>
				<?php
					infinity_comment_nav();
				?>
				<ol class="comment-list">
					<?php
						echo $ping_comments;
					?>
				</ol><!-- .comment-list -->
				<?php
					infinity_comment_nav();
			endif;

		// no comments found
		else:

			// are comments closed?
			if ( !comments_open() ) :
				?>
				<p class="no-comments">
					<?php
						_e( 'Comments are closed.', 'infinity-engine' );
					?>
				</p>
				<?php
			endif;

		// all done
		endif;
	?>
</div><!-- .comments-area -->
