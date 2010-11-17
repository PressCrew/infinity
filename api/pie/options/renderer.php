<?php
/**
 * PIE Framework API options renderer class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright &copy; 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage options
 * @since 1.0
 */

require_once( 'walkers.php' );

/**
 * Make rendering options easy
 */
abstract class Pie_Easy_Options_Renderer
{
	/**
	 * The current option being rendered
	 * 
	 * @var Pie_Easy_Options_Option
	 */
	private $option;

	/**
	 * Setup necessary files
	 */
	static public function setup( $pie_url )
	{
		// global css
		wp_enqueue_style( 'pie-easy', $pie_url . '/assets/css/pie.css' );
		
		// color picker plugin
		wp_enqueue_style( 'pie-easy-colorpicker', $pie_url . '/assets/css/colorpicker.css' );
		wp_enqueue_script( 'pie-easy-colorpicker', $pie_url . '/assets/js/colorpicker.js', array( 'jquery' ) );

		// pie javascript
		wp_enqueue_script( 'pie-easy', $pie_url . '/assets/js/pie.js', array( 'jquery' ) );
	}

	/**
	 * Render an option
	 *
	 * @param Pie_Easy_Options_Option $option
	 * @param boolean $output Set to false to return results instead of printing
	 * @return string|void
	 */
	final public function render( Pie_Easy_Options_Option $option, $output = true )
	{
		// set as currently rendered option
		$this->option = $option;

		// handle output buffering if applicable
		if ( $output === false ) {
			ob_start();
		}

		// render the option
		$this->render_option();

		// return results if output buffering is on
		if ( $output === false ) {
			return ob_get_clean();
		}
	}

	/**
	 * Render option label and input wrapped in a container
	 */
	protected function render_option()
	{ ?>
		<div class="<?php print $this->option->class ?>">
			<?php $this->render_label() ?>
			<?php $this->render_field() ?>
			<?php $this->render_description() ?>
		</div><?php
	}

	/**
	 * Render form input label
	 */
	protected function render_label()
	{ ?>
		<label for="<?php print esc_attr( $this->option->name ) ?>" title="<?php print esc_attr( $this->option->title ) ?>"><?php print esc_attr( $this->option->title ) ?></label><?php
	}

	/**
	 * Render form input description
	 */
	protected function render_description()
	{ ?>
		<p><?php print esc_attr( $this->option->description ) ?></p><?php
	}

	/**
	 * Render form input tag
	 *
	 * @param string $type A valid form input type
	 */
	protected function render_input( $type )
	{ ?>
		<input type="<?php print $type ?>" name="<?php print esc_attr( $this->option->name ) ?>" id="<?php print esc_attr(  $this->option->field_id ) ?>" class="<?php print esc_attr( $this->option->field_class ) ?>" value="<?php print esc_attr( $this->option->get() ) ?>" /> <?php
	}

	/**
	 * Render a group of inputs with the same name
	 *
	 * @param string $type
	 */
	protected function render_input_group( $type )
	{
		if ( is_array( $this->option->field_options ) ) {
			foreach ( $this->option->field_options as $value => $display ) {
				$checked = ( $this->option->get() == $value ) ? ' checked=checked' : null; ?>
				<input type="<?php print $type ?>" name="<?php print esc_attr( $this->option->name ) ?>" id="<?php print esc_attr( $this->option->field_id ) ?>" value="<?php print esc_attr( $value ) ?>"<?php print $checked ?> /><?php
				print $display;
			}
		} else {
			throw new Exception( sprintf( 'The "%s" option has no array of field options to render.', $this->option->name ) );
		}
	}

	/**
	 * Render the field based on type
	 */
	final protected function render_field()
	{
		// call the applicable rendering method
		switch ( $this->option->field_type ) {
			case 'category':
				$this->render_category();
				break;
			case 'categories':
				$this->render_categories();
				break;
			case 'checkbox':
				$this->render_checkbox();
				break;
			case 'colorpicker':
				$this->render_colorpicker();
				break;
			case 'page':
				$this->render_page();
				break;
			case 'radio':
				$this->render_radio();
				break;
			case 'text':
				$this->render_text();
				break;
			case 'textarea':
				$this->render_textarea();
				break;
			default:
				throw new UnexpectedValueException( sprintf(
					'The option type "%s" does not have a renderer yet.',
					$this->option->field_type ) );
		}
	}

