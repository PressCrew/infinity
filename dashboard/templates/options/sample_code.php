<?php
/**
 * Infinity Theme: Dashboard options screen, option sample code template
 *
 * DO NOT call template tags to render elements in this template!
 *
 * @package Infinity
 * @subpackage dashboard-templates
 */
?>
<strong>Test if option is set</strong>
<code class="ui-widget-content ui-corner-bottom ui-corner-tr">&lt;?php if ( infinity_option_get( '<?php print $this->component()->name ?>' ) ): ?&gt;
    <?php print $this->component()->name ?> has a value
&lt;?php endif; ?&gt;</code>

<strong>Echo an option value</strong>
<code class="ui-widget-content ui-corner-bottom ui-corner-tr">&lt;?php echo infinity_option_get( '<?php print $this->component()->name ?>' ); ?&gt;</code>

<?php if ( $option instanceof Pie_Easy_Exts_Options_Upload ): ?>
<strong>Echo option as image URL</strong>
<code class="ui-widget-content ui-corner-bottom ui-corner-tr">&lt;img src="&lt;?php echo infinity_option_image_url( '<?php print $this->component()->name ?>' ); ?&gt;"&gt;</code>
<?php endif ?>
