<?php
/**
 * PIE API options renderer class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'docs' );

/**
 * Make rendering options easy
 *
 * @package pie
 * @subpackage options
 */
abstract class Pie_Easy_Options_Option_Renderer
{
	/**
	 * All options that have been rendered
	 *
	 * @var array
	 */
	private $options_rendered = array();

	/**
	 * The current option being rendered
	 * 
	 * @var Pie_Easy_Options_Option
	 */
	private $option;

	/**
	 * The uploader renderer
	 *
	 * @var Pie_Easy_Options_Uploader
	 */
	private $uploader;

	/**
	 * Init screen dependancies for all applicable option types
	 */
	public function init_screen()
	{
		add_action( 'pie_easy_enqueue_styles', array($this, 'init_styles') );
		add_action( 'pie_easy_enqueue_scripts', array($this, 'init_scripts') );

		if ( $this->uploader instanceof Pie_Easy_Options_Uploader ) {
			$this->uploader->init_screen();
		}
	}

	/**
	 * Init screen style requirements
	 *
	 * @ignore
	 */
	public function init_styles()
	{
		// color picker
		wp_enqueue_style( 'pie-easy-colorpicker' );
	}

	/**
	 * Init screen style requirements
	 *
	 * @ignore
	 */
	public function init_scripts()
	{
		// jQuery UI
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-button' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-progressbar' );
		wp_enqueue_script( 'jquery-ui-tabs' );

