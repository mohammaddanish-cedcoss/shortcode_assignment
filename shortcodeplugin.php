<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Shortcodeplugin
 *
 * @wordpress-plugin
 * Plugin Name:       shortcodeplugin
 * Plugin URI:        https://makewebbetter.com/product/shortcodeplugin/
 * Description:       This plugin is for shortcode assignment
 * Version:           1.0.0
 * Author:            makewebbetter
 * Author URI:        https://makewebbetter.com/
 * Text Domain:       shortcodeplugin
 * Domain Path:       /languages
 *
 * Requires at least: 4.6
 * Tested up to:      4.9.5
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	die;
}

/**
 * Define plugin constants.
 *
 * @since             1.0.0
 */
function define_shortcodeplugin_constants() {

	shortcodeplugin_constants('SHORTCODEPLUGIN_VERSION', '1.0.0');
	shortcodeplugin_constants('SHORTCODEPLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
	shortcodeplugin_constants('SHORTCODEPLUGIN_DIR_URL', plugin_dir_url(__FILE__));
	shortcodeplugin_constants('SHORTCODEPLUGIN_SERVER_URL', 'https://makewebbetter.com');
	shortcodeplugin_constants('SHORTCODEPLUGIN_ITEM_REFERENCE', 'shortcodeplugin');
}

/**
 * Callable function for defining plugin constants.
 *
 * @param   String $key    Key for contant.
 * @param   String $value   value for contant.
 * @since             1.0.0
 */
function shortcodeplugin_constants($key, $value) {

	if (!defined($key)) {

		define($key, $value);
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-shortcodeplugin-activator.php
 */
function activate_shortcodeplugin() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-shortcodeplugin-activator.php';
	Shortcodeplugin_Activator::shortcodeplugin_activate();
	$mwb_s_active_plugin = get_option('mwb_all_plugins_active', false);
	if (is_array($mwb_s_active_plugin) && !empty($mwb_s_active_plugin)) {
		$mwb_s_active_plugin['shortcodeplugin'] = array(
			'plugin_name' => __('shortcodeplugin', 'shortcodeplugin'),
			'active' => '1',
		);
	} else {
		$mwb_s_active_plugin = array();
		$mwb_s_active_plugin['shortcodeplugin'] = array(
			'plugin_name' => __('shortcodeplugin', 'shortcodeplugin'),
			'active' => '1',
		);
	}
	update_option('mwb_all_plugins_active', $mwb_s_active_plugin);

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-shortcodeplugin-deactivator.php
 */
function deactivate_shortcodeplugin() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-shortcodeplugin-deactivator.php';
	Shortcodeplugin_Deactivator::shortcodeplugin_deactivate();
	$mwb_s_deactive_plugin = get_option('mwb_all_plugins_active', false);
	if (is_array($mwb_s_deactive_plugin) && !empty($mwb_s_deactive_plugin)) {
		foreach ($mwb_s_deactive_plugin as $mwb_s_deactive_key => $mwb_s_deactive) {
			if ('shortcodeplugin' === $mwb_s_deactive_key) {
				$mwb_s_deactive_plugin[$mwb_s_deactive_key]['active'] = '0';
			}
		}
	}
	update_option('mwb_all_plugins_active', $mwb_s_deactive_plugin);

}

register_activation_hook(__FILE__, 'activate_shortcodeplugin');
register_deactivation_hook(__FILE__, 'deactivate_shortcodeplugin');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-shortcodeplugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_shortcodeplugin() {
	define_shortcodeplugin_constants();

	$s_plugin_standard = new Shortcodeplugin();
	$s_plugin_standard->s_run();
	$GLOBALS['s_mwb_s_obj'] = $s_plugin_standard;

}
run_shortcodeplugin();

// Add rest api endpoint for plugin.
add_action('rest_api_init', 's_add_default_endpoint');

/**
 * Callback function for endpoints.
 *
 * @since    1.0.0
 */
function s_add_default_endpoint() {
	register_rest_route(
		's-route',
		'/s-dummy-data/',
		array(
			'methods' => 'POST',
			'callback' => 'mwb_s_default_callback',
			'permission_callback' => 'mwb_s_default_permission_check',
		)
	);
}

/**
 * API validation
 * @param Array $request All information related with the api request containing in this array.
 * @since    1.0.0
 */
function mwb_s_default_permission_check($request) {

	// Add rest api validation for each request.
	$result = true;
	return $result;
}

/**
 * Begins execution of api endpoint.
 *
 * @param   Array $request    All information related with the api request containing in this array.
 * @return  Array   $mwb_s_response   return rest response to server from where the endpoint hits.
 * @since    1.0.0
 */
function mwb_s_default_callback($request) {
	require_once SHORTCODEPLUGIN_DIR_PATH . 'includes/class-shortcodeplugin-api-process.php';
	$mwb_s_api_obj = new Shortcodeplugin_Api_Process();
	$mwb_s_resultsdata = $mwb_s_api_obj->mwb_s_default_process($request);
	if (is_array($mwb_s_resultsdata) && isset($mwb_s_resultsdata['status']) && 200 === $mwb_s_resultsdata['status']) {
		unset($mwb_s_resultsdata['status']);
		$mwb_s_response = new WP_REST_Response($mwb_s_resultsdata, 200);
	} else {
		$mwb_s_response = new WP_Error($mwb_s_resultsdata);
	}
	return $mwb_s_response;
}

// Add settings link on plugin page.
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'shortcodeplugin_settings_link');

/**
 * Settings link.
 *
 * @since    1.0.0
 * @param   Array $links    Settings link array.
 */
function shortcodeplugin_settings_link($links) {

	$my_link = array(
		'<a href="' . admin_url('admin.php?page=shortcodeplugin_menu') . '">' . __('Settings', 'shortcodeplugin') . '</a>',
	);
	return array_merge($my_link, $links);
}
