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

/* SWF Uploader wrapper */
pieEasyFlashUploader = function ()
{	
	var
	log = function (msg, uploader)
		{
			var ta = jQuery('fieldset.pie-easy-options-fu-log textarea', uploader);
			ta.val(ta.val() + msg + "\n");
			ta.scrollTop(ta[0].scrollHeight - ta.height());
		},
	setMediaSrc = function(el, attachId)
	{
		jQuery.post( ajaxurl, {
			action: 'pie_easy_options_uploader_media_url',
			'attachment_id': attachId
		},
		function(rs)
		{
			var r = pieEasyAjax.splitResponse(rs);
			// TODO error handling
			el.attr('src', r[1]);
		});

		return;
	},
	// bind all listeners to selector
	bindListeners = function(uploader)
		{
			return uploader.bindAll(listeners);
		},
	// listeners
	listeners = {
		swfuploadLoaded: function(event)
			{
				log('Waiting for file selection...', this);
			},
		fileQueued: function(event, file)
			{
				log('File queued for upload: ' + file.name);
				jQuery(this).swfupload('startUpload');
			},
		fileQueueError: function(event, file, errorCode, message)
			{
				log('Fatal Error (file queue): ' + message);
			},
		fileDialogStart: function(event)
			{
				log('Opening file dialog...');
			},
		fileDialogComplete: function(event, numFilesSelected, numFilesQueued)
			{
				log('Closing file dialog...');
			},
		uploadStart: function(event, file)
			{
				log('Starting upload: ' + file.name);
			},
		uploadProgress: function(event, file, bytesLoaded)
			{
				log('Upload progress: ' + bytesLoaded);
			},
		uploadSuccess: function(event, file, serverData)
			{
				if (isNaN(serverData)) {
					log('Upload of ' + file.name + ' failed: WordPress did not return an attachment ID');
				} else {
					log('Upload successful: ' + file.name + ' saved as attachment ID ' + serverData );
					setMediaSrc(jQuery('img', this), serverData);
				}
			},
		uploadComplete: function(event, file)
			{
				log('Upload complete!');
				jQuery(this).swfupload('startUpload');
			},
		uploadError: function(event, file, errorCode, message)
			{
				log('Upload error: ' + message);
			}
	},
	// default options
	options = {
		flash_url : pieEasyFlashUploaderL10n.flash_url,
		upload_url: pieEasyFlashUploaderL10n.upload_url,
		post_params: {
			'post_id' : 0,
			'auth_cookie' : pieEasyFlashUploaderL10n.pp_auth_cookie,
			'logged_in_cookie': pieEasyFlashUploaderL10n.pp_logged_in_cookie,
			'_wpnonce' : pieEasyFlashUploaderL10n.pp_wpnonce,
			'short' : 1
		},
		// file upload
		file_post_name: "async-upload",
		file_size_limit : pieEasyFlashUploaderL10n.file_size_limit,
		file_types : "*.png;*.jpg;*.gif",
		file_types_description : "Image Files",
		file_upload_limit : 10,
		file_queue_limit : 0,
		// button
		button_image_url: pieEasyFlashUploaderL10n.button_image_url,
		button_placeholder_id : null,
		button_width: 132,
		button_height: 23,
		button_text: '<span class="button">Select Files</span>',
		button_text_style: ".button { text-align: center; font-weight: bold; font-family:Verdana,Arial,sans-serif; font-size: 11px; text-shadow: 0 1px 0 #FFFFFF; color:#464646; }",
		button_text_top_padding: 3,
		// custom
		custom_settings: {
			cancelButton: ''
		},
		// debug
		debug: false,
		debug_handler: function (message) {alert(message)}
	}

	return {
		// create a new uploader
		create: function (uploaderId)
			{
				// get uploader
				var uploader = jQuery('#' + uploaderId);
				// set options
				options.button_placeholder = jQuery('input[type=button]:eq(0)', uploader)[0];
				options.custom_settings.cancelButton = jQuery('input[type=button]:eq(1)', uploader)[0];
				// return the object
				return bindListeners(uploader).swfupload(options);
			}
	};
}();

/* SWF Uploader wrapper l10n defaults */
pieEasyFlashUploaderL10n = {
	flash_url: null,
	upload_url: null,
		pp_auth_cookie: null,
		pp_logged_in_cookie: null,
		pp_wpnonce: null,
	file_size_limit: null,
	button_image_url: null,
	button_text: null,
	button_text_style: null
};