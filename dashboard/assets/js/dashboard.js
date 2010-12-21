(function($){
	$(document).ready(function() {

		$('a#infinity-cpanel-toolbar-start').button({icons: {primary: "ui-icon-power"}});
		$('a#infinity-cpanel-toolbar-widgets').button({icons: {primary: "ui-icon-gear"}});
		$('a#infinity-cpanel-toolbar-shortcodes').button({icons: {primary: "ui-icon-copy"}});
		$('a#infinity-cpanel-toolbar-options').button({icons: {primary: "ui-icon-wrench"}});
		$('a#infinity-cpanel-toolbar-docs').button({icons: {primary: "ui-icon-document"}});
		$('a#infinity-cpanel-toolbar-about').button({icons: {primary: "ui-icon-person"}});
		$('a#infinity-cpanel-toolbar-thanks').button({icons: {primary: "ui-icon-heart"}});

		/**
		 * Option section click event
		 */
		$('.rm_section h3').click(function() {
			// is the element currently displayed?
			var displayed = ($(this).parent().next('.rm_options').css('display') == 'none');
			// toggle active class
			$(this).toggleClass('active', displayed);
			$(this).children('img').toggleClass('active', displayed);
			// toggle slide in/out
			$(this).parent().next('.rm_options').slideToggle('slow');
		});

	});
})(jQuery);