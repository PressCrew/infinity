(function($){
	$(document).ready(function() {

		// cpanel toolbar buttons
		var tb_menu =
			$('a#infinity-cpanel-toolbar-menu')
				.button({icons:{secondary: "ui-icon-triangle-1-s"}});
		var tb_start =
			$('a#infinity-cpanel-toolbar-start')
				.button({icons: {primary: "ui-icon-power"}});
		var tb_refresh =
			$('a#infinity-cpanel-toolbar-refresh')
				.button({icons: {primary: "ui-icon-refresh"}})
				.attr('href', tb_start.attr('href'));

		// more toolbar buttons
		$('a#infinity-cpanel-toolbar-options').button({icons: {primary: "ui-icon-pencil"}});
		$('a#infinity-cpanel-toolbar-shortcodes').button({icons: {primary: "ui-icon-copy"}});
		$('a#infinity-cpanel-toolbar-widgets').button({icons: {primary: "ui-icon-gear"}});
		$('a#infinity-cpanel-toolbar-docs').button({icons: {primary: "ui-icon-document"}});
		$('a#infinity-cpanel-toolbar-about').button({icons: {primary: "ui-icon-info"}});

		// cpanel menu buttons
		$('li#infinity-cpanel-toolbar-menu-item-start a').button({icons: {primary: "ui-icon-power"}});
		$('li#infinity-cpanel-toolbar-menu-item-options a').button({icons: {primary: "ui-icon-pencil"}});
		$('li#infinity-cpanel-toolbar-menu-item-shortcodes a').button({icons: {primary: "ui-icon-copy"}});
		$('li#infinity-cpanel-toolbar-menu-item-widgets a').button({icons: {primary: "ui-icon-gear"}});
		$('li#infinity-cpanel-toolbar-menu-item-docs a').button({icons: {primary: "ui-icon-document"}});
		$('li#infinity-cpanel-toolbar-menu-item-about a').button({icons: {primary: "ui-icon-info"}});
		$('li#infinity-cpanel-toolbar-menu-item-devs a').button({icons: {primary: "ui-icon-wrench", secondary: "ui-icon-triangle-1-e"}});
			$('li#infinity-cpanel-toolbar-menu-item-ddocs a').button({icons: {primary: "ui-icon-document"}});
			$('li#infinity-cpanel-toolbar-menu-item-api a').button({icons: {primary: "ui-icon-note"}});
			$('li#infinity-cpanel-toolbar-menu-item-repo a').button({icons: {primary: "ui-icon-link"}}).click(function(){alert('External Link');return false;});
		$('li#infinity-cpanel-toolbar-menu-item-comm a').button({icons: {primary: "ui-icon-person",secondary: "ui-icon-triangle-1-e"}});
			$('li#infinity-cpanel-toolbar-menu-item-news a').button({icons: {primary: "ui-icon-signal-diag"}});
			$('li#infinity-cpanel-toolbar-menu-item-support a').button({icons: {primary: "ui-icon-help"}}).click(function(){alert('External Link');return false;});
			$('li#infinity-cpanel-toolbar-menu-item-thanks a').button({icons: {primary: "ui-icon-heart"}});

		// menus
		$('a.infinity-cpanel-context-menu').each(function() {
			var $this = $(this);
			// new menu
			var menu = $this.next().menu({
				select: function(event, ui) {
					$(this).hide();
				},
				input: $(this)
			}).hide();
			// hide other menus
			menu.parent().siblings('li').children('a').click(function(){
				menu.hide();
			});
		}).click(function(event) {
			// menu is next element
			var menu = $(this).next();
			// is it a re-click?
			if (menu.is(":visible")) {
				menu.hide();
				return false;
			}
			// display me
			menu.show()
				.css({'top': 0, 'left': 0})
				.position({
					my: "left top",
					at: "right top",
					offset: "5 -5",
					collision: "fit none",
					of: this
				});
			// hide all menus
			$(document).one("click", function() {
				menu.hide();
			});
			return false;
		});

		// main menu has a special positioning
		tb_menu.click(function(){
			$(this).next().position(
			{
				my: "left top",
				at: "left bottom",
				offset: "-7 4",
				collision: "fit none",
				of: this
			});
		});

		// cpanel elements
		var cpanel = $('div#infinity-cpanel');
		var cpanel_t = $('div#infinity-cpanel-tabs', cpanel);

		// init cpanel tabs
		cpanel_t.tabs({
			tabTemplate: "<li><a href='#{href}'>#{label}</a><span class='ui-icon ui-icon-close'></span></li>",
			panelTemplate: '<div class="infinity-cpanel-tab"></div>',
			add: function(event, ui) {
				cpanel_t.tabs('select', '#' + ui.panel.id);
			},
			select: function(event, ui) {
				tb_refresh.attr('href', $(ui.panel).data('infinity.href.loaded'));
			}
		}).find('.ui-tabs-nav').sortable({
			cursor: 'move',
			containment: 'parent'
		});

		// add and/or select cpanel tab
		$('a', cpanel).live('click', function() {
			if ( $(this).attr('href') ) {
				if ( $(this).attr('target') ) {
					return true;
				} else {
					load_tab(this);
					return false;
				}
			}
			return true;
		})

		// close cpanel tab
		$( 'ul.ui-tabs-nav span.ui-icon-close', cpanel_t ).live( "click", function() {
			var index = $(this).siblings('a').first().attr('href').substring(1);
			cpanel_t.tabs( "remove", index );
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
				cpanel_t.tabs('select', hash);
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
					cpanel_t.tabs('add', hash, title);
				} else {
					// not good
					alert('A tab for ' + hash + 'does not exist');
				}
			}

			// update refr button
			tb_refresh.attr('href', href);

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
				ajaxurl + '?' + href.split('?').pop(),
				{'action': 'infinity_tabs_content'},
				function(r) {
					var sr = pieEasyAjax.splitResponseStd(r);
					var message = panel.pieEasyFlash('find');
					if (sr.code >= 1) {
						// success
						message.fadeOut(200, function(){
							panel.html(sr.content);
							initOptionsPanel();
							initDocPage();
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
						InfinityOptionsL10n.ajax_url,
						{
							'action': 'infinity_options_screen',
							'option_name': option,
							'section_name': section,
							'pie_easy_options_blog_id': InfinityOptionsL10n.blog_id,
							'pie_easy_options_blog_theme': InfinityOptionsL10n.blog_theme
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
								// init docs
								initDocPage();
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
						+ '&pie_easy_options_blog_id=' + InfinityOptionsL10n.blog_id
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
							InfinityOptionsL10n.ajax_url,
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

		// init doc pages
		function initDocPage()
		{
			// recursive menu builder
			function build_menu(menu, els_head, anchor)
			{
				var filter, did_one;

				els_head.each(function(){
					// name of anchor
					var a_name = 'menu_item_' + anchor++;
					// inject before self
					$(this).before($('<a></a>').attr('name', a_name));
					// create list item
					var item = $('<li></li>').appendTo(menu);
					var item_a = $('<a></a>').appendTo(item);
					var item_s = $('<ul></ul>');
					// build up link
					item_a.attr('target', '_self').attr('href', '#' + a_name).html($(this).html());
					// determine next level
					switch (this.tagName) {
						case 'H3':filter = 'h4';break;
						case 'H4':filter = 'h5';break;
						case 'H5':filter = 'h6';break;
						case 'H6':return false;
					}
					// next level headers
					var next = $(this).nextUntil(this.tagName).filter(filter);
					// build sub
					if ( build_menu(item_s, next, anchor) ) {
						item.append(item_s);
					}
					// yay
					return did_one = true;
				});

				return did_one;
			}

			$('div.infinity-docs').each(function(){
				var menu = $('ul.infinity-docs-menu', this);
				var headers = $('h3', this);
				build_menu(menu, headers, 1);
			});
		}

		// initial load
		if ( cpanel.length ) {
			// load start page by default
			load_tab(tb_start);
		} else {
			// init options in case they are displayed on page load
			initOptionsPanel();
			//init docs
			initDocPage();
		}

	});
})(jQuery);