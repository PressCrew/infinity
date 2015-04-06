<?php

ICE_Loader::load( 'utils/webfont' );

?>
<div id="typography-ff"></div>

<script type="text/javascript">
//<![CDATA[
	jQuery(document).ready(function($){
		var options = {};
		// add application options
		options.jsonUrl = '<?php print ICE_Webfont::instance(0)->get_property( 'url' ) ?>';
		options.slantText = '<?php _e( 'Slant', 'infinity' ) ?>';
		options.serviceText = '<?php _e( 'Service', 'infinity' ) ?>';
		options.variantText = '<?php _e( 'Thickness', 'infinity' ) ?>';
		options.subsetText = '<?php _e( 'Script', 'infinity' ) ?>';
		options.match =
			function(e,fonts,filters){
				console.log(fonts.length,filters);
//				$.each(fonts, function(k,v) {
//					console.log(v);
//				});
			};
		// add font picker
		$('div#typography-ff').fontfilter(options);
	});
//]]>
</script>