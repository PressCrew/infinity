
function widgetThemePickerDialogInit(dialog, ss)
{
	// activate link
	jQuery('a.pie-easy-exts-widgets-theme-picker-dialog-list-item-activate', dialog)
		.click( function()
		{
			// params
			var data =
				'action=pie_easy_exts_widgets_theme_picker_activate&' +
				jQuery(this).attr('href').split('?')[1]

			// message and image elements
			var message = jQuery(this).siblings('div.pie-easy-exts-widgets-theme-picker-dialog-list-item-message');
			var ss_new = jQuery(this).siblings('img');

			// set loading message
			message.pieEasyFlash('loading', 'Activating theme...');

			// show loading message and exec post
			message.fadeIn(300, function(){
				// send request for option save
				jQuery.post(
					pieEasyGlobalL10n.ajax_url,
					data,
					function(r) {
						var sr = pieEasyAjax.splitResponseStd(r);
						// flash them ;)
						message.fadeOut(300, function() {

							var state = 'alert';

							if (sr.code >= 1 ) {
								ss.attr('src', ss_new.attr('src'));
							} else {
								var state = 'error';
							}

							jQuery(this).pieEasyFlash(state, sr.message).fadeIn();
					});
				});
			});
			return false;
		});
}