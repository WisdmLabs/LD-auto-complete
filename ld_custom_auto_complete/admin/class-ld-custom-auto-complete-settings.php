<?php
/**
 * Handles the setting section for autocompletion.
 *
 * @since      1.0.0
 * @package    Ld_Custom_Auto_Complete
 * @subpackage Ld_Custom_Auto_Complete/admin
 * @author     WisdmLabs <support@wisdmlabs.com>
 */

namespace Ld_Custom_Auto_Complete\Admin;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ld_Custom_Auto_Complete_Settings' ) ) {
	/**
	 * Class handles register and saving of custom settings.
	 */
	class Ld_Custom_Auto_Complete_Settings {

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
		 * Add autocomplete setting to the given setting option fields based on the provided settings metabox key.
		 *
		 * @param array  $setting_option_fields The setting option fields to add the autocomplete setting to.
		 * @param string $settings_metabox_key   The settings metabox key to determine the type of autocomplete setting to add.
		 *
		 * @return array The updated setting option fields with the added autocomplete setting.
		 */
		public function ld_custom_autocomplete_add_setting( $setting_option_fields = array(), $settings_metabox_key = '' ) {

			if ( 'learndash-topic-display-content-settings' === $settings_metabox_key || 'learndash-lesson-display-content-settings' === $settings_metabox_key ) {
				$post_id            = get_the_ID();
				$is_autocomplete_on = get_post_meta( $post_id, LD_CUSTOM_AUTO_COMPLETE_META, true );

				if ( 'learndash-topic-display-content-settings' === $settings_metabox_key ) {
					$label = sprintf(
							// translators: placeholder: Topic.
						esc_html_x( '%s Autocomplete', 'placeholder: Topic', 'learndash' ),
						learndash_get_custom_label( 'topic' )
					);
						$help_text = sprintf(
							// translators: placeholder: topic.
							esc_html_x( 'Turning this on will allow you to autocomplete %s.', 'placeholder: Topic.', 'learndash' ),
							learndash_get_custom_label_lower( 'topic' )
						);
				} else {
					$label = sprintf(
						// translators: placeholder: Lesson.
						esc_html_x( '%s Autocomplete', 'placeholder: Lesson', 'learndash' ),
						learndash_get_custom_label( 'lesson' )
					);
					$help_text = sprintf(
						// translators: placeholder: lesson.
						esc_html_x( 'Turning this on will allow you to autocomplete %s.', 'placeholder: Lesson.', 'learndash' ),
						learndash_get_custom_label_lower( 'lesson' )
					);
				}

				if ( ! isset( $setting_option_fields[ LD_CUSTOM_AUTO_COMPLETE_META ] ) ) {
					$setting_option_fields[ LD_CUSTOM_AUTO_COMPLETE_META ] = array(
						'name'      => LD_CUSTOM_AUTO_COMPLETE_META,
						'label'     => $label,
						'type'      => 'checkbox-switch',
						'class'     => '-medium',
						'value'     => $is_autocomplete_on,
						'default'   => '',
						'options'   => array(
							''   => '',
							'on' => '',
						),
						'help_text' => $help_text,
					);
				}
			}
			return $setting_option_fields;
		}

		/**
		 * Saves the autocomplete setting for a lesson.
		 *
		 * @param int $post_id  The ID of the post to save the setting for.
		 *
		 * @return void
		 */
		public function ld_custom_autocomplete_save_setting_lesson( $post_id = 0 ) {
			// bail when DOING_AUTOSAVE is true.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'update-post_' . $post_id ) ) {
				return;
			}
			$my_settings_value = '';
			if ( isset( $_POST['learndash-lesson-display-content-settings'][ LD_CUSTOM_AUTO_COMPLETE_META ] ) ) {
				$my_settings_value = sanitize_text_field( wp_unslash( $_POST['learndash-lesson-display-content-settings'][ LD_CUSTOM_AUTO_COMPLETE_META ] ) );
			}
			update_post_meta( $post_id, LD_CUSTOM_AUTO_COMPLETE_META, $my_settings_value );
		}

		/**
		 * Saves the autocomplete setting for a topic.
		 *
		 * @param int $post_id  The ID of the post to save the setting for.
		 *
		 * @return void
		 */
		public function ld_custom_autocomplete_save_setting_topic( $post_id = 0 ) {
			// bail when DOING_AUTOSAVE is true.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'update-post_' . $post_id ) ) {
				return;
			}
			$my_settings_value = '';
			if ( isset( $_POST['learndash-topic-display-content-settings'][ LD_CUSTOM_AUTO_COMPLETE_META ] ) ) {
				$my_settings_value = sanitize_text_field( wp_unslash( $_POST['learndash-topic-display-content-settings'][ LD_CUSTOM_AUTO_COMPLETE_META ] ) );
			}
				update_post_meta( $post_id, LD_CUSTOM_AUTO_COMPLETE_META, $my_settings_value );
		}
	}
}
