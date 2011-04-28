## Installation

Themes built on Infinity can use all the WordPress functions you've come to expect.
Whether you are starting from scratch, or extending an existing theme, setup is very simple.

<ul class="infinity-docs-menu"></ul>

> For our examples we are using Twenty Ten, the default theme that ships with Wordpress

### Upload the Themes

1. Upload the unpacked Infinity directory into the `wp-content/themes/` directory.

		wp-content/themes/infinity

1. Upload the WordPress theme you want to use to the `wp-content/themes/` directory as well.

		wp-content/themes/twentyten

### Enabling Infinity in your Theme

1. Create a new directory in *your* theme's root directory called: `config`.

		wp-content/themes/twentyten/config/

1. Create a new file called `infinity.ini` in the config directory that you just created:

		wp-content/themes/twentyten/config/infinity.ini

1. Open the file for editing, and add this one simple directive:

		parent_theme = "infinity"

1. Save and close the ini file.

> Done! That's all there is to it to enable Infinity in your Theme.

### Does your theme look like this?

![Example](infinity://admin:image/docs/setup_theme.jpg "Infinity Enabled")

### Setting up Child Themes

Once you have successfully empowered your theme with Infinity, you are ready to
create new theme options and use the scheme to create an infinite number of child
themes based on your Infinity powered theme.

The easiest and quickest way to extend your Infinity powered theme is to create a
<a href="http://codex.wordpress.org/Child_Themes" target="_blank">Child Theme</a> that
loads the templates from your theme. This is very easy to do, and it pretty much
a repeat of the initial setup:

1. Create a new directory in the `wp-content/themes/` directory.

		wp-content/themes/my-child-theme

1. *Create* or *Copy* over the `config` directory to the new theme as well.

		wp-content/themes/my-child-theme/config

1. Open up or create the `infinity.ini` file:

		wp-content/themes/my-child-theme/config/infinity.ini

   Change the `parent_theme` line to the name of your parent theme:

		parent_theme = "twentyten"

	> This tells Infinity to load the templates from your Infinity powered parent theme
	(in our case Twenty Ten).

1. Create a new `style.css`, or copy over `style.css` from your parent theme and modify it
   as needed.

> You now have a "grandchild theme" that inherits from **two** themes, its parent (Twenty Ten),
and its grandparent (Infinity).

### Special Template Requirements

Any theme hierarchy which is **three or more** deep, including Infinity,
must use some custom template tags in order for the template inheritance to work properly.

> Full documentation for this can be found here:
[Templating](infinity://admin:doc/install_tpls)
