<?php
/**
 * Handles the topic completion logic.
 *
 * @since      1.0.0
 * @package    ld_auto_complete
 * @subpackage ld_custom_auto_complete/modules/classes
 * @author     WisdmLabs <support@wisdmlabs.com>
 */

namespace Ld_Custom_Auto_Complete\Modules\Classes;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ld_Custom_Auto_Complete_Topic' ) ) {
	/**
	 * Class handles autocompletion of topic.
	 */
	class Ld_Custom_Auto_Complete_Topic {
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
		 * Autocompletes a topic.
		 *
		 * @param int $post_id The ID of the topic.
		 * @param int $course_id The ID of the course.
		 * @param int $user_id The ID of the user.
		 * @return void
		 */
		public function ld_custom_autocomplete_topic( $post_id, $course_id, $user_id ) {
			$is_autocomplete_on = get_post_meta( $post_id, LD_CUSTOM_AUTO_COMPLETE_META, true );
			if ( 'on' === $is_autocomplete_on ) {
				$lesson_id           = learndash_get_lesson_id( $post_id, $course_id );
				$is_available_topic  = ld_lesson_access_from( $post_id, $user_id, $course_id );
				$is_available_lesson = ld_lesson_access_from( $lesson_id, $user_id, $course_id );

				if ( empty( $is_available_topic ) && empty( $is_available_lesson ) ) {
					learndash_process_mark_complete( $user_id, $post_id, false, $course_id );
				}
			}
		}
	}
}
