/**
 * inject an overlay div into a structure
 */
(function($){
	$.fn.pieEasyExtsAddOverlay =
		function(el_id, el_class) {
			return this.each(function() {
				// wrap inner contents with overlay
				var overlay =
						$('<div></div>').attr('id',el_id).attr('class',el_class),
					target =
						$(this).prepend(overlay);

				// set height to parent inner height if necessary
				if ( !overlay.innerHeight() ) {
					overlay.css('height', target.innerHeight() + 'px');
				}
			});
	};
})( jQuery );