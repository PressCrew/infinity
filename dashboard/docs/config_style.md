## Configuration: Automatic Style Enqueuing

Infinity allows you to enqueue your theme's style sheets entirely using configuration
directives without writing any PHP code. The WordPress function `wp_enqueue_styles()`
is called to enqueue the styles where the configuration calls for it.

### Directives

#### [style]

The style section is where you define your styles. A style is a handle (name) of the style sheet
and the style sheet's relative path *from your theme's root directory.* Full length URLs to
style sheets are also supported if the begin with `http`.

The format is:

	handle = "path/to/file.css"

Here is an example:

	[style]
	colors = "css/colors.css"
	sliders = "css/vendors/slider.css"
	page-sidebar = "css/pagesidebars.css"
	gfonts = "http://fonts.googleapis.com/css?family=Droid+Sans:regular"

The above code would result in the following three syle sheets being enqueued.

	http://mysite.com/wp-content/themes/my-theme/css/colors.css
	http://mysite.com/wp-content/themes/my-theme/css/vendors/slider.css
	http://mysite.com/wp-content/themes/my-theme/css/pagesidebars.css
	http://fonts.googleapis.com/css?family=Droid+Sans:regular

> *Important:* All styles will ALWAYS be enqueued, UNLESS an action or condition is set (see below)

#### [style\_depends]

The style dependancies section lets you define other style sheets that one of your
style sheets requires to be loaded before it.

The format is:

	handle = "comma,separated,style,handles"

You should use the *exact* same handle names that you defined in the `[styles]` section.
If your list of required handles contains a handle which is not defined by you, it will
still be passed to `wp_enqueue_styles()` as a dependancy assuming that is has already
been registered in WordPress.

Here is an example:

	[style_depends]
	sliders = "colors,gfonts"

In the above example, the `colors` and `gfonts` style sheets would be enqueued before
the `sliders` style sheet.

#### [style\_actions]

The style actions section lets you fine tune when styles are enqueued by assigning one
or more style handles to an action.

The format is:

	action = "comma,separated,style,handles"

You should use the *exact* same handle names that you defined in the `[styles]` section.

Here is an example:

	[style_actions]
	wp = "colors,sliders"

In the above example, the `colors` and `sliders` style sheets would only be enqueued if and when
the `wp` action is called by WordPress. It is up to you to use actions that are called in time
for `wp_enqueue_styles()` to work properly.

#### [style\_conditions]

The style conditions section lets fine tune when styles are enqueued by assigning one
or more style handles to a condition (callback function) which evaluates to true.

The basic format is:

	callback_name = "comma,separated,style,handles"

The format to pass any number of arguments to the callback is:

	callback_name:arg1,arg2,arg3 = "comma,separated,style,handles"

You should use the *exact* same handle names that you defined in the `[styles]` section.

Here is an example:

	[style_conditions]
	is_front_page = "sliders"
	is_page = "page-sidebar"

In the above example, the `sliders` style sheet would only be enqueued if the `is_front_page()`
function returns true, and the page-sidebar style sheet would only be enqueued if the `is_page()`
function returns true.

All style conditions are evaluated on the `wp_print_styles` and `admin_print_styles` actions at
priority 10. This is hard coded in Infinity and cannot be changed via any configuration settings.
