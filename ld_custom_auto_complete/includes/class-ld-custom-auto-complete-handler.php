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
		 * Autocomplete a lesson.
		 *
		 * Checks if the lesson is set to autocomplete and if the user has access to it.
		 * If the lesson is set to autocomplete and the user does not have access, it marks the lesson as complete for the user.
		 *
		 * @param int $post_id The ID of the lesson.
		 * @param int $course_id The ID of the course.
		 * @param int $user_id The ID of the user.
		 * @return void
		 */
		public function ld_custom_autocomplete_lesson( $post_id, $course_id, $user_id ) {
			$is_autocomplete_on = get_post_meta( $post_id, LD_CUSTOM_AUTO_COMPLETE_META, true );

			if ( 'on' !== $is_autocomplete_on ) {
				return;
			}
			$is_available = ld_lesson_access_from( $post_id, $user_id, $course_id );
			if ( ! empty( $is_available ) ) {
				return;
			}
				learndash_process_mark_complete( $user_id, $post_id, false, $course_id );
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

		/**
		 * Autocompletes a topic.
		 *
		 * @param int $post_id The ID of the topic.
		 * @param int $course_id The ID of the course.
		 * @param int $user_id The ID of the user.
		 * @return void
		 */
		public function ld_custom_autocomplete_topic( $post_id, $course_id, $user_id ) {
			$is_autocomplete_on = get_post_meta( $post_id, LD_CUSTOM_AUTO_COMPLETE_META, true );
			if ( 'on' !== $is_autocomplete_on ) {
				return;
			}
			$lesson_id           = learndash_get_lesson_id( $post_id, $course_id );
			$is_available_topic  = ld_lesson_access_from( $post_id, $user_id, $course_id );
			$is_available_lesson = ld_lesson_access_from( $lesson_id, $user_id, $course_id );
			if ( ! empty( $is_available_topic ) && ! empty( $is_available_lesson ) ) {
				return;
			}
				learndash_process_mark_complete( $user_id, $post_id, false, $course_id );
		}
	}
}
