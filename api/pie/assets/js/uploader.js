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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/* SWF Uploader wrapper */
pieEasyFlashUploader = function ()
{
	var
	bbar = function ()
		{
			var bar, btnRem, btnView;

			return {
				construct: function (uploader)
				{
					// the button bar
					bar = jQuery('fieldset.pie-easy-options-fu-img div', uploader);
					// view button
					btnView = bar.children().eq(0).button({
							icons: {
								primary: "ui-icon-zoomin"
							}
					});
					// remove button
					btnRem = bar.children().eq(1).button({
							icons: {
								primary: "ui-icon-trash"
							}
					});
					// display on load?
					if ( attachId(uploader).length ) {
						this.show();
					}
				},
				show: function ()
				{
					bar.fadeIn(750);
				},
				hide: function ()
				{
					bar.fadeOut(750);
				}
			}
		}(),
	status = function ()
		{
			var stat, log, prg, prgTxt;

			return {
				construct: function (uploader)
				{
					stat = jQuery('fieldset.pie-easy-options-fu-stat', uploader);
					log = jQuery('fieldset.pie-easy-options-fu-stat textarea', uploader);
					prg = jQuery('div', stat).progressbar({value: 0});
					prgTxt = jQuery('p', prg);
					this.show();
					return prg;
				},
				destruct: function ()
				{
					this.hide();
					prg.progressbar('destroy');
					prg = null;
				},
				show: function ()
				{
					this.prgMsg();
					this.logClr();
					stat.fadeIn(500);
				},
				hide: function ()
				{
					stat.fadeOut(750);
				},
				logMsg: function (msg)
				{
					log.val(log.val() + msg + "\n");
					log.scrollTop(log[0].scrollHeight - log.height());
				},
				logClr: function ()
				{
					log.val('');
				},
				prgPct: function (pct)
				{
					return prg.progressbar('value', pct);
				},
				prgMsg: function (msg)
				{
					return prgTxt.text(msg);
				}
			}
		}(),
	attachId = function(uploader)
	{
		return jQuery('input[type=hidden]', uploader).val();
	},
	mediaSrc = function(el, attachId)
	{
		jQuery.ajax(
			{
				async: false,
				type: 'POST',
				url: ajaxurl,
				data: {
					'action': 'pie_easy_options_uploader_media_url',
					'attachment_id': attachId
			},
			success: function(rs)
				{
					var r = pieEasyAjax.splitResponse(rs);
					// TODO error handling
					el.attr('src', r[1]);
				}
		});
	},
	// listeners
	listeners = {
		swfuploadLoaded: function(event)
			{
			},
		fileDialogStart: function(event)
			{
				status.construct(this);
				status.logMsg('Opening file dialog...');
				status.prgMsg('Waiting for file to be selected...');
			},
		fileDialogComplete: function(event, numFilesSelected, numFilesQueued)
			{
				status.logMsg('Closing file dialog...');
			},
		fileQueued: function(event, file)
			{
				status.logMsg('File queued for upload: "' + file.name + '"');
				status.prgMsg('Uploading your image...');
				jQuery(this).swfupload('startUpload');
			},
		fileQueueError: function(event, file, errorCode, message)
			{
				switch (errorCode) {
					case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
						alert('You have selected too many files.');
						return status.logMsg('Queue Error: Queue limit exceeded');
					case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
						status.prgMsg('Upload Failed: The file is too big');
						return status.logMsg('Queue Error: File "' + file.name + '" is too large (' + file.size + 'K) [' + message + ']');
					case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
						status.prgMsg('Upload Failed: The file is empty');
						return status.logMsg('Queue Error: Zero byte file (' + file.name + ') [' + message + ']');
					case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
						status.prgMsg('Upload Failed: Invalid File Type');
						return status.logMsg('Queue Error: Invalid file type (' + file.name + ') [' + message + ']');
					default:
						status.prgMsg('Upload Failed: An unknown error occured');
						return status.logMsg('Queue Error: ' + errorCode + ', File name: "' + file.name + '", File size: ' + file.size + ', Message: ' + message);
				}
			},
		uploadStart: function(event, file)
			{
				status.logMsg('Starting upload of: "' + file.name + '"');
				status.prgMsg('Starting your upload...');
			},
		uploadProgress: function(event, file, bytesLoaded, bytesTotal)
			{
				var pct = Math.ceil((bytesLoaded / bytesTotal) * 100);
				status.prgPct(pct);
				status.prgMsg('Your upload is ' + pct + '% complete');
				status.logMsg('Upload progress: ' + pct + '% (' + bytesLoaded + ' bytes)');
				if (pct == 100) {
					status.logMsg('Processing image file...');
					status.prgMsg('Creating thumbnails, please wait...');
				}
			},
		uploadError: function(event, file, errorCode, message)
			{
				status.prgMsg('Upload Failed: See status log');

				switch (errorCode) {
					case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
						return status.logMsg('Upload Error: HTTP Error (' + file.name + ') [' + message + ']');
					case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
						return status.logMsg('Upload Error: No backend file (' + file.name + ') [' + message + ']');
					case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
						return status.logMsg('Upload Error: Upload initialization failed (' + file.name + ') [' + message + ']');
					case SWFUpload.UPLOAD_ERROR.IO_ERROR:
						return status.logMsg('Upload Error: IO Error (' + file.name + ') [' + message + ']');
					case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
						return status.logMsg('Upload Error: Security Error (' + file.name + ') [' + message + ']');
					case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
						return status.logMsg('Upload Error: Upload limit exceeded (' + file.name + ') [' + message + ']');
					case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
						return status.logMsg('Upload Error: The file not found (' + file.name + ') [' + message + ']');
					case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
						return status.logMsg('Upload Error: File validation failed (' + file.name + ') [' + message + ']');
					case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
						return status.logMsg('Upload Error: Cancelled (' + file.name + ') [' + message + ']');
					case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
						return status.logMsg('Upload Error: Stopped (' + file.name + ') [' + message + ']');
					default:
						return status.logMsg('Upload Error: ' + errorCode + ', File name: "' + file.name + '", File size: ' + file.size + ', Message: ' + message);
				}
			},
		uploadSuccess: function(event, file, serverData)
			{
				if (isNaN(serverData)) {
					status.logMsg('Upload of "' + file.name + '" failed: WordPress did not return an attachment ID');
				} else {
					status.logMsg('Upload successful: "' + file.name + '" saved as attachment ID #' + serverData );
					status.prgMsg('Loading image preview...');
					mediaSrc(jQuery('p img', this), serverData);
					bbar.show();
				}
			},
		uploadComplete: function(event, file)
			{
				status.logMsg('Upload of "' + file.name + '" completed.');
				status.prgMsg('Your upload is complete!');

				if (jQuery(this).swfupload('getStats').files_queued >= 1) {
					status.prgMsg('Uploading next file in queue...');
					jQuery(this).swfupload('startUpload');
					return;
				}
				
				status.destruct();
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
				// setup button bar
				bbar.construct(uploader);
				// set options
				options.button_placeholder = jQuery('input[type=button]', uploader)[0];
				// return the object
				return uploader.bindAll(listeners).swfupload(options);
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