<?php
/**
 * PIE API: option extensions, select box template file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

// grab value one time only
$selected_value = $this->component()->get();

?>
<select name="<?php $this->render_name() ?>" id="<?php $this->render_field_id() ?>" class="<?php $this->render_field_class() ?>">
	<option value="">--- Select One ---</option>
	<?php foreach ( $this->component()->field_options as $value => $text ):
		$selected = ( $value == $selected_value ) ? ' selected="selected"' : null; ?>
		<option value="<?php print esc_attr( $value ) ?>"<?php print $selected ?>><?php print esc_html( $text ) ?></option>
	<?php endforeach; ?>
</select>