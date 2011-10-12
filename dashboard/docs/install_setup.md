## Installation

Themes built on Infinity can use all the WordPress functions you've come to expect.
Whether you are starting from scratch, or extending an existing theme, setup is very simple.

<ul class="infinity-docs-menu"></ul>

### Installing Infinity

Unpack the Infinity package. Infinity ships with a few themes, but just worry about
the `infinity` directory for now!

#### Uploading the Base Theme

1. Upload the unpacked `infinity` directory into the `wp-content/themes/` directory.

	wp-content/themes/infinity

1. If you are running a multi-site setup, be sure to enable the Infinity theme from the
   network admin dashboard.

#### Using Base As Your Theme

If you just want to play around with Infinity, or use it as your theme, all that you
need to do is activate it for a site, and you are done!

### Creating a Child Theme

1. The Infinity package ships with a bare bones theme called `infinity-skeleton`.
   This theme contains all of the important files which you need to begin developing
   a child theme of Infinity, or to use as a guide for modifying an existing theme
   to extend Infinity.

1. Normally you will want to **copy** and **rename** the `infinity-skeleton` directory to
   something else. For the purposes of our examples, we are going to rename the `infinity-skeleton`
   to `my-child-theme`. If you choose to name your child theme something other than
   `my-child-theme`, make sure you use the new name in place of `my-child-theme`
   in the examples below.

#### Configuring the Child Theme

If you are not familiar with how child themes work in WordPress, please take some
time to learn about this from the WordPress codex before continuing:
<a href="http://codex.wordpress.org/Child_Themes" target="_blank">Child Theme</a>

1. Look in the `my-child-theme` theme's root directory called: `config`.

		my-child-theme/config/

1. Open the file called `infinity.ini` in the config directory:

		my-child-theme/config/infinity.ini

1. You will see there is only one directive:

		parent_theme = "infinity"

   This is how you tell Infinity which theme is the parent of the `my-child-theme` theme,
   in this case `infinity`.

1. Now open the `style.css` file in the `my-child-theme` theme and modify the theme name
   and description to whatever you like. We are going to call ours "My Child Theme"

1. Also in `style.css` you will notice that the `Template` is set as follows

		Template: infinity

   Take a moment to absorb this very important detail:

   **Every child, grandchild, great-grandchild and so on MUST have the
   `Template` set to `infinity`.**

#### Installing the Child Theme

1. Upload the `my-child-theme` directory to the `wp-content/themes/` directory.

		wp-content/themes/my-child-theme

1. If you are running a multi-site setup, be sure to enable the new theme from the
   network admin dashboard.

1. Activate the "My Child Theme" and you are done!

### Grandchild Themes

Once you have Infinity configured with a single child theme, you are ready to create an
infinite number of child themes, if you so desire.

#### Special Requirements of Grandchild Themes

1. Any theme hierarchy which is **three or more** deep (including `infinity`)
   must use some custom template tags in order for the template inheritance to work properly.

	> Full documentation for this can be found here:
	  [Templating](infinity://admin:doc/install_tpls)

1. Setting up a grandchild theme is identical to setting up a child theme except for one thing,
   the `parent_theme` directive should be set to the theme you want to inherit from.

   **DO NOT set the Template setting in `style.css` to the theme you want to inherit from.**

   The `Template` setting in style.css should **ALWAYS** be `infinity`.

#### Grandchild Theme Example

1. Create a **new copy** of the `infinity-skeleton` directory. For the purposes of our example
   we are going to name this new theme `my-grandchild-theme`.

1. Open up the `infinity.ini` file:

		my-grandchild-theme/config/infinity.ini

   Change the `parent_theme` line to the name of the theme you want to inherit from:

		parent_theme = "my-child-theme"

	> This tells Infinity to load the templates from your Infinity child theme
	  which we created and configured early in this document.

1. Now open the `style.css` file in the `my-grandchild-theme` theme and modify the theme name
   and description to whatever you like. We are going to call ours "My Grandchild Theme"

1. DO NOT edit the `Template` setting in `style.css`. It should **ALWAYS** be `infinity`.

> You now have a grandchild theme that inherits from **two** themes, its parent `my-child-theme`,
and its grandparent `infinity`.

#### Installing the Grandchild Theme

1. Upload the `my-grandchild-theme` directory to the `wp-content/themes/` directory.

		wp-content/themes/my-grandchild-theme

1. If you are running a multi-site setup, be sure to enable the new theme from the
   network admin dashboard.

1. Activate the "My Grandchild Theme" and you are done!

### Going Way Deep

The only limits of Infinity's powerful scheme architecture is the limits of your imagination
(and your server's CPU power).

#### Deep Theme Ancestories

Infinity supports Infinite theme ancestories (hence the name). Simply follow the instructions
for creating a grandchild theme, taking care to set the `parent_theme` directive to the
theme which you wish to inherit from

#### Family Tree Ancestories

Yes, you can use Infinity to create "trees" of themes. In fact, this is its most powerful
feature for use in multi-site setups.
