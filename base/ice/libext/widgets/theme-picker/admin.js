
function widgetThemePickerDialogInit(dialog, ss)
{
	// activate link
	jQuery('a.icext-widgets-theme-picker-dialog-list-item-activate', dialog)
		.click( function()
		{
			// params
			var data =
				'action=ice_exts_widgets_theme_picker_activate&' +
				jQuery(this).attr('href').split('?')[1]

			// message and image elements
			var message = jQuery(this).siblings('div.icext-widgets-theme-picker-dialog-list-item-message');
			var ss_new = jQuery(this).siblings('img');

			// set loading message
			message.iceEasyFlash('loading', 'Activating theme...');

			// show loading message and exec post
			message.fadeIn(300, function(){
				// send request for option save
				jQuery.post(
					iceEasyGlobalL10n.ajax_url,
					data,
					function(r) {
						var sr = iceEasyAjax.splitResponseStd(r);
						// flash them ;)
						message.fadeOut(300, function() {

							var state = 'alert';

							if (sr.code >= 1 ) {
								ss.attr('src', ss_new.attr('src'));
							} else {
								var state = 'error';
							}

							jQuery(this).iceEasyFlash(state, sr.message).fadeIn();
					});
				});
			});
			return false;
		});
}