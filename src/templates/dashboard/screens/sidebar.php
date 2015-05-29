<!-- start sidebar wrap -->
<div class="infinity-sidebar-wrap">
	<div class="infinity-sidebar">

		<?php
			get_template_part( 'templates/dashboard/cpanel-sidebar', get_stylesheet() );
		?>

		<div class="infinity-widget">
			<?php //display some useful support info
				infinity_dashboard_support_info();
			?>
		</div>

	</div>
</div>
<!-- end sidebar wrap -->
