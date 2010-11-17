jQuery(document).ready(function() {

	/**
	 * Color Picker wrapper
	 */
	pieEasyColorPicker = function ()
	{
		var
			inputEl,
			pickerEl,
			pickerBg = function(color)
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
				inputEl = jQuery(inputSelector);
				pickerEl = jQuery(pickerSelector);

				// attach color picker event
				pickerEl.ColorPicker({
					onBeforeShow: function () {
						// set elements before show
						inputEl = jQuery(inputSelector);
						pickerEl = jQuery(pickerSelector);
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
						pickerBg(color);
					}
				});

				// initialize color on init
				if (inputEl.val()) {
					pickerBg(inputEl.val());
				}
			}
		};
	}();

});