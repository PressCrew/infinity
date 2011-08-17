/**
 * Enable nested sortable for posts list widget
 */
function widgetPostsListSortable()
{
	jQuery('div.pie-easy-exts-widget-posts-list ul')
		.nestedSortable({
			handle: 'div',
			helper:	'clone',
			listType: 'ul',
			items: 'li',
			maxLevels: 0,
			opacity: .6,
			revert: 250,
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div'
	});
}