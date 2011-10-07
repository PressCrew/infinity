/*
 * control panel wrapper
 * @todo turn this into a jQuery plugin
 */
function pieEasyCpanel(options)
{
	// options
	// tabLoaded - called after tab panel content has been insterted

	var $ = jQuery;

	// cpanel toolbar buttons
	var tb_menu =
		$('a.pie-easy-ui-cpanel-toolbar-menu')
			.button({icons:{secondary: "ui-icon-triangle-1-s"}});

	var tb_start =
		$('a#' + options.startButtonId);

	var tb_refresh =
		$('a.pie-easy-ui-cpanel-toolbar-refresh')
			.button({icons: {primary: "ui-icon-refresh"}})
			.attr('href', tb_start.attr('href'))
			.attr('target', tb_start.attr('target'));

	var tb_scroll =
		$('input.pie-easy-ui-cpanel-toolbar-scroll')
			.button({
				icons: {primary: "ui-icon-arrow-2-n-s"},
				create: function(event, ui){
					if (Boolean(Number($.cookie('pie_easy_ui_cpanel_scroll')))) {
						$(this).attr('checked', 'checked').button('refresh');
					}
				}
			}).click(function() {
				initScroll();
			});

	// menus
	$('a.pie-easy-ui-cpanel-context-menu').each(function() {
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
	var cpanel = $('div#' + options.id).resizable(
		{
			minWidth: 1000,
			minHeight: 600,
			alsoResize: 'div.pie-easy-ui-cpanel-tab'
		}
	);
	var cpanel_t = $('div.pie-easy-ui-cpanel-tabs', cpanel);

	// init cpanel tabs
	cpanel_t.tabs({
		tabTemplate: "<li><a href='#{href}'>#{label}</a><span class='ui-icon ui-icon-close'></span></li>",
		panelTemplate: '<div class="pie-easy-ui-cpanel-tab ui-corner-bottom ui-corner-tr"></div>',
		add: function(event, ui) {
			// update tab target
			$(ui.tab).attr('target', $(ui.tab).prop('hash').substring(1));
			// bind tab loaded event
			if (options.tabLoaded) {
				$(ui.panel).bind('cpanelTabLoaded', options.tabLoaded);
			}
			// select the tab that was just loaded
			cpanel_t.tabs('select', '#' + ui.panel.id);
		},
		remove: function(event, ui) {
			saveTab('rem', ui.tab);
		},
		select: function(event, ui) {
			$.cookie('pie_easy_ui_cpanel_tab_selected', ui.panel.id, {expires: 7});
			tb_refresh.attr('href', $(ui.panel).data('pie_easy_ui.href.loaded'));
			tb_refresh.attr('target', $(ui.tab).attr('target'));
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
		if ( identTab(this) ) {
			loadTab(this);
			return false;
		}
		window.open($(this).attr('href'), $(this).attr('target'));
		return false;
	})

	// close cpanel tab
	$( 'ul.ui-tabs-nav span.ui-icon-close', cpanel_t ).live( "click", function() {
		var index = identTab($(this).prev('a'));
		cpanel_t.tabs( "remove", index );
		return false;
	});

	// return tab id that is being targeted by an anchor
	function identTab(anchor)
	{
		var $a = $(anchor),
			$targ = $a.attr('target');

		if (!$targ) {
			$targ = $a.closest('div.ui-tabs-panel').attr('id');
		}

		if ( ($targ) && $targ.substring(0, 20) == options.id + '-tab-') {
			return $targ;
		} else {
			return false;
		}
	}

	// load content into a tab panel
	function loadTab(anchor)
	{
		var $anchor = $(anchor);
		var href = $anchor.attr('href');
		var tab_id = identTab($anchor);
		var message = $('<div></div>');

		saveTab('add', $anchor);

		if ( !tab_id ) {
			// multiple loading started when i changed this
			var panel_id = $('div.pie-easy-ui-cpanel-tab:visible').attr('id');
			if ( (panel_id) && panel_id.length ) {
				tab_id = panel_id;
			} else {
				return;
			}
		}

		if ( $('div#' + tab_id).length ) {
			// panel exists
			cpanel_t.tabs('select', '#' + tab_id);
		} else {
			// the title
			var title = '';
			// find title from toolbar
			$('div.pie-easy-ui-cpanel-toolbar a').each(function(){
				if ( identTab(this) == tab_id ) {
					title = $(this).attr('title');
					return false; // break!
				}
			});
			// find a title?
			if (title.length) {
				// create new panel
				cpanel_t.tabs('add', '#' + tab_id, title);
			} else {
				// not good
				alert('A tab for ' + tab_id + 'does not exist');
			}
		}

		// update refr button
		tb_refresh.attr('href', href).attr('target', tab_id);

		// find active panel
		var panel = $('div#' + tab_id).empty();

		// store href
		panel.data('pie_easy_ui.href.loaded', href);

		// init message
		panel.prepend(
			message.pieEasyFlash('loading', 'Loading tab content.').fadeIn()
		);

		// get content for the tab
		$.post(
			ajaxurl + '?' + href.split('?').pop().split('#').shift(),
			{'action': options.postAction},
			function(r) {
				var sr = pieEasyAjax.splitResponseStd(r);
				var message = panel.pieEasyFlash('find');
				if (sr.code >= 1) {
					// success
					message.fadeOut(200, function(){
						panel.html(sr.content);
						panel.trigger('cpanelTabLoaded');
					});
				} else {
					// error
					message.fadeOut(300, function(){
						message.pieEasyFlash('error', sr.message).fadeIn();
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
		var c = $.cookie('pie_easy_ui_cpanel_tabs_open');
		var t = (c) ? c.split('||') : [];
		var tt = new Array();
		var h = $a.attr('href');
		var i, s, id;
		var m = false;

		if (cmd == 'get') {
			return t;
		} else {
			id = identTab($a);
			// must id as a tab
			if (!id) {
				id = $a.closest('div.ui-tabs-panel').attr('id');
				$a.prop('target', id);
			}
			// loop all items
			for (i in t) {
				s = t[i].split('|');
				if (s[0] == id) {
					m = true;
					if (cmd == 'rem') {
						continue;
					}
				}
				tt.push(t[i]);
			}
			if (!m && cmd == 'add') {
				tt.push(id + '|' + h);
			}
			// update cookie
			return $.cookie('pie_easy_ui_cpanel_tabs_open', tt.join('||'), {expires: 7});
		}
	}

	// show scroll bars
	function initScroll(panel)
	{
		var checked = tb_scroll.attr('checked');

		if (!panel) {
			panel = cpanel_t.find('div.pie-easy-ui-cpanel-tab:visible');
		}

		panel.toggleClass('pie-easy-ui-cpanel-tab-scroll', checked);
		$.cookie('pie_easy_ui_cpanel_scroll', Number(checked), {expires: 7});

		if (checked == 'checked') {
			// calc heights
			var cp_ot = cpanel.offset().top;
			var cp_hc = ($(window).height() - cp_ot) * 0.9;
			var tp_ot, tp_hc;
			// update heights
			tp_ot = panel.offset().top;
			tp_hc = cp_hc - (tp_ot - cp_ot);
			cpanel.css({'height': cp_hc});
			panel.css({'height': tp_hc - 44});
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
			ts = $.cookie('pie_easy_ui_cpanel_tab_selected'),
			i, s, a;

		if (t.length) {
			for (i in t) {
				s = t[i].split('|');
				a = $('<a></a>').attr('target', s[0]).attr('href', s[1]);
				loadTab(a);
			}
			if (ts) {
				cpanel_t.tabs('option', 'selected', ts);
			}
		} else {
			// load start by default
			loadTab(tb_start);
		}
	}

	// initial load
	if ( cpanel.length ) {
		// initialize tabs
		initTabs();
	}
}