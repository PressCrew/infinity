## Configuration: Required Directives

Infinity has directives that MUST be set for Infinity to work properly.

> Infinity sets these in its own infinity.ini, so you don't always have to set them
in your child theme, but it is highly recommended that you do anyways.

### Directives

Currently there are only two, but more may be added in the future.

#### parent\_theme

The parent theme must be either "infinity" or an ancestor of infinity.

	parent_theme = "my-child-theme"

> If you do not set this, it is assumed that the parent theme is **"infinity"**

#### image\_root

The root image directory, relative to your theme root.

If your image directory is:

	wp-content/themes/my-theme/content/img

Then your image root setting would be:

	image_root = "content/img"

> If you do not set this, it is assumed that the image root is **"assets/images"**