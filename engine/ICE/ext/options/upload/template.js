(function($)
{
	// all instances share dialog vars to there is
	// only ever one instance of each at a time
	var muDialog, zoomDialog;

	// the plugin!
	$.fn.icextOptionUploader = function (method)
	{
		var $base = this,
			settings = {
				ibarSelector: '',
				imageSelector: '',
				inputSelector: '',
				muOptions: {
					autoOpen: false,
					modal: true,
					height: 600,
					width: 700,
					minHeight: 600,
					minWidth: 700
				},
				zoomOptions: {
					autoOpen: false,
					modal: true,
					height: 600,
					width: 800
				}
			};

		var methods = {

			init: function( options )
			{
				// merge options
				$.extend( true, settings, options );

				// kill existing mu dialog
				if ( muDialog ) {
					muDialog.dialog('destroy').remove();
				}

				// kill existing zoom dialog
				if ( zoomDialog ) {
					zoomDialog.dialog('destroy').remove();
				}

				// init ibar
				methods.ibar().init();
			},

			ibar: function()
			{
				var _bar = $( settings.ibarSelector, $base ),
					_btnUpload = _bar.children().eq(0),
					_btnLibrary = _bar.children().eq(1),
					_btnZoom = _bar.children().eq(2),
					_btnRem = _bar.children().eq(3);

				return {
					init: function(){
						// init upload button
						_btnUpload.button({
							icons: {
								primary: "ui-icon-circle-arrow-n"
							}
						}).click(function(e){
							methods.muOpen( 'type' );
							e.preventDefault();
						}).show();
						// init library button
						_btnLibrary.button({
							icons: {
								primary: "ui-icon-folder-open"
							}
						}).click(function(e){
							methods.muOpen( 'library' );
							e.preventDefault();
						}).show();
						// init zoom button
						_btnZoom.button({
							icons: {
								primary: "ui-icon-zoomin"
							}
						}).click(function(e){
							methods.zoomOpen();
							e.preventDefault();
						}).show();
						// init rem button
						_btnRem.button({
							icons: {
								primary: "ui-icon-trash"
							}
						}).click(function(e){
							methods.attach().id('');
							e.preventDefault();
						});
						// display on load?
						var attachId = methods.attach().id();
						if ( isNaN( parseInt( attachId ) ) ) {
							_btnRem.hide();
						}
					},
					showTrash: function(){
						_btnRem.show();
					},
					hideTrash: function(){
						_btnRem.fadeOut(750);
					}
				}
			},

			attach: function()
			{
				var _input = $( settings.inputSelector, $base ),
					_image = $( settings.imageSelector, $base );
				
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
						if ( this.id() ) {
							_image.fadeTo( 1000, 0.3 );
							$.ajax({
								type: 'POST',
								url: iceEasyGlobalL10n.ajax_url,
								data: {
									'action': 'ice_options_uploader_media_url',
									'attachment_id': this.id(),
									'attachment_size': 'full'
								},
								success: function(rs){
									var r = iceEasyAjax.splitResponse(rs);
									// TODO error handling
									_image
										.attr( 'src', r[1] )
										.fadeTo( 750, 1, function(){
											methods.ibar().showTrash();
											methods.notice();
										});
								}
							});
						} else {
							_image
								.fadeOut( 500, function(){
									methods.ibar().hideTrash();
									methods.notice();
								}).attr( 'src', '' );
						}
					}
				}
			},
			
			notice: function()
			{
				alert( 'You must save your changes to make this setting permanent.' );
			},

			muOpen: function( tab )
			{
				muDialog = $('<div class="icextOptionUploadWin"><iframe></iframe></div>').appendTo('body');
				muDialog.dialog( settings.muOptions );
				muDialog.dialog({
					close: function(){
						var iframe = $( 'iframe', this ),
							iframeWin = iframe.prop( 'contentWindow' ) || iframe.prop( 'contentDocument' );
						methods.attach().id( iframeWin.icextOptionUploadAttachmentId );
						iframe.attr( 'src', '' );
						muDialog.dialog('destroy').remove();
					}
				});
				muDialog.dialog('open');
				$( 'iframe', muDialog )
					.attr( 'src', 'media-upload.php?post_id=0&icext_option_upload=1&tab=' + tab );
			},

			zoomOpen: function()
			{
				var _image = $( settings.imageSelector, $base );

				zoomDialog = $('<div></div>').appendTo('body');
				zoomDialog.dialog( settings.zoomOptions );
				zoomDialog.dialog({
					close: function(){
						zoomDialog.dialog('destroy').remove();
					}
				});

				_image.clone().appendTo( zoomDialog );
				
				zoomDialog.dialog('open');
			}
		}
		
		if ( methods[method] ) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || ! method) {
			return methods.init.apply(this, arguments);
		} else {
			return $.error('Method ' +  method + ' does not exist on jQuery.icextOptionUploader');
		}
	}

}(jQuery));
