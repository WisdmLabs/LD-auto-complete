<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wisdmlabs.com
 * @since      1.0.0
 *
 * @package    Ld_Custom_Auto_Complete
 * @subpackage Ld_Custom_Auto_Complete/includes
 */

namespace Ld_Custom_Auto_Complete\Includes;

/**
 * Core Classes
 */
use Ld_Custom_Auto_Complete\Includes\Ld_Custom_Auto_Complete_Loader;
use Ld_Custom_Auto_Complete\Includes\Ld_Custom_Auto_Complete_Activator;
use Ld_Custom_Auto_Complete\Includes\Ld_Custom_Auto_Complete_Deactivator;
use Ld_Custom_Auto_Complete\Includes\Ld_Custom_Auto_Complete_I18n;

/**
 * Public Class
 */
use Ld_Custom_Auto_Complete\Public\Ld_Custom_Auto_Complete_Frontend;

/**
 * Functionality Classes
 */
use Ld_Custom_Auto_Complete\Admin\Ld_Custom_Auto_Complete_Settings;
use Ld_Custom_Auto_Complete\Includes\Ld_Custom_Auto_Complete_Handler;
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ld_Custom_Auto_Complete
 * @subpackage Ld_Custom_Auto_Complete/includes
 * @author     wisdmlabs <archin.joshi@wisdmlabs.com>
 */
class Ld_Custom_Auto_Complete {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Ld_Custom_Auto_Complete_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'LD_CUSTOM_AUTO_COMPLETE_VERSION' ) ) {
			$this->version = LD_CUSTOM_AUTO_COMPLETE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'ld_custom_auto_complete';

		$this->load_dependencies();
		$this->handle_activation();
		$this->handle_deactivation();
		$this->set_locale();
		$this->define_public_hooks();
		$this->add_learndash_setting();
		$this->autocomplete_handler();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Ld_Custom_Auto_Complete_Loader. Orchestrates the hooks of the plugin.
	 * - Ld_Custom_Auto_Complete_I18n. Defines internationalization functionality.
	 * - Ld_Custom_Auto_Complete_Admin. Defines all hooks for the admin area.
	 * - Ld_Custom_Auto_Complete_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for handling activation functionalities of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-ld-custom-auto-complete-activator.php';

		/**
		 * The class responsible for handling deactivation functionalities of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-ld-custom-auto-complete-deactivator.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-ld-custom-auto-complete-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-ld-custom-auto-complete-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/class-ld-custom-auto-complete-frontend.php';

		/**
		 * The class responsible for defining all acttions related to adding and saving setting to learnDash.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-ld-custom-auto-complete-settings.php';

		/**
		 * The class responsible for defining all actions related to autocompleting lesson,topics and timer.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-ld-custom-auto-complete-handler.php';

		$this->loader = new Ld_Custom_Auto_Complete_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ld_Custom_Auto_Complete_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Ld_Custom_Auto_Complete_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Ld_Custom_Auto_Complete_Frontend( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	/**
	 * Handle plugin activation
	 */
	private function handle_activation() {
		$plugin_activator = new Ld_Custom_Auto_Complete_Activator();
		$plugin_activator->activate();
	}

	/**
	 * Handle plugin deactivation
	 */
	private function handle_deactivation() {
		$plugin_activator = new Ld_Custom_Auto_Complete_Deactivator();
		$plugin_activator->deactivate();
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Ld_Custom_Auto_Complete_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register all of the hooks related to adding and saving learndash setting
	 *
	 * @since   1.0.0
	 * @access  private
	 */
	private function add_learndash_setting() {
		$settings = Ld_Custom_Auto_Complete_Settings::get_instance();
		$this->loader->add_filter( 'learndash_settings_fields', $settings, 'ld_custom_autocomplete_add_setting', 30, 2 );
		$this->loader->add_action( 'save_post_sfwd-lessons', $settings, 'ld_custom_autocomplete_save_setting_lesson', 30 );
		$this->loader->add_action( 'save_post_sfwd-topic', $settings, 'ld_custom_autocomplete_save_setting_topic', 30 );
	}

	/**
	 * Register all of the hooks related to the autocompleting the lesson,topic and timer
	 *
	 * @since   1.0.0
	 * @access  private
	 */
	private function autocomplete_handler() {
		$handler = Ld_Custom_Auto_Complete_Handler::get_instance();
		$this->loader->add_action( 'learndash-lesson-before', $handler, 'ld_custom_autocomplete_lesson', 1, 3 );
		$this->loader->add_action( 'learndash-topic-before', $handler, 'ld_custom_autocomplete_topic', 1, 3 );
		$this->loader->add_action( 'wp_ajax_mark_complete', $handler, 'ld_custom_autocomplete_timer' );
	}
}
