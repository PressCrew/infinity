## Cascading Images: Helper Functions

To take advantage of cascading images you must use these special functions
to locate the correct image paths and urls. These functions *return* not *echo* values.

<ul class="infinity-docs-menu"></ul>

### Image Functions

**Important:** The $path argument is the **RELATIVE** path to the image\_root setting
of the highest level theme that calls the image in its template(s). That means
**no leading slash!**

#### Getting Image Path

Returns absolute filesystem path to image file.

	(string) infinity_image_path( string $path )

> _$path_ : Relative path to theme's image\_root setting.

#### Getting Image URL

Returns absolute URL to image file.

	(string) infinity_image_url( string $path )

> _$path_ : Relative path to theme's image\_root setting.