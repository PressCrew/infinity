(function($){
	$(document).ready(function() {

		// cpanel toolbar buttons
		var b_menu =
			$('a#infinity-cpanel-toolbar-menu')
				.button({icons:{secondary: "ui-icon-triangle-1-s"}});
		var b_start =
			$('a#infinity-cpanel-toolbar-start')
				.button({icons: {primary: "ui-icon-power"}, text: false});
		var b_refresh =
			$('a#infinity-cpanel-toolbar-refresh')
				.button({icons: {primary: "ui-icon-refresh"}})
				.attr('href', b_start.attr('href'));
		// more toolbar buttons
		$('a#infinity-cpanel-toolbar-widgets').button({icons: {primary: "ui-icon-gear"}, text: false});
		$('a#infinity-cpanel-toolbar-shortcodes').button({icons: {primary: "ui-icon-copy"}, text: false});
		$('a#infinity-cpanel-toolbar-options').button({icons: {primary: "ui-icon-wrench"}, text: false});
		$('a#infinity-cpanel-toolbar-docs').button({icons: {primary: "ui-icon-document"}, text: false});
		$('a#infinity-cpanel-toolbar-about').button({icons: {primary: "ui-icon-person"}, text: false});
		$('a#infinity-cpanel-toolbar-thanks').button({icons: {primary: "ui-icon-heart"}, text: false});

		// cpanel element
		var cpanel = $('div#infinity-cpanel');

		// init cpanel tabs
		cpanel.tabs({
			tabTemplate: "<li><a href='#{href}'>#{label}</a><span class='ui-icon ui-icon-close'></span></li>",
			panelTemplate: '<div class="infinity-cpanel-tab"></div>',
			add: function(event, ui) {
				cpanel.tabs('select', '#' + ui.panel.id);
			},
			select: function(event, ui) {
				b_refresh.attr('href', $(ui.panel).data('infinity.href.loaded'));
			}
		}).find('.ui-tabs-nav').sortable({
			cursor: 'move',
			containment: 'parent'
		});

		// load start page by default
		load_tab(b_start);
		
		// add and/or select cpanel tab
		$('a', cpanel).live('click', function(){
			if ( $(this).attr('target') ) {
				return;
			} else {
				load_tab(this);
				return false;
			}
		})

		// close cpanel tab
		$( 'ul.ui-tabs-nav span.ui-icon-close', cpanel ).live( "click", function() {
			var index = $(this).siblings('a').first().attr('href').substring(1);
			cpanel.tabs( "remove", index );
			return false;
		});

		// load content into a tab panel
		function load_tab(anchor) {

			var $anchor = $(anchor);
			var href = $anchor.attr('href');
			var hash = $anchor.attr('hash');
			var message = $('<div></div>');

			if ( !hash.length ) {
				var panel_id = $('div.infinity-cpanel-tab:visible').attr('id');
				if ( panel_id.length ) {
					hash = '#' + panel_id;
				} else {
					return;
				}
			}

			if ( $('div' + hash).length ) {
				// panel exists
				cpanel.tabs('select', hash);
			} else {
				// the title
				var title = '';
				// find title from toolbar
				$('div#infinity-cpanel-toolbar a').each(function(){
					if ( $(this).attr('hash') == hash ) {
						title = $(this).attr('title');
						return false; // break!
					}
				});
				// find a title?
				if (title.length) {
					// create new panel
					cpanel.tabs('add', hash, title);
				} else {
					// not good
					alert('A tab for ' + hash + 'does not exist');
				}
			}

			// update refr button
			b_refresh.attr('href', href);

			// find active panel
			var panel = $('div' + hash).empty();

			// store href
			panel.data('infinity.href.loaded', href);

			// init message
			panel.prepend(
				message.pieEasyFlash('loading', 'Loading tab content.').fadeIn()
			);

			// get content for the tab
			$.post(
				InfinityDashboardL10n.ajax_url + '?' + href.split('?').pop(),
				{ 'action': 'infinity_tabs_content' },
				function(r) {
					var sr = pieEasyAjax.splitResponseStd(r);
					var message = panel.pieEasyFlash('find');
					if (sr.code >= 1) {
						// success
						message.fadeOut(200, function(){
							panel.html(sr.content);
							initOptionsPanel();
						});
					} else {
						// error
						message.fadeOut(300, function(){
							message.pieEasyFlash('error', sr.content).fadeIn();
						});
					}
				}
			);
		}

		// init options panel
		function initOptionsPanel()
		{	
			// skip this if not options panel
			if ( !$('div#infinity-cpanel-options').length ) {
				return;
			}

			// cpanel options page menu
			$('div.infinity-cpanel-options-menu').accordion({
				autoHeight: false,
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
							'section_name': section
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
									option_init(this);
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

			// init an option
			function option_init(option) {

				var $option = $(option);

				// tabs
				$option.tabs().css('float', 'left');

				// save buttons
				$('a.infinity-cpanel-options-save', $option)
					.first().button({icons: {secondary: "ui-icon-arrowthick-2-n-s"}})
					.next().button({icons: {secondary: "ui-icon-arrowthick-1-e"}});

				// save buttons click
				$('a.infinity-cpanel-options-save', $option).click( function(){

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
			}

		}

	});
})(jQuery);