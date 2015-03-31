<div id="vertical-activity-tabs" class="activity-type-tabs sidebar-activity-tabs item-list-tabs" role="navigation">
	<ul>
		<?php
			do_action( 'bp_before_activity_type_tab_all' );
		?>

		<li class="selected" id="activity-all">
			<a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/'; ?>" title="<?php _e( 'The public activity for everyone on this site.', 'buddypress' ); ?>"><?php printf( __( 'All Members <span>%s</span>', 'buddypress' ), bp_get_total_site_member_count() ); ?></a>
		</li>

		<?php
			// is user logged in?
			if ( is_user_logged_in() ):

				do_action( 'bp_before_activity_type_tab_friends' );

				// is friends component active?
				if ( bp_is_active( 'friends' ) ):
					// does user have at least one friend?
					if ( bp_get_total_friend_count( bp_loggedin_user_id() ) ):

						// show friends activity item ?>
						<li id="activity-friends">
							<a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_friends_slug() . '/'; ?>" title="<?php _e( 'The activity of my friends only.', 'buddypress' ); ?>"><?php printf( __( 'My Friends <span>%s</span>', 'buddypress' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ); ?></a>
						</li>
						<?php

					endif;
				endif;

				do_action( 'bp_before_activity_type_tab_groups' );

				// is groups component active?
				if ( bp_is_active( 'groups' ) ):
					// is use in at least one group?
					if ( bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ):

						// show groups activity item ?>
						<li id="activity-groups">
							<a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_groups_slug() . '/'; ?>" title="<?php _e( 'The activity of groups I am a member of.', 'buddypress' ); ?>"><?php printf( __( 'My Groups <span>%s</span>', 'buddypress' ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ); ?></a>
						</li>
						<?php

					endif;
				endif;

				do_action( 'bp_before_activity_type_tab_favorites' );

				// does user have at least one favorite?
				if ( bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ):

					// show activity favorites item ?>
					<li id="activity-favorites">
						<a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/favorites/'; ?>" title="<?php _e( "The activity I've marked as a favorite.", 'buddypress' ); ?>"><?php printf( __( 'My Favorites <span>%s</span>', 'buddypress' ), bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ); ?></a>
					</li>
					<?php

				endif;

				do_action( 'bp_before_activity_type_tab_mentions' );

				// show activity mentions ?>
				<li id="activity-mentions">
					<a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/'; ?>" title="<?php _e( 'Activity that I have been mentioned in.', 'buddypress' ); ?>"><?php _e( 'Mentions', 'buddypress' ); ?><?php if ( bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ) : ?> <strong><?php printf( __( '<span>%s new</span>', 'buddypress' ), bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ); ?></strong><?php endif; ?></a>
				</li>
				<?php

			endif;

			do_action( 'bp_activity_type_tabs' );
		?>
	</ul>
</div><!-- .item-list-tabs -->