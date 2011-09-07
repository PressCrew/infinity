<?php
/**
 * Infinity Theme: Dashboard options screen, option sample code template
 *
 * DO NOT call template tags to render elements in this template!
 *
 * This template is called from option the render. All rendering methods
 * are available from the $renderer variable (see below).
 *
 * Variables:
 *		$option		The current Pie_Easy_Options_Option object being rendered
 *		$renderer	The Pie_Easy_Options_Renderer object that is rendering the option
 *
 * @package infinity
 * @subpackage dashboard-templates
 */
?>
<strong>Test if option is set</strong>
<code>&lt;?php if ( infinity_option_get( '<?php print $option->name ?>' ) ): ?&gt;
    <?php print $option->name ?> has a value
&lt;?php endif; ?&gt;</code>

<strong>Echo an option value</strong>
<code>&lt;?php echo infinity_option_get( '<?php print $option->name ?>' ); ?&gt;</code>

<?php if ( $option instanceof Pie_Easy_Exts_Options_Upload ): ?>
<strong>Echo option as image URL</strong>
<code>&lt;img src="&lt;?php echo infinity_option_image_url( '<?php print $option->name ?>' ); ?&gt;"&gt;</code>
<?php endif ?>
