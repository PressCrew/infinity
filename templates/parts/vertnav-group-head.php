	<div id="group-sidebar">
		<div id="item-header-avatar">
			<?php
				bp_group_avatar();
			?>
		</div>
	</div>
	<div id="item-buttons">
		<?php
			do_action( 'bp_group_header_actions' );
		?>
	</div>