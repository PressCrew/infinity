<?php
/**
 * PIE API: options extensions, textarea template file
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
<textarea name="<?php $this->render_name() ?>" id="<?php $this->render_field_id() ?>" class="<?php $this->render_field_class() ?>" rows="5" cols="50"><?php $this->render_field_value() ?></textarea>