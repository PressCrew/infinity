<?php
/**
 * PIE Framework API options uploader class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage options
 * @since 1.0
 */

/**
 * Make an uploaded file option easy
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
	 * Constructor
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
	 * Setup necessary scripts and actions
	 */
	public function init()
	{
		// enqueue flash uploader scripts
		wp_enqueue_script('swfupload-all');

		// enqueue uploader plugin and wrapper
		Pie_Easy_Loader::enqueue_script( 'jquery.swfupload', array( 'jquery' ) );
		Pie_Easy_Loader::enqueue_script( 'uploader', array( 'jquery' ) );

		// enque thickbox
		add_thickbox();

		// localize the upload wrapper
		$this->localize_script();
	}

	/**
	 * Initialize ajax actions
	 */
	public function init_ajax()
	{
		add_action( 'wp_ajax_pie_easy_options_uploader_media_url', array( $this, 'ajax_media_url' ) );
	}

	/**
	 * Localize the flash uploader class wrapper
	 */
	private function localize_script()
	{
		Pie_Easy_Loader::localize_script(
			'uploader',
			'pieEasyFlashUploaderL10n',
			array(
				'flash_url' => includes_url('js/swfupload/swfupload.swf'),
				'upload_url' => esc_attr( admin_url('async-upload.php') ),
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
	 * @param Pie_Easy_Options_Renderer $renderer
	 */
	public function render( Pie_Easy_Options_Option $option, Pie_Easy_Options_Renderer $renderer )
	{
		// make uploader element id
		$uploader_id = 'pie-easy-options-fu-' . $option->name;
		// make uploader var name
		$uploader_var = 'pie_easy_options_fu_' . $option->name;
		// get saved attachment url
		$attach_url = $this->media_url( $option->get() ); ?>

		<div class="pie-easy-options-fu" id="<?php print $uploader_id ?>">
			<fieldset class="pie-easy-options-fu-img">
				<legend><?php _e( 'Current Image' ) ?></legend>
				<p><img src="<?php print esc_attr( $attach_url ) ?>" alt="" /></p>
				<div><a class="thickbox" href="<?php print esc_attr( $attach_url ) ?>">Zoom</a><a>Remove</a></div>
			</fieldset>
			<fieldset class="pie-easy-options-fu-stat">
				<legend><?php _e( 'Upload Status' ) ?></legend>
				<textarea></textarea><div><p></p></div>
			</fieldset>
			<div class="pie-easy-options-fu-btn">
				<input type="button" /><?php
				$renderer->render_input( 'hidden' ); ?>
			</div>	
		</div><?php
	}

	/**
	 * Get the URL for an attachment id
	 * 
	 * @param string $attach_id
	 * @param string $size
	 * @return string|null
	 */
	public function media_url( $attach_id, $size = null )
	{
		// try to get the attachment info
		$src = wp_get_attachment_image_src( $attach_id, $size );

		// did we find one?
		if ( is_array($src) ) {
			// return the url
			return $src[0];
		} else {
			return null;
		}
	}

	/**
	 * Get the url for a media attachment
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
				Pie_Easy_Ajax::response( 1, $src[0], $src[1], $src[2] );
			} else {
				Pie_Easy_Ajax::response( 0, 'Failed to lookup attachment URL.' );
			}
		} else {
			Pie_Easy_Ajax::response( 0, 'No attachment ID received.' );
		}
	}
}

?>
