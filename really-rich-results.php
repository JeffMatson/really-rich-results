<?php
/**
 * Main plugin file for bootstrapping Really Rich Results.
 *
 * @link              https://pagely.com
 * @since             0.1.0
 * @package           Really_Rich_Results
 *
 * @wordpress-plugin
 * Plugin Name:       Really Rich Results
 * Plugin URI:        https://pagely.com
 * Description:       Adds JSON-LD output for structured data
 * Version:           0.1.1
 * Author:            JeffMatson, joshuastrebel, Pagely
 * Author URI:        https://pagely.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       really-rich-results
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'RRR_VERSION', '0.1.1' );
define( 'RRR_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'RRR_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

// Register the autoloader.
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

// Initialize an instance of the main Really Rich Results class.
Really_Rich_Results\Main::get_instance()->init();
