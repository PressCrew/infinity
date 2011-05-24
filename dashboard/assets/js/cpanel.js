(function($){
	$(document).ready(function() {

		// cpanel toolbar buttons
		var tb_menu =
			$('a#infinity-cpanel-toolbar-menu')
				.button({icons:{secondary: "ui-icon-triangle-1-s"}});
		var tb_start =
			$('a#infinity-cpanel-toolbar-start');
		var tb_refresh =
			$('a#infinity-cpanel-toolbar-refresh')
				.button({icons: {primary: "ui-icon-refresh"}})
				.attr('href', tb_start.attr('href'));
		var tb_scroll =
			$('input#infinity-cpanel-toolbar-scroll')
				.button({
					icons: {primary: "ui-icon-arrow-2-n-s"},
					create: function(event, ui){
						if (Boolean(Number($.cookie('infinity_cpanel_scroll')))) {
							$(this).attr('checked', 'checked').button('refresh');
						}
					}
				}).click(function() {
					initScroll();
				});
	   
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
		var cpanel = $('div#infinity-cpanel').resizable(
			{
				minWidth: 1000,
				minHeight: 600,
				alsoResize: 'div.infinity-cpanel-tab'
			}
		);
		var cpanel_t = $('div#infinity-cpanel-tabs', cpanel);

		// init cpanel tabs
		cpanel_t.tabs({
			tabTemplate: "<li><a href='#{href}'>#{label}</a><span class='ui-icon ui-icon-close'></span></li>",
			panelTemplate: '<div class="infinity-cpanel-tab"></div>',
			add: function(event, ui) {
				cpanel_t.tabs('select', '#' + ui.panel.id);
			},
			remove: function(event, ui) {
				saveTab('rem', ui.tab);
			},
			select: function(event, ui) {
				$.cookie('infinity_cpanel_tab_selected', ui.panel.id, {expires: 7});
				tb_refresh.attr('href', $(ui.panel).data('infinity.href.loaded'));
			},
			show: function(event, ui) {
				initScroll($(ui.panel));
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
					loadTab(this);
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

		// scroll cpanel tab
		tb_scroll.click(function()
		{

		});

		// load content into a tab panel
		function loadTab(anchor)
		{
			var $anchor = $(anchor);
			var href = $anchor.attr('href');
			var hash = $anchor.attr('hash');
			var message = $('<div></div>');

			saveTab('add', $anchor);

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
				ajaxurl + '?' + href.split('?').pop().split('#').shift(),
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

		// manage currently open tabs in a cookie
		// valid cmds: 'get', 'add', 'rem'
		function saveTab(cmd, a)
		{
			var $a = $(a);
			var c = $.cookie('infinity_cpanel_tabs_open');
			var t = (c) ? c.split('|') : [];
			var i, id;

			if (cmd == 'get') {
				return t;
			} else {
				// must have a hash
				if ($a.attr('hash')) {
					id = $a.attr('hash').substr(1);
				} else {
					id = $a.closest('div.ui-tabs-panel').attr('id');
					$a.attr('hash', id);
				}
				// remove anchor from tabs
				for (i in t) {
					if (t[i].split('#')[1] == id) {
						t.splice(i, 1);
						break;
					}
				}
				// add it if applies
				if (cmd == 'add') {
					t.push($a.attr('href'));
				}
				// update cookie
				return $.cookie('infinity_cpanel_tabs_open', t.join('|'), {expires: 7});
			}
		}

		// show scroll bars
		function initScroll(panel)
		{
			var checked = (tb_scroll.attr('checked'));

			if (!panel) {
				panel = cpanel_t.find('div.infinity-cpanel-tab:visible');
			}

			panel.toggleClass('infinity-cpanel-tab-scroll', checked);
			$.cookie('infinity_cpanel_scroll', Number(checked), {expires: 7});

			if (checked == true) {
				// calc heights
				var cp_ot = cpanel.offset().top;
				var cp_hc = ($(window).height() - cp_ot) * 0.9;
				var tp_ot, tp_hc;
				// update heights
				tp_ot = panel.offset().top;
				tp_hc = cp_hc - (tp_ot - cp_ot);
				cpanel.css({'height': cp_hc});
				panel.css({'height': tp_hc - 10});
			} else {
				// kill heights
				cpanel.height('auto');
				panel.height('auto');
			}
		}

		// load tabs on page load
		function initTabs()
		{
			var t = saveTab('get'),
				ts = $.cookie('infinity_cpanel_tab_selected');

			if (t.length) {
				for (i in t) {
					loadTab($('<a></a>').attr('href', t[i]));
				}
				if (ts) {
					cpanel_t.tabs('option', 'selected', ts);
				}
			} else {
				// load start by default
				loadTab(tb_start);
			}
		}

		// init options panel
		function initOptionsPanel()
		{
			// skip this if not options panel
			if ( !$('div#infinity-cpanel-options').length ) {
				return;
			}

			// the option form
			var form = $('div#infinity-cpanel-options form');
			// the menu(s)
			var menu = $('div.infinity-cpanel-options-menu');
			// get last option loaded
			var last = $.cookie('infinity_cpanel_option_loaded');

			// setup accordion menu
			menu.accordion({
				autoHeight: false,
				collapsible: true,
				clearStyle: true,
				icons: {
					header: 'ui-icon-folder-collapsed',
					headerSelected: 'ui-icon-folder-open'
				},
				change: function(event, ui) {
					var states = [];
					menu.each(function(){
						var state = [
							$(this).attr('id'),
							$(this).accordion('option', 'active')
						];
						states.push(state.join(','));
					});
					$.cookie('infinity_cpanel_option_menu_states', states.join('|'), {expires: 7});
				}
			});

			// show all options for section
			$('a.infinity-cpanel-options-menu-showall').button().click(function(){
				return false;
			});

			// cpanel options page menu item clicks
			$('div.infinity-cpanel-options-menu a.infinity-cpanel-options-menu-show').bind('click',
				function(){
					optionLoad($(this).attr('id'));
					return false;
				}
			);

			// populate form if empty
			if (form.children().length < 1 && last) {
				// get states from cookie
				var om_states = $.cookie('infinity_cpanel_option_menu_states');
				// get the cookie?
				if (om_states != null) {
					// split at pipe
					om_states = om_states.split('|');
					// activate menus that were open
					if (om_states.length) {
						var om_state_idx, om_state_cur, om_state_menu, om_state_act, om_state_new;
						for (om_state_idx in om_states) {
							om_state_cur = om_states[om_state_idx].split(',');
							om_state_menu = $('#' + om_state_cur[0]);
							om_state_act = om_state_menu.accordion('option', 'active');
							om_state_new = ('false' == om_state_cur[1]) ? false : Number(om_state_cur[1]);
							if (om_state_act !== om_state_new) {
								om_state_menu.accordion('activate', om_state_new);
							}
						}
					}
				}
				// load last option
				optionLoad(last);
			}

			// load option screen
			function optionLoad(id)
			{
				// what to load?
				var load = id.split('___');
				// message element
				var message =
					$('div#infinity-cpanel-options-flash')
						.pieEasyFlash('loading', 'Loading option editor.')
						.fadeIn();
				// empty the form
				form.empty();
				// send request for option screen
				$.post(
					InfinityOptionsL10n.ajax_url,
					{
						'action': 'infinity_options_screen',
						'load_type': load[0],
						'load_name': load[1],
						'pie_easy_options_blog_id': InfinityOptionsL10n.blog_id,
						'pie_easy_options_blog_theme': InfinityOptionsL10n.blog_theme
					},
					function(r) {
						var sr = pieEasyAjax.splitResponseStd(r);
						if (sr.code >= 1) {
							// save as last option screen
							$.cookie('infinity_cpanel_option_loaded', id, {expires: 7});
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
								optionInit(this);
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
			}

			// init an option
			function optionInit(option)
			{
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
			function buildDocMenu(menu, els_head, anchor)
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
					if ( buildDocMenu(item_s, next, anchor) ) {
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
				buildDocMenu(menu, headers, 1);
			});
		}

		// initial load
		if ( cpanel.length ) {
			// initialize tabs
			initTabs();
		} else {
			// init options in case they are displayed on page load
			initOptionsPanel();
			//init docs
			initDocPage();
		}

	});
})(jQuery);