		// color picker
		wp_enqueue_script( 'pie-easy-colorpicker' );
	}

	/**
	 * Initialize ajax requirements for all applicable option types
	 */
	final public function init_ajax()
	{
		// uploader
		if ( $this->uploader instanceof Pie_Easy_Options_Uploader ) {
			$this->uploader->init_ajax();
		}
	}

	/**
	 * Enable uploader support by passing a valid uploader object
	 *
	 * @param Pie_Easy_Options_Uploader $uploader
	 */
	final public function enable_uploader( Pie_Easy_Options_Uploader $uploader )
	{
		$this->uploader = $uploader;
	}

	/**
	 * Return true if the option being rendered has documentation to render
	 *
	 * @return boolean
	 */
	final protected function has_documentation()
	{
		return ( $this->option->documentation );
	}

	/**
	 * Render an option
	 *
	 * @param Pie_Easy_Options_Option $option The option to render
	 * @param boolean $output Set to false to return results instead of printing
	 * @return string|void
	 */
	final public function render( Pie_Easy_Options_Option $option, $output = true )
	{
		// check feature support
		if ( $option->supported() ) {
			
			// set as currently rendered option
			$this->option = $option;
			$this->option->enable_post_override();

			// handle output buffering if applicable
			if ( $output === false ) {
				ob_start();
			}

			// render the option
			$this->render_option();
			$this->options_rendered[] = $option;

			// return results if output buffering is on
			if ( $output === false ) {
				return ob_get_clean();
			}
		}
	}

	/**
	 * Render option label, input, and description wrapped in a container
	 *
	 * This is a very basic implementation. In most cases you will want to override
	 * this to generate custom markup.
	 */
	protected function render_option()
	{ 
		// start rendering ?>
		<div class="<?php $this->render_classes( 'pie-easy-options-wrapper' ) ?>">
			<?php $this->render_label() ?>
			<p class="pie-easy-options-description">
				<?php $this->render_description() ?>
			</p>
			<div class="pie-easy-options-field">
				<?php $this->render_field() ?>
			</div>
		</div><?php
	}

	/**
	 * Render the option name
	 * 
	 * This is useful when using it as part of an attribute
	 */
	protected function render_name()
	{
		print esc_attr( $this->option->name );
	}

	/**
	 * Render wrapper classes
	 *
	 * @param string $class,...
	 */
	protected function render_classes()
	{
		// get unlimited number of class args
		$classes = func_get_args();

		// append custom class if set
		if ( $this->option->class ) {
			$classes[] = $this->option->class;
		}

		// render them all delimited with a space
		print join( ' ', $classes );
	}

	/**
	 * Render form input label
	 */
	protected function render_label()
	{ ?>
		<label class="pie-easy-options-title" for="<?php $this->render_name() ?>" title="<?php print esc_attr( $this->option->title ) ?>"><?php print esc_attr( $this->option->title ) ?></label><?php
	}

	/**
	 * Render form input description
	 */
	protected function render_description()
	{
		print esc_attr( $this->option->description );
	}

	/**
	 * Render the field based on option type
	 */
	final protected function render_field()
	{
		// call the applicable rendering method
		switch ( $this->option->field_type ) {
			case Pie_Easy_Options_Option::FIELD_CATEGORY:
				$this->render_category();
				break;
			case Pie_Easy_Options_Option::FIELD_CATEGORIES:
				$this->render_categories();
				break;
			case Pie_Easy_Options_Option::FIELD_CHECKBOX:
				$this->render_checkbox();
				break;
			case Pie_Easy_Options_Option::FIELD_COLORPICKER:
				$this->render_colorpicker();
				break;
			case Pie_Easy_Options_Option::FIELD_CSS:
				$this->render_css();
				break;
			case Pie_Easy_Options_Option::FIELD_PAGE:
				$this->render_page();
				break;
			case Pie_Easy_Options_Option::FIELD_PAGES:
				$this->render_pages();
				break;
			case Pie_Easy_Options_Option::FIELD_POST:
				$this->render_post();
				break;
			case Pie_Easy_Options_Option::FIELD_POSTS:
				$this->render_posts();
				break;
			case Pie_Easy_Options_Option::FIELD_RADIO:
				$this->render_radio();
				break;
			case Pie_Easy_Options_Option::FIELD_SELECT:
				$this->render_select();
				break;
			case Pie_Easy_Options_Option::FIELD_TAG:
				$this->render_tag();
				break;
			case Pie_Easy_Options_Option::FIELD_TAGS:
				$this->render_tags();
				break;
			case Pie_Easy_Options_Option::FIELD_TEXT:
				$this->render_text();
				break;
			case Pie_Easy_Options_Option::FIELD_TEXTAREA:
				$this->render_textarea();
				break;
			case Pie_Easy_Options_Option::FIELD_UPLOAD:
				$this->render_upload();
				break;
			default:
				throw new UnexpectedValueException( sprintf(
					'The option type "%s" does not have a rendering method defined.',
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

		// render div wrapper if applicable
		if ( $this->option->field_id ) { ?>
			<div id="<?php print $this->option->field_id ?>"><?php
		}

		// call the WordPress function
		wp_list_categories( $args );

		// close div wrapper if applicable
		if ( $this->option->field_id ) { ?>
			</div><?php
		}
	}

	/**
	 * Render one or more checkboxes
	 *
	 * @see render_input_group
	 */
	protected function render_checkbox()
	{
		$this->render_input_group( 'checkbox' );
	}

	/**
	 * Render a color picker input
	 *
	 * @see render_input
	 */
	protected function render_colorpicker()
	{
		// render the input text field
		$this->render_input( 'text' );
		
		// now the color picker box ?>
		<div id="pie-easy-options-cp-wrapper-<?php $this->render_name() ?>" class="pie-easy-options-cp-box">
			<div style="background-color: <?php print esc_attr( $this->option->get() ) ?>;"></div>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				pieEasyColorPicker.init(
					'input[name=<?php $this->render_name() ?>]',
					'div#pie-easy-options-cp-wrapper-<?php $this->render_name() ?>'
				);
			});
		</script><?php
	}

	/**
	 * Render a CSS text area
	 *
	 * @see render_textarea
	 */
	protected function render_css()
	{
		// simply render a textarea
		$this->render_textarea();
	}

	/**
	 * Render a simple form input tag
	 *
	 * @param string $type A valid form input element "type" attribute value (see HTML spec)
	 */
	public function render_input( $type )
	{ ?>
		<input type="<?php print $type ?>" name="<?php $this->render_name() ?>" id="<?php print esc_attr(  $this->option->field_id ) ?>" class="<?php print esc_attr( $this->option->field_class ) ?>" value="<?php print esc_attr( $this->option->get() ) ?>" /> <?php
	}

	/**
	 * Render a group of inputs with the same name
	 *
	 * @param string $type Valid types are 'checkbox' and 'radio'
	 * @param array $field_options
	 * @param mixed $selected_value
	 */
	protected function render_input_group( $type, $field_options = null, $selected_value = null )
	{
		// field options defaults to rendered option config
		if ( empty( $field_options ) ) {
			$field_options = $this->option->field_options;
		}

		// select value defaults to rendered option setting
		if ( empty( $selected_value ) ) {
			$selected_value = $this->option->get();
		}

		// force the selected value to an array
		if ( !is_array( $selected_value ) ) {
			$selected_value = array( $selected_value );
		}

		if ( is_array( $field_options ) ) {
			// render div wrapper if applicable
			if ( $this->option->field_id ) { ?>
				<div id="<?php print $this->option->field_id ?>"><?php
			}
			// loop through all field options
			foreach ( $field_options as $value => $display ) {
				$loop = ($loop) ? $loop + 1 : 1;
				$checked = ( in_array( $value, $selected_value ) ) ? ' checked=checked' : null; ?>
				<input type="<?php print $type ?>" name="<?php $this->render_name() ?><?php if ( $type == 'checkbox' ): ?>[]<?php endif; ?>" value="<?php print esc_attr( $value ) ?>"<?php print $checked ?> /><label for="<?php print $element_id ?>"><?php print $display ?></label><?php
			}
			// close div wrapper if applicable
			if ( $this->option->field_id ) { ?>
				</div><?php
			}
		} else {
			throw new Exception( sprintf( 'The "%s" option has no array of field options to render.', $this->option->name ) );
		}
	}
	
	/**
	 * Render a page select box
	 */
	protected function render_page()
	{
		$args = array(
			'depth'		=> 0,
			'child_of'	=> 0,
			'echo'		=> true,
			'selected'	=> $this->option->get(),
			'name'		=> $this->option->name );

		// call the WP function
		wp_dropdown_pages( $args );
	}

	/**
	 * Render page checkboxes
	 */
	protected function render_pages()
	{
		$args = array(
			'depth'        => 0,
			'show_date'    => '',
			'date_format'  => get_option('date_format'),
			'child_of'     => 0,
			'exclude'      => '',
			'include'      => '',
			'title_li'     => '',
			'echo'         => true,
			'authors'      => '',
			'sort_column'  => 'menu_order, post_title',
			'link_before'  => '',
			'link_after'   => '',
			'walker'			=> new Pie_Easy_Options_Walker_Page(),
			'pie_easy_option'	=> $this->option );

		// render div wrapper if applicable
		if ( $this->option->field_id ) { ?>
			<div id="<?php print $this->option->field_id ?>"><?php
		}

		// call the WordPress function
		wp_list_pages( $args );

		// close div wrapper if applicable
		if ( $this->option->field_id ) { ?>
			</div><?php
		}
	}

	/**
	 * Render a post select box
	 *
	 * @see render_select
	 */
	protected function render_post()
	{
		// get all posts
		$posts = get_posts();

		// field options
		$options = array();

		// build of options array
		foreach ( $posts as $post ) {
			$options[$post->ID] = apply_filters( 'the_title', $post->post_title, $post->ID );
		}

		// call the select renderer
		$this->render_select( $options );
	}

	/**
	 * Render posts checkboxes
	 *
	 * @see render_input_group
	 */
	protected function render_posts()
	{
		// get all posts
		$posts = get_posts();

		// field options
		$options = array();

		// build of options array
		foreach ( $posts as $post ) {
			$options[$post->ID] = apply_filters( 'the_title', $post->post_title, $post->ID );
		}

		// call the input group  renderer
		$this->render_input_group( 'checkbox', $options );
	}

	/**
	 * Render one or more radio button tags
	 *
	 * @see render_input_group
	 */
	protected function render_radio()
	{
		$this->render_input_group( 'radio' );
	}

	/**
	 * Render a select tag
	 *
	 * @param array $field_options Multi-dimensional array of options to render (value => description)
	 * @param mixed $selected_value The value to render as selected
	 */
	protected function render_select( $field_options = null, $selected_value = null )
	{
		// field options defaults to rendered option config
		if ( empty( $field_options ) ) {
			$field_options = $this->option->field_options;
		}

		// select value defaults to rendered option setting
		if ( empty( $selected_value ) ) {
			$selected_value = $this->option->get();
		} ?>

		<select name="<?php $this->render_name() ?>" id="<?php print esc_attr( $this->option->field_id ) ?>" class="<?php print esc_attr( $this->option->field_class ) ?>">
			<option value="">--- Select One ---</option>
			<?php foreach ( $field_options as $value => $text ):
				$selected = ( $value == $selected_value ) ? ' selected="selected"' : null; ?>
				<option value="<?php print esc_attr( $value ) ?>"<?php print $selected ?>><?php print esc_html( $text ) ?></option>
			<?php endforeach; ?>
		</select><?php
	}

	/**
	 * Render a tag select box
	 *
	 * @see render_select
	 */
	protected function render_tag()
	{
		$args = array(
			'hide_empty' => false
		);

		// get all tags
		$tags = get_tags( $args );

		// field options
		$options = array();

		// build of options array
		foreach ( $tags as $tag ) {
			$options[$tag->term_id] = $tag->name;
		}

		// call the select renderer
		$this->render_select( $options );
	}

	/**
	 * Render tags checkboxes
	 *
	 * @see render_input_group
	 */
	protected function render_tags()
	{
		$args = array(
			'hide_empty' => false
		);

		// get all tags
		$tags = get_tags( $args );

		// field options
		$options = array();

		// build of options array
		foreach ( $tags as $tag ) {
			$options[$tag->term_id] = $tag->name;
		}

		// call the input group  renderer
		$this->render_input_group( 'checkbox', $options );
	}

	/**
	 * Render a text input tag
	 *
	 * @see render_input
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
		<textarea name="<?php $this->render_name() ?>" id="<?php print esc_attr(  $this->option->field_id ) ?>" class="<?php print esc_attr( $this->option->field_class ) ?>" rows="5" cols="50"><?php print esc_attr( $this->option->get() ) ?></textarea> <?php
	}

	/**
	 * Render a file uploader
	 *
	 * @see Pie_Easy_Options_Uploader::render()
	 */
	final protected function render_upload()
	{
		// make sure uploader was enabled
		if ( $this->uploader instanceof Pie_Easy_Options_Uploader ) {
			// render it!
			$this->uploader->render( $this->option, $this );
		} else {
			throw new Exception( 'Uploader support has not been initiated.' );
		}
	}

	/**
	 * Render a string representation of the date the option was last updated
	 *
	 * @param string $format
	 */
	final protected function render_date_updated( $format = 'F j, Y, g:i a' )
	{
		$time_updated = $this->option->get_meta( Pie_Easy_Options_Option::META_TIME_UPDATED );

		if ( $time_updated ) {
			print date( $format, $time_updated );
		} else {
			print 'Never';
		}
	}

	/**
	 * Render documentation for this option
	 *
	 * @param array $doc_dirs Directory paths under which to search for doc page file
	 */
	final protected function render_documentation( $doc_dirs )
	{
		// is documentation set?
		if ( $this->option->documentation ) {
			// boolean value?
			if ( is_numeric( $this->option->documentation ) ) {
				// use auto naming?
				if ( (boolean) $this->option->documentation == true ) {
					// yes, page is option name
					$page = $this->option->name;
				} else {
					// no, documentation disabled
					return;
				}
			} else {
				// page name was set manually
				$page = $this->option->documentation;
			}

			// new easy doc object
			$doc = new Pie_Easy_Docs( $doc_dirs, $page );

			// publish it!
			$doc->publish();
		}
	}
	
	/**
	 * Render sample code for this option
	 */
	final protected function render_sample_code()
	{
// begin rendering ?>
<strong>Test if option is set</strong>
<code>&lt;?php if ( infinity_option( '<?php print $this->option->name ?>' ) ): ?&gt;
    <?php print $this->option->name ?> has a value
&lt;?php endif; ?&gt;</code>

<strong>Echo an option value</strong>
<code>&lt;?php echo infinity_option( '<?php print $this->option->name ?>' ); ?&gt;</code><?php

		// special uploader functions
		if ( $this->option->field_type == Infinity_Options_Option::FIELD_UPLOAD ) {
// begin rendering ?>
<strong>Echo option as image URL</strong>
<code>&lt;img src="&lt;?php echo infinity_option_image_url( '<?php print $this->option->name ?>' ); ?&gt;"&gt;</code><?php
		}
	}

	/**
	 * Render a hidden input which is a serialized array of all option names that were rendered
	 *
	 * @param boolean $output
	 */
	final public function render_manifest( $output = true )
	{
		$option_names = array();

		foreach ( $this->options_rendered as $option ) {
			$option_names[] = $option->name;
		}

		$html = sprintf(
			'<input type="hidden" name="_manifest_" value="%s" />',
			esc_attr( implode( ',', $option_names ) )
		);

		if ( $output ) {
			print $html;
		} else {
			return $html;
		}
	}
}

?>
