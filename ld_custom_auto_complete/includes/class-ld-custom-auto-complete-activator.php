<?php
/**
 * Fired during plugin activation
 *
 * @link       https://wisdmlabs.com
 * @since      1.0.0
 *
 * @package    Ld_Custom_Auto_Complete
 * @subpackage Ld_Custom_Auto_Complete/includes
 */

namespace Ld_Custom_Auto_Complete\Includes;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ld_Custom_Auto_Complete
 * @subpackage Ld_Custom_Auto_Complete/includes
 * @author     wisdmlabs <archin.joshi@wisdmlabs.com>
 */
class Ld_Custom_Auto_Complete_Activator {

	/**
	 * Activates the plugin.
	 *
	 * This function is called when the plugin is activated. It performs any necessary setup or initialization tasks.
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		add_action( 'init', array( $this, 'ld_custom_is_learndash_active' ) );
	}

	/**
	 * Check if LearnDash is active.
	 *
	 * @since 1.0.0
	 */
	public function ld_custom_is_learndash_active() {
		if ( ! class_exists( 'LDLMS_Factory_Post' ) ) {
			add_action( 'admin_notices', array( $this, 'ld_custom_display_learndash_admin_notice' ) );
		}
	}

	/**
	 * Display learndash admin notice.
	 *
	 * @since 1.0.0
	 */
	public function ld_custom_display_learndash_admin_notice() {
		$message = __( 'AutoCompleter requires LearnDash to be installed and activated.', 'ld_custom_auto_complete' );
		printf( '<div class="error"><p>%s</p></div>', esc_html( $message ) );
		$this->ld_custom_deactivate_custom_plugin();
	}

	/**
	 * Deactivate Custom Plugin if leardash not found.
	 *
	 * @since 1.0.0
	 */
	public function ld_custom_deactivate_custom_plugin() {
		deactivate_plugins( plugin_basename( LD_CUSTOM_AUTO_COMPLETE_BASE ) );
		unset( $_GET['activate'] );
	}
}
