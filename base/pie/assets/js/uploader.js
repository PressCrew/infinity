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
(function( $ ){

	var listeners = {
		swfuploadLoaded: function(event) {
			// nothing for now
		},
		fileDialogStart: function(event) {
			$(this).pieEasyUploader('status').init();
			$(this).pieEasyUploader('status').logMsg('Opening file dialog...');
			$(this).pieEasyUploader('status').prgMsg('Waiting for file to be selected...');
		},
		fileDialogComplete: function(event, numFilesSelected, numFilesQueued){
			if (numFilesQueued >= 1) {
				$(this).pieEasyUploader('status').logMsg('Closing file dialog...');
				$(this).pieEasyUploader('status').logMsg('Selected ' + numFilesSelected + ' files');
				$(this).pieEasyUploader('status').logMsg('Queued ' + numFilesQueued + ' files');
			} else {
				$(this).pieEasyUploader('status').logMsg('No files selected');
				$(this).pieEasyUploader('status').hide();
			}
		},
		fileQueued: function(event, file) {
			$(this).pieEasyUploader('status').logMsg('File queued for upload: "' + file.name + '"');
			$(this).pieEasyUploader('status').prgMsg('Uploading your image...');
			$(this).swfupload('startUpload');
		},
		fileQueueError: function(event, file, errorCode, message) {
			switch (errorCode) {
				case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
					alert('You have selected too many files.');
					return $(this).pieEasyUploader('status').logMsg('Queue Error: Queue limit exceeded');
				case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
					$(this).pieEasyUploader('status').prgMsg('Upload Failed: The file is too big');
					return $(this).pieEasyUploader('status').logMsg('Queue Error: File "' + file.name + '" is too large (' + file.size + 'K) [' + message + ']');
				case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
					$(this).pieEasyUploader('status').prgMsg('Upload Failed: The file is empty');
					return $(this).pieEasyUploader('status').logMsg('Queue Error: Zero byte file (' + file.name + ') [' + message + ']');
				case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
					$(this).pieEasyUploader('status').prgMsg('Upload Failed: Invalid File Type');
					return $(this).pieEasyUploader('status').logMsg('Queue Error: Invalid file type (' + file.name + ') [' + message + ']');
				default:
					$(this).pieEasyUploader('status').prgMsg('Upload Failed: An unknown error occured');
					return $(this).pieEasyUploader('status').logMsg('Queue Error: ' + errorCode + ', File name: "' + file.name + '", File size: ' + file.size + ', Message: ' + message);
			}
		},
		uploadStart: function(event, file) {
			$(this).pieEasyUploader('status').logMsg('Starting upload of: "' + file.name + '"');
			$(this).pieEasyUploader('status').prgMsg('Starting your upload...');
		},
		uploadProgress: function(event, file, bytesLoaded, bytesTotal) {
			var pct = Math.ceil((bytesLoaded / bytesTotal) * 100);
			$(this).pieEasyUploader('status').prgPct(pct);
			$(this).pieEasyUploader('status').prgMsg('Your upload is ' + pct + '% complete');
			$(this).pieEasyUploader('status').logMsg('Upload progress: ' + pct + '% (' + bytesLoaded + ' bytes)');
			if (pct == 100) {
				$(this).pieEasyUploader('status').logMsg('Processing image file...');
				$(this).pieEasyUploader('status').prgMsg('Creating thumbnails, please wait...');
			}
		},
		uploadError: function(event, file, errorCode, message) {
			$(this).pieEasyUploader('status').prgMsg('Upload Failed: See status log');

			switch (errorCode) {
				case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
					return $(this).pieEasyUploader('status').logMsg('Upload Error: HTTP Error (' + file.name + ') [' + message + ']');
				case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
					return $(this).pieEasyUploader('status').logMsg('Upload Error: No backend file (' + file.name + ') [' + message + ']');
				case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
					return $(this).pieEasyUploader('status').logMsg('Upload Error: Upload initialization failed (' + file.name + ') [' + message + ']');
				case SWFUpload.UPLOAD_ERROR.IO_ERROR:
					return $(this).pieEasyUploader('status').logMsg('Upload Error: IO Error (' + file.name + ') [' + message + ']');
				case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
					return $(this).pieEasyUploader('status').logMsg('Upload Error: Security Error (' + file.name + ') [' + message + ']');
				case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
					return $(this).pieEasyUploader('status').logMsg('Upload Error: Upload limit exceeded (' + file.name + ') [' + message + ']');
				case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
					return $(this).pieEasyUploader('status').logMsg('Upload Error: The file not found (' + file.name + ') [' + message + ']');
				case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
					return $(this).pieEasyUploader('status').logMsg('Upload Error: File validation failed (' + file.name + ') [' + message + ']');
				case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
					return $(this).pieEasyUploader('status').logMsg('Upload Error: Cancelled (' + file.name + ') [' + message + ']');
				case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
					return $(this).pieEasyUploader('status').logMsg('Upload Error: Stopped (' + file.name + ') [' + message + ']');
				default:
					return $(this).pieEasyUploader('status').logMsg('Upload Error: ' + errorCode + ', File name: "' + file.name + '", File size: ' + file.size + ', Message: ' + message);
			}
		},
		uploadSuccess: function(event, file, serverData) {
			if (isNaN(serverData)) {
				$(this).pieEasyUploader('status').logMsg('Upload of "' + file.name + '" failed: WordPress did not return an attachment ID');
			} else {
				$(this).pieEasyUploader('status').logMsg('Upload successful: "' + file.name + '" saved as attachment ID #' + serverData );
				$(this).pieEasyUploader('status').prgMsg('Loading image preview...');
				$(this).pieEasyUploader('attach').id(serverData);
			}
		},
		uploadComplete: function(event, file) {
			$(this).pieEasyUploader('status').logMsg('Upload of "' + file.name + '" completed.');
			$(this).pieEasyUploader('status').prgMsg('Your upload is complete!');

			if ($(this).swfupload('getStats').files_queued >= 1) {
				$(this).pieEasyUploader('status').prgMsg('Uploading next file in queue...');
				$(this).swfupload('startUpload');
				return;
			}

			$(this).pieEasyUploader('status').destroy();
			alert('You must save your changes to make this setting permanent.');
		}
	};

	var settings = {
		flash_url : pieEasyFlashUploaderL10n.flash_url,
		upload_url: pieEasyFlashUploaderL10n.upload_url,
		post_params: {
			'short' : 1,
			'post_id' : 0,
			'auth_cookie' : pieEasyFlashUploaderL10n.pp_auth_cookie,
			'logged_in_cookie': pieEasyFlashUploaderL10n.pp_logged_in_cookie,
			'_wpnonce' : pieEasyFlashUploaderL10n.pp_wpnonce
		},
		// file upload
		file_post_name: "async-upload",
		file_size_limit : pieEasyFlashUploaderL10n.file_size_limit,
		file_types : "*.png;*.jpg;*.gif",
		file_types_description : "Image Files",
		file_upload_limit : 10,
		file_queue_limit : 0,
		// button
		button_window_mode: SWFUpload.WINDOW_MODE.OPAQUE,
		button_image_url: pieEasyFlashUploaderL10n.button_image_url,
		button_placeholder : null,
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

	var methods = {
		init: function() {
			// init buttons
			$(this).pieEasyUploader('ibar').init();
			// set options
			var options = $.extend(
				{}, settings, {button_placeholder: $('input[type=button]', this)[0]}
			);
			// bind listeners and initialize uploader
			return this.bindAll(listeners).swfupload(options);
		},
		ibar: function(){
			var
				$this = this,
				_bar = $('fieldset.pie-easy-options-fu-img div.pie-easy-options-fu-ibar', $this),
				_diaZoom = $('fieldset.pie-easy-options-fu-img div.pie-easy-options-fu-zoom', $this),
				_btnZoom = _bar.children().eq(0),
				_btnEdit = _bar.children().eq(1),
				_btnRem = _bar.children().eq(2);
			return {
				init: function(){
					// init zoom dialogue
					_diaZoom.dialog({
						autoOpen: false,
						modal: true,
						height: _diaZoom.children('img').attr('height') + 60,
						width: _diaZoom.children('img').attr('width') + 30
					});
					// init zoom button
					_btnZoom.button({
						icons: {
							primary: "ui-icon-zoomin"
						}
					}).click(function(){
						_diaZoom.dialog('open');
					});
					_btnEdit.button({
						icons: {
							primary: "ui-icon-wrench"
						}
					}).click(function(){
						$.ajax({
							async: false,
							type: 'POST',
							url: pieEasyFlashUploaderL10n.ajax_url,
							data: {
								'action': 'pie_easy_options_uploader_image_edit',
								'attachment_id': $this.pieEasyUploader('attach').id()
							},
							success: function(r){
								_btnEdit.after('<div></div>');
								var _btnDia = _btnEdit.next();
								_btnDia.empty();
								_btnDia.append(r);
								_btnDia.dialog({
									modal: true,
									draggable: false,
									width: 600,
									title: 'Edit Image',
									zIndex: 3,
									buttons: {
										"Close": function() {
											$(this).dialog("close");
										}
									},
									beforeClose: function(){
										$this.pieEasyUploader('attach').update()
									}
								});
							}
						});
					});
					// init rem button
					_btnRem.button({
						icons: {
							primary: "ui-icon-trash"
						}
					}).click(function(){
						$this.pieEasyUploader('attach').id('');
						return false;
					});
					// display on load?
					var attachId = $this.pieEasyUploader('attach').id();
					if ( !isNaN(parseInt(attachId)) ) {
						this.show();
					}
				},
				show: function(){
					_bar.show();
				},
				hide: function(){
					_bar.fadeOut(750);
				},
				zoomHref: function (href) {
					_btnZoom.attr('href', href);
				}
			}
		},
		attach: function(){
			var
				$this = this,
				_input = $('div.pie-easy-options-fu-btn input[type=hidden]', this),
				_image = $('fieldset.pie-easy-options-fu-img p img', this);
			return {
				id: function (value) {
					if (typeof value == 'undefined') {
						return _input.val();
					} else {
						_input.val(value);
						this.update();
						return value;
					}
				},
				update: function() {
					if (this.id()) {
						$.ajax({
							async: false,
							type: 'POST',
							url: pieEasyFlashUploaderL10n.ajax_url,
							data: {
								'action': 'pie_easy_options_uploader_media_url',
								'attachment_id': this.id(),
								'attachment_size': 'full'
							},
							success: function(rs){
								var r = pieEasyAjax.splitResponse(rs);
								// TODO error handling
								_image.attr('src', r[1]);
								$this.pieEasyUploader('ibar').zoomHref(r[1]);
								$this.pieEasyUploader('ibar').show();
							}
						});
					} else {
						_image.attr('src', '');
						$this.pieEasyUploader('ibar').zoomHref('');
						$this.pieEasyUploader('ibar').hide();
					}
				}
			}
		},
		status: function(){
			var
				_stat = $('fieldset.pie-easy-options-fu-stat', this),
				_log = $('fieldset.pie-easy-options-fu-stat textarea', this),
				_prg = $('div', _stat),
				_prgTxt = $('p', _prg);
			return {
				init: function(){
					_prg.progressbar({value: 0});
					return this.show();
				},
				destroy: function(){
					this.hide();
					_prg.progressbar('destroy');
				},
				show: function(){
					this.prgMsg();
					this.logClr();
					_stat.fadeIn(500);
				},
				hide: function(){
					_stat.fadeOut(750);
				},
				logMsg: function(msg){
					_log.val(_log.val() + msg + "\n");
					_log.scrollTop(_log[0].scrollHeight - _log.height());
				},
				logClr: function(){
					_log.val('');
				},
				prgPct: function (pct){
					return _prg.progressbar('value', pct);
				},
				prgMsg: function (msg){
					return _prgTxt.text(msg);
				}
			}
		}
	}

	$.fn.pieEasyUploader = function (method)
	{
		if ( methods[method] ) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || ! method) {
			return methods.init.apply(this, arguments);
		} else {
			return $.error('Method ' +  method + ' does not exist on jQuery.pieEasyUploader');
		}
	}

})( jQuery );