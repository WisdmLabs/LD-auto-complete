<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wisdmlabs.com
 * @since             1.0.0
 * @package           Ld_Custom_Auto_Complete
 *
 * @wordpress-plugin
 * Plugin Name:       AutoCompleter
 * Plugin URI:        https://wisdmlabs.com
 * Description:       A custom LearnDash autocompleter that provides administrators with the ability to enable or disable autocomplete settings for specific lessons or topics
 * Version:           1.0.0
 * Author:            wisdmlabs
 * Author URI:        https://wisdmlabs.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ld_custom_auto_complete
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LD_CUSTOM_AUTO_COMPLETE_VERSION', '1.0.0' );

/**
 * Plugin dir path Constant
 *
 * @since 1.0.0
 */
if ( ! defined( 'LD_CUSTOM_AUTO_COMPLETE_ABSPATH' ) ) {
	define( 'LD_CUSTOM_AUTO_COMPLETE_ABSPATH', plugin_dir_path( __FILE__ ) );
}

/**
 * Plugin BaseName Constant
 *
 * @since 1.0.0
 */
if ( ! defined( 'LD_CUSTOM_AUTO_COMPLETE_BASE' ) ) {
	define( 'LD_CUSTOM_AUTO_COMPLETE_BASE', plugin_basename( __FILE__ ) );
}

/**
 * Autocomplete meta.
 */
define( 'LD_CUSTOM_AUTO_COMPLETE_META', 'ld_custom_autocomplete' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ld-custom-auto-complete.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ld_custom_auto_complete() {

	$plugin = new ld_custom_auto_complete\Includes\Ld_Custom_Auto_Complete();
	$plugin->run();
}
run_ld_custom_auto_complete();
