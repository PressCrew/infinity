<div id="profile-sidebar">

	<div id="item-header-avatar">
		<a href="<?php bp_user_link(); ?>"><?php bp_displayed_user_avatar( 'type=full' ); ?></a>
	</div><!-- #item-header-avatar -->

	<div id="item-buttons">
		<?php
			do_action( 'bp_member_header_actions' );
		?>
	</div><!-- #item-buttons -->

	<?php
		// show quick menu for own profile page
		if ( bp_is_my_profile() ):
			// show profile nav ?>
			<div id="profile-nav-menu">
			<?php
				$userLink = bp_get_loggedin_user_link();
				// show quick menu ?>
				<ul>
					<li id="edit-profile">
						<a class="button edit-profile-button" href="<?php echo $userLink; ?>profile/edit"><?php _e( 'Edit My Profile', 'infinity-engine' ); ?></a>
					</li>
					<li id="edit-avatar">
						<a class="button edit-avatar-button" href="<?php echo $userLink; ?>profile/change-avatar"><?php _e( 'Change Avatar', 'infinity-engine' ); ?></a>
					</li>
					<li id="edit-password">
						<a class="button edit-password-button" href="<?php echo $userLink; ?>settings"><?php _e( 'Email/Password Settings', 'infinity-engine' ); ?></a>
					</li>
					<li id="edit-notifications">
						<a class="button edit-notifications-button" href="<?php echo $userLink; ?>settings/notifications/"><?php _e( 'Notification Settings', 'infinity-engine' ); ?></a>
					</li>
				</ul>
			</div>
			<?php
		endif;
	?>
</div>