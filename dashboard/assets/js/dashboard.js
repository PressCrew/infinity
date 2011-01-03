(function($){
	$(document).ready(function() {

		// cpanel toolbar
		$('a#infinity-cpanel-toolbar-start').button({icons: {primary: "ui-icon-power"}});
		$('a#infinity-cpanel-toolbar-widgets').button({icons: {primary: "ui-icon-gear"}});
		$('a#infinity-cpanel-toolbar-shortcodes').button({icons: {primary: "ui-icon-copy"}});
		$('a#infinity-cpanel-toolbar-options').button({icons: {primary: "ui-icon-wrench"}});
		$('a#infinity-cpanel-toolbar-docs').button({icons: {primary: "ui-icon-document"}});
		$('a#infinity-cpanel-toolbar-about').button({icons: {primary: "ui-icon-person"}});
		$('a#infinity-cpanel-toolbar-thanks').button({icons: {primary: "ui-icon-heart"}});

		// cpanel options page menu
		$('div#infinity-cpanel-options-menu').accordion({
			collapsible: true,
			fillSpace: true,
			icons: {
				header: 'ui-icon-folder-collapsed',
				headerSelected: 'ui-icon-folder-open'
			}
		});

		// cpanel options page menu item clicks
		$('div#infinity-cpanel-options-menu ul li a').bind('click',
			function(){
				// get option from href
				var option = $(this).attr('href').substr(1);
				// send request for option screen
				$.post(
					ajaxurl,
					{
						'action': 'infinity_options_screen',
						'option_name': option
					},
					function(r) {
						var sr = pieEasyAjax.splitResponseStd(r);
						if (sr.code >= 1) {
							// inject options markup
							$('div#infinity-cpanel-options form').html(sr.content);
							// init uploaders
							$('div.pie-easy-options-fu').each(function(){
								$(this).pieEasyUploader();
							});
							// init options tabs
							$('div.infinity-cpanel-options-single').tabs().css('float', 'left');
							// init submit buttons
							$('a.infinity-cpanel-options-save-one')
								.button({icons: {secondary: "ui-icon-arrowthick-1-e"}});
							$('a.infinity-cpanel-options-save-all')
								.button({icons: {secondary: "ui-icon-arrowthick-2-n-s"}});
						} else {
							alert(sr.message);
							// TODO error message
						}
					}
				);
				return false;
			}
		);

		// cpanel options page menu save button clicks
		$('a.infinity-cpanel-options-save-one, a.infinity-cpanel-options-save-all')
			.live('click', function(){
				// get option from href
				var option = $(this).attr('href').substr(1);
				var data = 'option_name=' + option + '&' + $(this).parents('form').first().serialize()
				return false;
		});

	});
})(jQuery);