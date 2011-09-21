<?php
/**
 * PIE API: option extensions, categories template file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */
?>
<div class="<?php $this->render_class( 'field' ) ?>" id="<?php $this->render_field_id() ?>">
	<?php $this->component()->render_field() ?>
</div>