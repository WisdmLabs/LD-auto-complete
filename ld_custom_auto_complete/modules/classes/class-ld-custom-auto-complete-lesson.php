<?php
/**
 * Handles the lesson completion logic.
 *
 * @since      1.0.0
 * @package    ld_auto_complete
 * @subpackage ld_custom_auto_complete/modules/classes
 * @author     WisdmLabs <support@wisdmlabs.com>
 */

namespace Ld_Custom_Auto_Complete\Modules\Classes;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ld_Custom_Auto_Complete_Lesson' ) ) {
	/**
	 * Class handles autocompletion of lesson.
	 */
	class Ld_Custom_Auto_Complete_Lesson {
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

			if ( 'on' === $is_autocomplete_on ) {
				$is_available = ld_lesson_access_from( $post_id, $user_id, $course_id );

				if ( empty( $is_available ) ) {
					learndash_process_mark_complete( $user_id, $post_id, false, $course_id );
				}
			}
		}
	}
}
