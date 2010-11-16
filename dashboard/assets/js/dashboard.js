jQuery(document).ready(function(){

	/**
	 * Option section click event
	 */
	jQuery('.rm_section h3').click(function() {
		// is the element currently displayed?
		var displayed = (jQuery(this).parent().next('.rm_options').css('display') == 'none');
		// toggle active class
		jQuery(this).toggleClass('active', displayed);
		jQuery(this).children('img').toggleClass('active', displayed);
		// toggle slide in/out
		jQuery(this).parent().next('.rm_options').slideToggle('slow');	
	});
	
});