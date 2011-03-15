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
		$('div.infinity-cpanel-options-menu').accordion({
			collapsible: true,
			clearStyle: true,
			icons: {
				header: 'ui-icon-folder-collapsed',
				headerSelected: 'ui-icon-folder-open'
			}
		});

		// cpanel options page show all options for section
		$('a.infinity-cpanel-options-menu-showall').button().click(function(){
			return false;
		});

		// cpanel options page menu item clicks
		$('div.infinity-cpanel-options-menu a.infinity-cpanel-options-menu-show').bind('click',
			function(){
				// null vars
				var option, section;
				// the form
				var form = $('div#infinity-cpanel-options form').empty();
				// option or section?
				if ($(this).hasClass('infinity-cpanel-options-menu-showall')) {
					section = $(this).attr('href').substr(1);
				} else {
					option = $(this).attr('href').substr(1);
				}
				// message element
				var message =
					$('div#infinity-cpanel-options-flash')
						.pieEasyFlash('loading', 'Loading option editor.')
						.fadeIn();
				// send request for option screen
				$.post(
					InfinityDashboardL10n.ajax_url,
					{
						'action': 'infinity_options_screen',
						'option_name': option,
						'section_name': section,
					},
					function(r) {
						var sr = pieEasyAjax.splitResponseStd(r);
						if (sr.code >= 1) {
							// inject options markup
							form.html(sr.content);
							// init uploaders
							$('div.pie-easy-options-fu').each(function(){
								$(this).pieEasyUploader();
							});
							// init option reqs
							$('div.infinity-cpanel-options-single').each(function(){
								// tabs
								$(this).tabs().css('float', 'left');
								// save buttons
								$('a.infinity-cpanel-options-save', this)
									.first().button({icons: {secondary: "ui-icon-arrowthick-2-n-s"}})
									.next().button({icons: {secondary: "ui-icon-arrowthick-1-e"}});
							});
							// remove message
							message.fadeOut().empty();
						} else {
							// error
							message.fadeOut(300, function(){
								message.pieEasyFlash('error', sr.content).fadeIn();
							})
						}
					}
				);
				return false;
			}
		);

		// cpanel options page menu save button clicks
		$('a.infinity-cpanel-options-save').live('click', function(){

				// get option from href
				var option = $(this).attr('href').substr(1);

				// form data
				var data =
					'action=infinity_options_update'
					+ '&option_names=' + option
					+ '&' + $(this).parents('form').first().serialize()

				// message element
				var message = $(this).parent().siblings('div.infinity-cpanel-options-single-flash');

				// set loading message
				message.pieEasyFlash('loading', 'Saving changes.');
				
				// show loading message and exec post
				message.fadeIn(300, function(){
					// send request for option save
					$.post(
						InfinityDashboardL10n.ajax_url,
						data,
						function(r) {
							var sr = pieEasyAjax.splitResponseStd(r);
							// flash them ;)
							message.fadeOut(300, function() {
								var state = (sr.code >= 1) ? 'alert' : 'error';
								$(this).pieEasyFlash(state, sr.message).fadeIn();
						});
					});
				});
				
				return false;
		});

	});
})(jQuery);