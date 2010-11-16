<?php
/**
 * PIE Framework API options section class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright &copy; 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage options
 * @since 1.0
 */

/**
 * Make an option section easy
 */
abstract class Pie_Easy_Options_Section
{
	/**
	 * The name of this section
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The title of this section
	 *
	 * @var string
	 */
	private $title;

	/**
	 * The CSS class for this section
	 *
	 * @var string
	 */
	private $class;

	/**
	 * The CSS class for the title of this section
	 *
	 * @var string
	 */
	private $class_title;

	/**
	 * The CSS class for the content of this section
	 *
	 * @var string
	 */
	private $class_content;

	/**
	 * The current content to be wrapped
	 *
	 * @var string
	 */
	private $section_content;

	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param string $title
	 */
	public function __construct( $name, $title  )
	{
		$this->name = $name;
		$this->title = $title;
	}

	/**
	 * Allow read access to all properties (for now)
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		return $this->$name;
	}

	/**
	 * Set the CSS class
	 *
	 * @param string $class
	 */
	public function set_class( $class )
	{
		$this->class = $class;
	}

	/**
	 * Set the title CSS class
	 *
	 * @param string $class
	 */
	public function set_class_title( $class )
	{
		$this->class_title = $class;
	}

	/**
	 * Set the content CSS class
	 *
	 * @param string $class
	 */
	public function set_class_content( $class )
	{
		$this->class_content = $class;
	}

	/**
	 * Render a section of options
	 *
	 * @param string $content The content to wrap with the section
	 * @param boolean $output Set to false to return results instead of printing
	 * @return string|void
	 */
	final public function render( $content, $output = true )
	{
		// set the content locally
		$this->section_content = $content;

		// handle output buffering if applicable
		if ( $output === false ) {
			ob_start();
		}

		// call the html renderer
		$this->render_section( $content );

		// return if output is disabled
		if ( $output === false ) {
			return ob_get_clean();
		}
	}

	/**
	 * Wrap the section content with the section layout
	 */
	protected function render_section()
	{ ?>
		<div class="<?php print esc_attr( $this->class ) ?> <?php print esc_attr( $this->class ) ?>-<?php print esc_attr( $this->name ) ?>">
			<div class="<?php print esc_attr( $this->class_title ) ?>">
				<h3><?php print $this->title ?></h3>
				<input name="save_section_<?php print esc_attr( $this->name ) ?>" type="submit" value="<?php _e( 'Save Changes' ) ?>" />
			</div>
			<div class="<?php print esc_attr( $this->class_content ) ?>">
				<?php print $this->section_content ?>
			</div>
		</div><?php
	}
}

?>
