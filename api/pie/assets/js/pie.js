


jQuery(document).ready(function() {

	/**
	 * color picker wrapper
	 */
	pieEasyColorPicker = function ()
	{
		var inputEl;
		var pickerEl;

		return {
			// Initialize values for a colorpicker
			init: function (inputSelector, pickerSelector)
			{
				// set elements
				var pcp = this;
				inputEl = jQuery(inputSelector);
				pickerEl = jQuery(pickerSelector);

				// attach color picker event
				pickerEl.ColorPicker({
					onBeforeShow: function () {
						pcp.input(jQuery(this).prev('input'));
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
						this.color = '#' + hex;
						pcp.input().val(this.color);
						pcp.bgcolor(this.color);
					}
				});
				
				// set color
				if (inputEl.val()) {
					this.bgcolor(inputEl.val());
				} else {
					inputEl.val(this.bgcolor());
				}
			},

			// Set the current picker input being manipulated
			input: function (el)
			{
				if (el) {
					inputEl = el;
				}
				return inputEl;
			},

			// Set the current picker bg color
			bgcolor: function (color)
			{
				var el = pickerEl.children('div');

				if (color) {
					el.css('background-color', color);
				}

				return el.css('background-color', color);
			}
		};
	}();

});