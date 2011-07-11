/**
 * Copyright (C) 2010  Marshall Sorenson
 *
 * Contact Information:
 *     marshall.sorenson@gmail.com
 *     http://marshallsorenson.com
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/* bindAll() helper */
(function($){
	
	/* bindAll() helper */
	$.fn.bindAll = function(options) {
		var $this = this;
		$.each(options, function(key, val){
			$this.bind(key, val);
		});
		return this;
	}
})(jQuery);

/* flash messaging helper */
(function($){

	var methods = {
		find: function()
		{
			return $('.pie-easy-flash', this);
		},
		set: function(state, icon, msg)
		{
			return this.each(function(){
				// maintain chain
				var $this = $(this);
				// create msg
				$(this).html(
					'<div class="ui-widget pie-easy-flash">' +
					'<div class="ui-state-' + state + ' ui-corner-all">' +
					'<p><span class="ui-icon ui-icon-' + icon + '"></span>' + msg + '</p>' +
					'</div></div>'
				);
				// add dismiss button
				if (state != 'default') {
					$(this).find('p').append('<button>Ok</button>');
					$(this).find('button').button().click(function(){
						$this.fadeOut(300, function(){
							$this.empty();
						});
						return false;
					});
				}
			});
		},
		reset: function()
		{
			return this.each(function(){
				$(this).empty();
			});
		},
		alert: function(msg)
		{
			return this.each(function(){
				$(this).pieEasyFlash('set', 'highlight', 'info', msg);
			});
		},
		error: function(msg)
		{
			return this.each(function(){
				$(this).pieEasyFlash('set', 'error', 'alert', msg);
			});
		},
		loading: function(msg)
		{
			return this.each(function(){
				$(this).pieEasyFlash('set', 'default', 'ajax-loader', msg);
			});
		}
	}

	$.fn.pieEasyFlash = function (method)
	{
		if ( methods[method] ) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || ! method) {
			return methods.init.apply(this, arguments);
		} else {
			return $.error('Method ' +  method + ' does not exist on jQuery.pieEasyFlash');
		}
	}
})(jQuery);

/* options helper */
(function($){

	var methods = {

		init: function(action)
		{
			return this.each(function(){
				$('div.pie-easy-options-block', this).pieEasyOptions('initBlock', action);
			});
		},
		
		initBlock: function(action)
		{
			var $form;

			// maintain chain
			return this.each(function(){

				var $option = $(this);

				if ( !$form ) {
					$form = $option.closest('form');
				}

				// init uploaders
				$('div.pie-easy-options-fu', $option).each(function(){
					$(this).pieEasyUploader();
				});

				// save all button
				$('a.pie-easy-options-save-all', $option)
					.button({icons: {secondary: "ui-icon-arrowthick-2-n-s"}});
					
				// save one button
				$('a.pie-easy-options-save-one', $option)
					.button({icons: {secondary: "ui-icon-arrowthick-1-e"}});
				
				// save buttons click
				$('a.pie-easy-options-save', $option).click( function(){

					// get option from href
					var opt_names = $(this).attr('hash').substr(1);

					// form data
					var data =
						'action=' + action +
						'&option_names=' + opt_names +
						'&' + $form.serialize()

					// message element
					var message = $('div.pie-easy-options-mesg', $option);

					// set loading message
					message.pieEasyFlash('loading', 'Saving changes.');

					// show loading message and exec post
					message.fadeIn(300, function(){
						// send request for option save
						$.post(
							pieEasyGlobalL10n.ajax_url,
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
		}
	}

	$.fn.pieEasyOptions = function (method)
	{
		if ( methods[method] ) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || ! method) {
			return methods.init.apply(this, arguments);
		} else {
			return $.error('Method ' +  method + ' does not exist on jQuery.pieEasyOptions');
		}
	}
})(jQuery);

/* AJAX helpers */
pieEasyAjax = {
	splitResponse: function (str)
	{
		return str.split('[[[s]]]');
	},
	splitResponseStd: function (str)
	{
		var sr = this.splitResponse(str);
		return {
			code: sr[0],
			message: sr[1],
			content: sr[2]
		}
	},
	queryParam: function(p, s)
	{
		var i, q = s.split('?').pop().split('#').shift().split('&');

		for (i in q) {
			var pv = q[i].split('=');
			if (pv[0] == p) {
				return pv[1];
			}
		}

		return null;
	}
};

/* colorpicker wrapper */
pieEasyColorPicker = function ()
{
	var
		inputEl,
		pickerEl,
		pickerBg = function(pickerEl, color)
		{
			// get child div
			var el = pickerEl.children('div');
			// set color if needed
			if (color) {
				el.css('background-color', color);
				pickerEl.ColorPickerSetColor(color);
			}
			// return color
			return el.css('background-color', color);
		};

	return {
		// Initialize a colorpicker
		init: function (inputSelector, pickerSelector)
		{
			// set elements on init
			pickerEl = jQuery(pickerSelector);
			inputEl = jQuery(inputSelector).bind('click', function () {
				jQuery(this).bind('keyup', function () {
					pickerBg(jQuery(pickerSelector), this.value);
				});
			});

			// attach color picker event
			pickerEl.ColorPicker({
				onBeforeShow: function () {
					// set elements before show
					pickerEl = jQuery(pickerSelector);
					inputEl = jQuery(inputSelector);
				},
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					color = '#' + hex;
					inputEl.val(color);
					pickerBg(pickerEl, color);
				}
			});

			// initialize color on init
			if (inputEl.val()) {
				pickerBg(pickerEl, inputEl.val());
			}
		}
	};
}();
