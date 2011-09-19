<div class="wrap nosubsub">
	<?php infinity_dashboard_cpanel_ui() ?>
</div>

<script type="text/javascript">
	pieEasyCpanel(
		{
			id: 'infinity-cpanel',
			startButtonId: 'infinity-cpanel-toolbar-start',
			postAction: 'infinity_tabs_content',
			tabLoaded:
				function(event)
				{
					initOptionsPanel(event.target);
					initDocuments(event.target);
				}
		}
	);
</script>
