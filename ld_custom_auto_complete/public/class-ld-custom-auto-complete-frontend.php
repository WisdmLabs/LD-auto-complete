<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wisdmlabs.com
 * @since      1.0.0
 *
 * @package    Ld_Custom_Auto_Complete
 * @subpackage Ld_Custom_Auto_Complete/public
 */

namespace Ld_Custom_Auto_Complete\Public;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ld_Custom_Auto_Complete
 * @subpackage Ld_Custom_Auto_Complete/public
 * @author     wisdmlabs <archin.joshi@wisdmlabs.com>
 */
class Ld_Custom_Auto_Complete_Frontend {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if ( is_singular( array( 'sfwd-topic', 'sfwd-lessons' ) ) ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ld_custom_auto_complete-frontend.js', array( 'jquery' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name,
				'ld_custom_auto_complete_localized_data',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'ld_custom_mark_complete_nonce' ),
					'user_id'  => get_current_user_id(),
				)
			);
		}
	}
}
