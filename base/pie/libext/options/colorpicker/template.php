<?php
/**
 * PIE API: option extensions, color picker template file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */
?>

<?php $this->render_input( 'text' ) ?>

<div id="<?php $this->render_id('launcher') ?>" class="pie-easy-exts-options-colorpicker-launcher">
	<div style="background-color: <?php $this->render_field_value() ?>;"></div>
</div>

<script type="text/javascript">
	pieEasyColorPicker.init(
		'input[name=<?php $this->render_name() ?>]',
		'div#<?php $this->render_id('launcher') ?>'
	);
</script>