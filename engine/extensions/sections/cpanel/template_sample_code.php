<?php
/**
 * Infinity Theme: Dashboard options screen, option sample code template
 *
 * DO NOT call template tags to render elements in this template!
 *
 * @package Infinity
 * @subpackage dashboard-templates
 */

// get option name
$option_name = $renderer->component()->get_property( 'name' );

// render the sample code (escaped PHP markup) ?>
<strong>Test if this has been set</strong>
<code class="ui-widget-content ui-corner-bottom ui-corner-tr">&lt;?php if ( infinity_option_get( '<?php echo $option_name ?>' ) ): ?&gt;
    <?php echo $option_name ?> has a value
&lt;?php endif; ?&gt;</code>

<strong>Echo an option value (insert this in your custom templates)</strong>
<code class="ui-widget-content ui-corner-bottom ui-corner-tr">&lt;?php echo infinity_option_get( '<?php echo $option_name ?>' ); ?&gt;</code>

<strong>Echo option as image URL (show your uploaded image in a template)</strong>
<code class="ui-widget-content ui-corner-bottom ui-corner-tr">&lt;img src="&lt;?php echo infinity_option_image_url( '<?php echo $option_name ?>', full ); ?&gt;"&gt;</code>
