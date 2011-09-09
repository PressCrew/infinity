/**
 * initialize a debugger tree
 */
function widgetsDebuggerInit(selector)
{
	jQuery(selector)
		.jstree({
			'plugins': ['html_data','themeroller'],
			'core': {'animation': 0}
		});
}