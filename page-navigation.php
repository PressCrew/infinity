<?php
/**
 * Infinity Theme: page navigation template
 *
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage templates
 * @since 1.0
 */
?>
<div class="navigation">
	<?php 
		global $wp_query;
		// get total number of pages
		$total = $wp_query->max_num_pages;
		// only bother with the rest if we have more than 1 page!
		if ( $total > 1 ) {
			// get the current page
			if ( !$current_page = get_query_var( 'paged' ) ) {
				$current_page = 1;
			}
			// structure of format depends on whether we're using pretty permalinks
			$permalink_structure = get_option( 'permalink_structure' );
			$format = empty($permalink_structure) ? '&page=%#%' : 'page/%#%/';
			print paginate_links( array(
				'base' => get_pagenum_link(1) . '%_%',
				'format' => $format,
				'current' => $current_page,
				'total' => $total,
				'mid_size' => 4,
				'type' => 'list'
			));
		}
	?>
</div>
