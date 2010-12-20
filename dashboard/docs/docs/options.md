Enabling Infinity in your Themes
--------------------------------

Themes built on Infinity can use all the WordPress functions you’ve come to expect, and all you need to do to get started is the following;

**Step 1:** Upload the unpacked Infinity folder to your Theme directory.

**Step 2:** Upload the WordPress Theme you want to use to the theme directory (for instance TwentyTen)

**Step 3:** Create a new folder in your Theme root directory called config

**Step 4:** Create a new file called _Infinity.ini_ in that folder and put the following in the file;

> parent_theme = "infinity"

Done!
Here’s how the TwentyTen folder structure looks with Infinity enabled:

![Example](infinity://admin:image/docs/setup_theme.jpg "Infinity Enabled")

That’s all there is to it to enable Infinity in your Theme. So now you’ve got it set up, we’ll take a look at how to start using it to create new Theme Options and using The Scheme to create an infinite amount of child themes based on your Parent Infinity Theme (the one you just created)

Setting up Child Themes
-----------------------

The easiest and quickest way to customize your Parent theme is to create a [Child Theme](http://codex.wordpress.org/Child_Themes "Child Theme") that loads the templates from your Infinity Theme. This is very easy to do, and it pretty much a repeat of the initial setup;

**Step 1:** Create a new folder in your Theme directory and name it as you like. We’ll call it My Child Theme to keep things easy. 

**Step 2:** Create or Copy over the config folder to this Theme as well.

**Step 3:** Open up or create the _infinity.ini_ file and change the parent_theme line to the name of your parent theme. For example:

> parent_theme = "TwentyTen"

**Step 4:** Copy over Style.css from your Parent Theme and modify it 

This tells Infinity to load the templates from your Infinity Powered Parent Theme (in our case TwentyTen).
