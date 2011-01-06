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
				// the form
				var form = $('div#infinity-cpanel-options form').empty();
				// get option from href
				var option = $(this).attr('href').substr(1);
				// message element
				var message =
					$('div#infinity-cpanel-options-flash')
						.pieEasyFlash('loading', 'Loading option editor.')
						.fadeIn();
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
							form.html(sr.content);
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
		$('a.infinity-cpanel-options-save-one, a.infinity-cpanel-options-save-all')
			.live('click', function(){

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
					$.post(ajaxurl, data, function(r) {
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