## Cascading Images: Overview

Maintaining a large portfolio of themes, particularly in multi-site environments
can quickly become cumbersome and repetitive. Infinity's cascading architecture
makes managing template inheritance easy, but with images tightly bound to their
theme's templates with static image paths you may be forced to duplicate templates
for minor path differences.

In some cases you may want to have several "master" themes, each of which has
several child themes which only differ in their styles and content. You need the
flexibility to override some or all of their parent theme's images without having
to duplicate the templates.

This is where cascading images comes in. Another powerful Infinity innovation which allows
extreme control over your theme hierarchy.

### The Basics

Your parent theme has this image root:

	image_root = "assets/images"

Your child theme has this image root:

	image_root = "content/img"

Your grandchild theme has this image root:

	image_root = "_inc/images"

If your parent theme contains an image under it's image root at:

	wp-content/themes/parent-theme/assets/images/banners/blue.jpg

you would have code in your parent theme's template similar to this:

	<img src="<?php infinity_image_url('banners/blue.jpg') ?>">

### The power of cascading

Now, you wish to override this in your **child** theme and your **grandparent** theme is the
one which is activated. Normally you would have to duplicate the template only to tweak the
image path. Not anymore! Upload a custom blue.jpg here:

	wp-content/themes/child-theme/content/img/banners/blue.jpg

Infinity will traverse the scheme looking for blue.jpg in this manner:

	wp-content/themes/grandchild-theme/_inc/images/banners/blue.jpg  < missing
	wp-content/themes/child-theme/content/img/banners/blue.jpg       < found and displayed
	wp-content/themes/parent-theme/assets/images/banners/blue.jpg    < ignored

> In case you missed it, the only thing that was done was to override the image. The
template is still being pulled from the parent theme.

### Performance

It is very possible that image cascading could lead to a significant jump in the
work your server must do to display images, all depending on the specifics of your setup.

Image cascading was originally created to facilitate simpler image management on the dashboard
side of things, but we did not see a reason to keep this powerful feature hidden.

Be smart, use at your own risk, YMMV!

### Helper Functions

Be sure to complete documentation on the [image cascading functions](infinity://admin:doc/images_funcs).