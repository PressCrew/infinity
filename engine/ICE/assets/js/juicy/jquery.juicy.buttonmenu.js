/*
 * jUIcy Button Menu
 *
 * Depends:
 *	jquery.ui.core.js
 *	jquery.ui.widget.js
 *	jquery.ui.menu.js
 */
(function( $ ) {

$.widget( "juicy.buttonmenu", $.ui.menu, {

	options: {
		autoOpen: false,
		button: null
	},

	_create: function()
	{
		// call parent constructor
		$.ui.menu.prototype._create.call( this );

		var o = this.options;

		this.element
			.addClass( 'juicy-buttonmenu' )

		// handle auto open option
		if ( o.autoOpen === false ) {
			this.element.hide();
		}

		// handle button
		if ( o.button ) {
			this._button( o.button );
			o.button = null;
		}
	},

	_setOption: function( key, value )
	{
		$.Widget.prototype._setOption.apply( this, arguments );

		switch( key ) {
			case 'autoOpen':
				break;
			case 'button':
				this._button( value );
				break;
		}
	},

	destroy: function()
	{
		this.element
			.removeClass( 'juicy-buttonmenu' );

		$.Widget.prototype.destroy.call( this );
	},

	_button: function( button )
	{
		var self = this;

		button.click( function( e ) {
			self.show();
			e.preventDefault();
		});
	},

	show: function()
	{
		var self = this;

		// show me
		this.element.show( 'fast', function() {
			// hide me on doc click
			$(document).one( 'click', function() {
				self.element.hide();
			});
			// trigger show event
			self._trigger( 'show', null, {item: self.element} );
		});
	}

});

}( jQuery ) );