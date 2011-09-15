<?php
/**
 * PIE API: widget extensions, posts list template file
 *
 * Variables:
 *		$posts_list	The Pie_Easy_Posts_List object that will render the list
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage widgets
 * @since 1.0
 */
?>
<div class="pie-easy-exts-widget-posts-list ui-widget">
	<div class="ui-widget-header">
		<?php $this->render_title() ?>
	</div>
	<div class="ui-widget-content">
		<?php $posts_list->display() ?>
	</div>
</div>

<script type="text/javascript">
	widgetPostsListSortable();
</script>