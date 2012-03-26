<?php
/**
 * ICE API: option extensions, uploader class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage options
 * @since 1.0
 */

ICE_Loader::load( 'utils/ajax' );

/**
 * Uploader option
 *
 * @package ICE-extensions
 * @subpackage options
 */
class ICE_Exts_Options_Upload
	extends ICE_Options_Option_Image
{
	/**
	 */
	protected function init()
	{
		// always run parent first
		parent::init();

		// upload files is required
		$this->add_capabilities( 'upload_files' );
	}

	/**
	 */
	public function init_styles()
	{
		parent::init_styles();

		if ( is_admin() ) {
			// need image editor styles
			wp_enqueue_style( 'imgareaselect' );
		}
	}

	/**
	 */
	public function init_scripts()
	{
		parent::init_scripts();

		if ( is_admin() ) {
			// need uploader plugin, ajax response, image editor scripts
			wp_enqueue_script( 'ice-uploader' );
			wp_enqueue_script( 'wp-ajax-response' );
			wp_enqueue_script( 'image-edit' );

			// localize the upload wrapper
			$this->localize_script();
		}
	}

	/**
	 */
	public function init_ajax()
	{
		parent::init_ajax();

		add_action( 'wp_ajax_ice_options_uploader_media_url', array( $this, 'ajax_media_url' ) );
		add_action( 'wp_ajax_ice_options_uploader_image_edit', array( $this, 'ajax_image_edit' ) );
	}

	/**
	 * Localize the flash uploader class wrapper
	 */
	protected function localize_script()
	{
		wp_localize_script(
			'ice-uploader',
			'iceEasyFlashUploaderL10n',
			array(
				'upload_url' => admin_url( ICE_Enqueue::SCRIPT_ASYNC ),
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
				ICE_Ajax::response( true, $src[0], $src[1], $src[2] );
			} else {
				ICE_Ajax::response( false, __('Failed to lookup attachment URL', infinity_text_domain) );
			}
		} else {
			ICE_Ajax::response( 0, __('No attachment ID received', infinity_text_domain) );
		}
	}

	/**
	 * Print the WP image edit form via ajax
	 */
	public function ajax_image_edit()
	{
		if ( isset( $_POST['attachment_id'] ) && is_numeric( $_POST['attachment_id'] ) ) {
			// load api file
			require_once ABSPATH . 'wp-admin/includes/image-edit.php'; ?>
			<div class="image-editor" id="image-editor-<?php echo $_POST['attachment_id'] ?>"><?php
			wp_image_editor( $_POST['attachment_id'] ); ?>
			</div> <?php
			die();
		}
	}

	/**
	 */
	public function get_template_vars()
	{
		list( $url, $width, $height ) = $this->get_image_src('full');

		return array(
			'attach_url' => $url,
			'attach_width' => $width,
			'attach_height' => $height,
			'edit_url' =>
				sprintf(
					'media.php?attachment_id=%d&action=edit',
					$this->get()
				)
		);
	}
}

?>
