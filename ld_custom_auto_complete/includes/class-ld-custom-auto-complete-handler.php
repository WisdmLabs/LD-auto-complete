<?php
/**
 *  This class handles the custom autocomplete functionality for LearnDash components,
 * including lessons, timers, and topics.
 *
 * @since      1.0.0
 * @package    Ld_Custom_Auto_Complete
 * @subpackage Ld_Custom_Auto_Complete/includes
 * @author     WisdmLabs <support@wisdmlabs.com>
 */

namespace Ld_Custom_Auto_Complete\Includes;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ld_Custom_Auto_Complete_Handler' ) ) {
	/**
	 * Class handles autocompletion of lesson.
	 */
	class Ld_Custom_Auto_Complete_Handler {
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
			if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'ld_custom_mark_complete_nonce' ) ) {
				$nonce_error_message = apply_filters( 'ld_custom_autocomplete_timer_nonce_error_message', __( 'Please provide proper nonce', 'ld_custom_auto_complete' ) );
				wp_send_json_error(
					array(
						'message' => $nonce_error_message,
						'type'    => 'error',
					),
					403
				);
			}
			$user_id     = get_current_user_id();
			$is_complete = false;
			$post_id     = isset( $_POST['post_id'] ) ? filter_var( wp_unslash( $_POST['post_id'] ), FILTER_SANITIZE_NUMBER_INT ) : '0';
			$course_id   = isset( $_POST['course_id'] ) ? filter_var( wp_unslash( $_POST['course_id'] ), FILTER_SANITIZE_NUMBER_INT ) : '0';
			$timer       = isset( $_POST['timer'] ) ? filter_var( wp_unslash( $_POST['timer'] ), FILTER_SANITIZE_NUMBER_INT ) : '';
			if ( '0' !== $post_id && '0' !== $course_id && '0' === $timer ) {
				$is_complete = learndash_process_mark_complete( $user_id, absint( $post_id ), false, absint( $course_id ) );
			}
			if ( true === $is_complete ) {
				$success_message = apply_filters( 'ld_custom_autocomplete_timer_success_message', __( 'Mark complete successfully done.', 'ld_custom_auto_complete' ) );
				wp_send_json_success(
					array(
						'message' => $success_message,
						'type'    => 'success',
					)
				);
			}
			$error_message = apply_filters( 'ld_custom_autocomplete_timer_failure_message', __( 'Mark complete failed due to insufficient permissions.', 'ld_custom_auto_complete' ) );
				wp_send_json_error(
					array(
						'message' => $error_message,
						'type'    => 'error',
					),
					403
				);
		}

		/**
		 * Autocomplete a lesson or topic.
		 *
		 * Checks if the lesson or topic is set to autocomplete and if the user has access to it.
		 *
		 * @param int $post_id The ID of the lesson or topic.
		 * @param int $course_id The ID of the course.
		 * @param int $user_id The ID of the user.
		 * @return void
		 */
		public function ld_custom_autocomplete_checker( $post_id, $course_id, $user_id ) {
			$is_autocomplete_on = get_post_meta( $post_id, LD_CUSTOM_AUTO_COMPLETE_META, true );
			if ( 'on' !== $is_autocomplete_on ) {
				return;
			}

			$is_available_content = ld_lesson_access_from( $post_id, $user_id, $course_id );

			if ( ! empty( $is_available_content ) ) {
				return;
			}

			if ( 'sfwd-topic' === get_post_type( $post_id ) ) {
				$lesson_id           = learndash_get_lesson_id( $post_id, $course_id );
				$is_available_lesson = ld_lesson_access_from( $lesson_id, $user_id, $course_id );

				if ( ! empty( $is_available_content ) && ! empty( $is_available_lesson ) ) {
					return;
				}
			}
			learndash_process_mark_complete( $user_id, $post_id, false, $course_id );
		}
	}
}
