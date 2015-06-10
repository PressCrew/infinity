<?php
/**
 * ICE API: widget extensions, posts list template file
 *
 * Variables:
 *		$posts_list	The ICE_Posts_List object that will render the list
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage widgets
 * @since 1.0
 */

/* @var $this ICE_Renderer */
?>
<div <?php $this->render_attrs() ?>>
	<div class="ui-widget-header">
		<?php $this->render_title() ?>
		<a href="<?php print admin_url( 'post-new.php' ) ?>?post_type=<?php print $post_type ?>" target="_parent" class="ice-do-create">
			<?php _e( 'Create New', 'infinity-engine' ) ?>
		</a>
	</div>
	<div class="ui-widget-content">
		<?php $posts_list->display() ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	widgetPostsListSortable('div#<?php $this->render_id() ?> div.ui-widget-content');
//]]>
</script>