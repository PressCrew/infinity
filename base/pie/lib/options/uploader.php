<?php
/**
 * PIE API: options uploader class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'utils/ajax', 'utils/files' );

/**
 * Make an uploaded file option easy
 *
 * @package PIE
 * @subpackage options
 */
class Pie_Easy_Options_Uploader
{
	/**
	 * The action on which to localize the script
	 *
	 * @var string
	 */
	private $script_action;

	/**
	 * Initializes the uploader
	 *
	 * @param string $script_action The action on which to localize the script
	 */
	public function __construct( $script_action = null )
	{
		if ( $script_action ) {
			$this->script_action = $script_action;
		}
	}

	/**
	 * Enqueue required styles
	 */
	public function init_styles()
	{
		// enqueue image editor style
		wp_enqueue_style( 'imgareaselect' );
	}

	/**
	 * Enqueue required scripts
	 */
	public function init_scripts()
	{
		// enqueue uploader plugin
		wp_enqueue_script( 'pie-easy-uploader' );

		// enqueue image editor scripts and style
		wp_enqueue_script( 'wp-ajax-response' );
		wp_enqueue_script( 'image-edit' );

		// localize the upload wrapper
		$this->localize_script();
	}

	/**
	 * Initialize ajax actions
	 */
	public function init_ajax()
	{
		add_action( 'wp_ajax_pie_easy_options_uploader_media_url', array( $this, 'ajax_media_url' ) );
		add_action( 'wp_ajax_pie_easy_options_uploader_image_edit', array( $this, 'ajax_image_edit' ) );
	}

	/**
	 * Localize the flash uploader class wrapper
	 */
	private function localize_script()
	{
		wp_localize_script(
			'pie-easy-uploader',
			'pieEasyFlashUploaderL10n',
			array(
				'upload_url' => admin_url( Pie_Easy_Enqueue::SCRIPT_ASYNC ),
				'flash_url' => includes_url('js/swfupload/swfupload.swf'),
				'pp_auth_cookie' => (is_ssl() ? $_COOKIE[SECURE_AUTH_COOKIE] : $_COOKIE[AUTH_COOKIE]),
				'pp_logged_in_cookie' => $_COOKIE[LOGGED_IN_COOKIE],
				'pp_wpnonce' => wp_create_nonce( 'media-form' ),
				'file_size_limit' => 1024 * 1024,
				'button_image_url' => includes_url('images/upload.png?ver=20100531')
			)
		);
	}

	/**
	 * Render a flash uploader for the given option
	 *
	 * @param Pie_Easy_Options_Option $option
	 * @param Pie_Easy_Options_Policy $policy
	 */
	public function render( Pie_Easy_Options_Option $option, Pie_Easy_Options_Policy $policy )
	{
		$edit_url = sprintf( 'media.php?attachment_id=%d&action=edit', $option->get() );
		list( $attach_url, $attach_width, $attach_height ) = $option->get_image_src('full'); ?>
		<div class="pie-easy-options-fu">
			<fieldset class="pie-easy-options-fu-img ui-corner-all">
				<legend class="ui-corner-all"><?php _e('Current Image', pie_easy_text_domain) ?></legend>
				<p><img src="<?php print esc_attr( $attach_url ) ?>" alt="" /></p>
				<div class="pie-easy-options-fu-ibar">
					<a><?php _e('Zoom', pie_easy_text_domain) ?></a>
					<a><?php _e('Edit', pie_easy_text_domain) ?></a>
					<a><?php _e('Trash', pie_easy_text_domain) ?></a>
				</div>
				<div class="pie-easy-options-fu-zoom" title="<?php _e('Full Size Image', pie_easy_text_domain) ?>">
					<img src="<?php print esc_attr( $attach_url ) ?>"  height="<?php print $attach_height ?>" width="<?php print $attach_width ?>" alt="">
				</div>
			</fieldset>
			<fieldset class="pie-easy-options-fu-stat ui-corner-all">
				<legend class="ui-corner-all"><?php _e('Upload Status', pie_easy_text_domain) ?></legend>
				<textarea></textarea><div><p></p></div>
			</fieldset>
			<div class="pie-easy-options-fu-btn">
				<input type="button" /><?php
				$policy->renderer()->render_input( 'hidden' ); ?>
			</div>
		</div><?php
	}

	/**
	 * Get the url for a media attachment for an AJAX request
	 */
	public function ajax_media_url()
	{
		if ( isset( $_POST['attachment_id'] ) && is_numeric( $_POST['attachment_id'] ) ) {
			// determine size to retrieve
			$size = ( isset( $_POST['attachment_size'] ) ) ? $_POST['attachment_size'] : 'full';
			// try to get the attachment info
			$src = wp_get_attachment_image_src( $_POST['attachment_id'], $size );
			// check it out
			if ( is_array($src) ) {
				Pie_Easy_Ajax::response( true, $src[0], $src[1], $src[2] );
			} else {
				Pie_Easy_Ajax::response( false, __('Failed to lookup attachment URL', pie_easy_text_domain) );
			}
		} else {
			Pie_Easy_Ajax::response( 0, __('No attachment ID received', pie_easy_text_domain) );
		}
	}

	/**
	 * Print the WP image edit form via ajax
	 */
	public function ajax_image_edit()
	{
		if ( isset( $_POST['attachment_id'] ) && is_numeric( $_POST['attachment_id'] ) ) {
			// load api file
			require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'image-edit.php'; ?>
			<div class="image-editor" id="image-editor-<?php echo $_POST['attachment_id'] ?>"><?php
			wp_image_editor( $_POST['attachment_id'] ); ?>
			</div> <?php
			die();
		}
	}
}

?>