	/**
	 * Render a category select tag
	 */
	protected function render_category()
	{
		$args = array(
			'show_option_all'    => null,
			'show_option_none'   => null,
			'orderby'            => 'ID',
			'order'              => 'ASC',
			'show_last_update'   => false,
			'show_count'         => false,
			'hide_empty'         => false,
			'child_of'           => false,
			'exclude'            => null,
			'echo'               => true,
			'selected'           => $this->option->get(),
			'hierarchical'       => false,
			'name'               => $this->option->name,
			'id'                 => $this->option->field_id,
			'class'              => $this->option->field_class,
			'depth'              => false,
			'tab_index'          => false,
			'taxonomy'           => 'category',
			'hide_if_empty'      => false );

		// use WordPress function
		wp_dropdown_categories( $args );
	}

	/**
	 * Render category checkboxes
	 */
	protected function render_categories()
	{
		$args = array(
			'show_option_all'		=> false,
			'orderby'				=> 'name',
			'order'					=> 'ASC',
			'show_last_updated'		=> false,
			'style'					=> false,
			'show_count'			=> false,
			'hide_empty'			=> false,
			'use_desc_for_title'	=> false,
			'child_of'				=> false,
			'feed'					=> false,
			'feed_type'				=> false,
			'feed_image'			=> false,
			'exclude'				=> false,
			'exclude_tree'			=> false,
			'include'				=> false,
			'hierarchical'			=> false,
			'title_li'				=> __( 'Categories' ),
			'number'				=> null,
			'echo'					=> true,
			'depth'					=> false,
			'current_category'		=> false,
			'pad_counts'			=> false,
			'taxonomy'				=> 'category',
			'walker'				=> new Pie_Easy_Options_Walker_Category(),
			'pie_easy_option'		=> $this->option );

		// call the WordPress function
		wp_list_categories( $args );
	}

	/**
	 * Render one or more checkboxes
	 */
	protected function render_checkbox()
	{
		$this->render_input_group( 'checkbox' );
	}

	/**
	 * Render a color picker input
	 */
	protected function render_colorpicker()
	{
		// render the input text field
		$this->render_input( 'text' );
		
		// now the color picker box ?>
		<div id="pie-easy-options-cp-wrapper-<?php print esc_attr( $this->option->name ) ?>" class="pie-easy-options-cp-box">
			<div style="background-color: <?php print esc_attr( $this->option->get() ) ?>;"></div>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				pieEasyColorPicker.init(
					'input[name=<?php print $this->option->name ?>]',
					'div#pie-easy-options-cp-wrapper-<?php print esc_attr( $this->option->name ) ?>'
				);
			});
		</script><?php
	}

	/**
	 * Render a page select box
	 */
	protected function render_page()
	{
		$args = array(
			'depth'		=> false,
			'child_of'	=> false,
			'echo'		=> true,
			'selected'	=> $this->option->get(),
			'name'		=> $this->option->name );

		// call the WP function
		wp_dropdown_pages( $args );
	}

	/**
	 * Render one or more radio button tags
	 */
	protected function render_radio()
	{
		$this->render_input_group( 'radio' );
	}

	/**
	 * Render a text input tag
	 */
	protected function render_text()
	{
		$this->render_input( 'text' );
	}

	/**
	 * Render textarea input tag
	 */
	protected function render_textarea()
	{ ?>
		<textarea name="<?php print esc_attr( $this->option->name ) ?>" id="<?php print esc_attr(  $this->option->field_id ) ?>" class="<?php print esc_attr( $this->option->field_class ) ?>" rows="5" cols="50"><?php print esc_attr( $this->option->get() ) ?></textarea> <?php
	}
}

?>
