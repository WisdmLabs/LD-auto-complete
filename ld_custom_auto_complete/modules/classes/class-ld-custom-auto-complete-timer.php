<?php
/**
 * Handles the timer completion ajax request.
 *
 * @since      1.0.0
 * @package    ld_auto_complete
 * @subpackage ld_custom_auto_complete/modules/classes
 * @author     WisdmLabs <support@wisdmlabs.com>
 */

namespace Ld_Custom_Auto_Complete\Modules\Classes;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ld_Custom_Auto_Complete_Timer' ) ) {
	/**
	 * Class handles timer completion ajax request.
	 */
	class Ld_Custom_Auto_Complete_Timer {
		/**
		 * Singleton instance of this class
		 *
		 * @var object  $instance
		 *
		 * @since 1.0.0
		 */
		protected static $instance = null;

		/**
		 * Class constructor.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {
		}

		/**
		 * Get a singleton instance of this class
		 *
		 * @return object
		 * @since   1.0.0
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}


		/**
		 * Handles the timer completion ajax request.
		 *
		 * This function is responsible for handling the ajax request when the timer
		 * is completed.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function ld_custom_autocomplete_timer() {
			if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'mark_complete_nonce' ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Please provide proper nonce', 'ld_custom_auto_complete' ),
						'type'    => 'error',
					),
					403
				);
			}
			$user_id     = get_current_user_id();
			$is_complete = false;
			if ( isset( $_POST['post_id'] ) && isset( $_POST['course_id'] ) && isset( $_POST['timer'] ) && '0' === $_POST['timer'] ) {
				$is_complete = learndash_process_mark_complete( $user_id, intval( $_POST['post_id'] ), false, intval( $_POST['course_id'] ) );
			}
			if ( true === $is_complete ) {
				wp_send_json_success(
					array(
						'message' => __( 'Mark complete Sucessfully done.', 'ld_custom_auto_complete' ),
						'type'    => 'success',
					)
				);
			} else {
				wp_send_json_error(
					array(
						'message' => __( 'Mark complete Failed due to insufficient permissions.', 'ld_custom_auto_complete' ),
						'type'    => 'error',
					),
					403
				);
			}
		}
	}
}
