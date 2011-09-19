
// init all options
function initOptions(panel)
{
	var $ = jQuery;

	// call pie helper
	$(panel).pieEasyOptions( 'init', 'infinity_options_update' );

	// create tabs
	$('div.infinity-cpanel-options-single', panel).each(function(){
		$(this).tabs().css('float', 'left');
	});
}

// init options panel
function initOptionsPanel(panel)
{
	var $ = jQuery;

	// full options panel?
	if ( !$('div#infinity-cpanel-options', panel).length ) {
		// no, just init options and quit
		initOptions(panel);
		return;
	}

	// the option form
	var form = $('div#infinity-cpanel-options form', panel);
	// the menu(s)
	var menu = $('div.infinity-cpanel-options-menu', panel);
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
	$('a.infinity-cpanel-options-menu-showall', panel).button().click(function(){
		return false;
	});

	// cpanel options page menu item clicks
	$('div.infinity-cpanel-options-menu a.infinity-cpanel-options-menu-show', panel).bind('click',
		function(){
			loadOption($(this).attr('id'), panel);
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
		loadOption(last, panel);
	}

	// load option screen
	function loadOption(id, panel)
	{
		// what to load?
		var load = id.split('___');

		// message element
		var message =
			$('div#infinity-cpanel-options-flash', panel)
				.pieEasyFlash('loading', 'Loading option editor.')
				.fadeIn();
		// empty the form
		form.empty();
		// send request for option screen
		$.post(
			pieEasyGlobalL10n.ajax_url,
			{
				'action': 'infinity_options_screen',
				'load_section': load[1],
				'load_option': (load[3]) ? load[3] : ''
			},
			function(r) {
				var sr = pieEasyAjax.splitResponseStd(r);
				if (sr.code >= 1) {
					// save as last option screen
					$.cookie('infinity_cpanel_option_loaded', id, {expires: 7});
					// inject options markup
					form.html(sr.content);
					// init docs and options
					initDocuments(panel);
					initOptions(panel);
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
}

/* docs initializer */
function initDocuments(panel)
{
	var $ = jQuery,
		anchor = 0;

	// recursive menu builder
	function buildDocMenu(menu, els_head)
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
			if ( buildDocMenu(item_s, next) ) {
				item.append(item_s);
			}
			// yay
			return did_one = true;
		});

		return did_one;
	}

	$('div.infinity-docs', panel).each(function(){
		var menu = $('ul.infinity-docs-menu', this);
		var headers = $('h3', this);
		buildDocMenu(menu, headers, 1);
	});
}