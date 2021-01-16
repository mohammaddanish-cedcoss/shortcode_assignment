<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Shortcodeplugin
 * @subpackage Shortcodeplugin/includes
 */

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
 * @package    Shortcodeplugin
 * @subpackage Shortcodeplugin/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Shortcodeplugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Shortcodeplugin_Loader    $loader    Maintains and registers all hooks for the plugin.
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

		if ( defined( 'SHORTCODEPLUGIN_VERSION' ) ) {

			$this->version = SHORTCODEPLUGIN_VERSION;
		} else {

			$this->version = '1.0.0';
		}

		$this->plugin_name = 'shortcodeplugin';

		$this->shortcodeplugin_dependencies();
		$this->shortcodeplugin_locale();
		$this->shortcodeplugin_admin_hooks();
		$this->shortcodeplugin_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Shortcodeplugin_Loader. Orchestrates the hooks of the plugin.
	 * - Shortcodeplugin_i18n. Defines internationalization functionality.
	 * - Shortcodeplugin_Admin. Defines all hooks for the admin area.
	 * - Shortcodeplugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function shortcodeplugin_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shortcodeplugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shortcodeplugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-shortcodeplugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcodeplugin-public.php';

		$this->loader = new Shortcodeplugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Shortcodeplugin_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function shortcodeplugin_locale() {

		$plugin_i18n = new Shortcodeplugin_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function shortcodeplugin_admin_hooks() {

		$s_plugin_admin = new Shortcodeplugin_Admin( $this->s_get_plugin_name(), $this->s_get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $s_plugin_admin, 's_admin_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $s_plugin_admin, 's_admin_enqueue_scripts' );

		// Add settings menu for shortcodeplugin.
		$this->loader->add_action( 'admin_menu', $s_plugin_admin, 's_options_page' );

		// All admin actions and filters after License Validation goes here.
		$this->loader->add_filter( 'mwb_add_plugins_menus_array', $s_plugin_admin, 's_admin_submenu_page', 15 );
		$this->loader->add_filter( 's_general_settings_array', $s_plugin_admin, 's_admin_general_settings_page', 10 );

		// CPT.
		$this->loader->add_action( 'init', $s_plugin_admin, 'product_custom_post_type' );
		// CT.
		$this->loader->add_action( 'init', $s_plugin_admin, 'pro_cat_register_taxonomy' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function shortcodeplugin_public_hooks() {

		$s_plugin_public = new Shortcodeplugin_Public( $this->s_get_plugin_name(), $this->s_get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $s_plugin_public, 's_public_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $s_plugin_public, 's_public_enqueue_scripts' );

		// Load ShortCode.
		$this->loader->add_action( 'init', $s_plugin_public, 'myshortcode' );

		// Inlcude Template.
		$this->loader->add_filter( 'template_include', $s_plugin_public, 'single_view_adjustment' );

	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function s_run() {
		$this->loader->s_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function s_get_plugin_name() {
		return $this->plugin_name;
	}

	/** add_filter( 'template_include', 'ibenic_checkout_template', 99 );
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Shortcodeplugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function s_get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function s_get_version() {
		return $this->version;
	}

	/**
	 * Predefined default mwb_s_plug tabs.
	 *
	 * @return  Array       An key=>value pair of shortcodeplugin tabs.
	 */
	public function mwb_s_plug_default_tabs() {

		$s_default_tabs = array();

		$s_default_tabs['shortcodeplugin-general'] = array(
			'title'       => esc_html__( 'General Setting', 'shortcodeplugin' ),
			'name'        => 'shortcodeplugin-general',
		);
		$s_default_tabs = apply_filters( 'mwb_s_plugin_standard_admin_settings_tabs', $s_default_tabs );

		$s_default_tabs['shortcodeplugin-system-status'] = array(
			'title'       => esc_html__( 'System Status', 'shortcodeplugin' ),
			'name'        => 'shortcodeplugin-system-status',
		);

		return $s_default_tabs;
	}

	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 * @param string $path path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function mwb_s_plug_load_template( $path, $params = array() ) {

		$s_file_path = SHORTCODEPLUGIN_DIR_PATH . $path;

		if ( file_exists( $s_file_path ) ) {

			include $s_file_path;
		} else {

			/* translators: %s: file path */
			$s_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'shortcodeplugin' ), $s_file_path );
			$this->mwb_s_plug_admin_notice( $s_notice, 'error' );
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param  string $s_message    Message to display.
	 * @param  string $type       notice type, accepted values - error/update/update-nag.
	 * @since  1.0.0
	 */
	public static function mwb_s_plug_admin_notice( $s_message, $type = 'error' ) {

		$s_classes = 'notice ';

		switch ( $type ) {

			case 'update':
				$s_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$s_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$s_classes .= 'notice-success is-dismissible';
				break;

			default:
				$s_classes .= 'notice-error is-dismissible';
		}

		$s_notice  = '<div class="' . esc_attr( $s_classes ) . '">';
		$s_notice .= '<p>' . esc_html( $s_message ) . '</p>';
		$s_notice .= '</div>';

		echo wp_kses_post( $s_notice );
	}


	/**
	 * Show wordpress and server info.
	 *
	 * @return  Array $s_system_data       returns array of all wordpress and server related information.
	 * @since  1.0.0
	 */
	public function mwb_s_plug_system_status() {
		global $wpdb;
		$s_system_status = array();
		$s_wordpress_status = array();
		$s_system_data = array();

		// Get the web server.
		$s_system_status['web_server'] = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '';

		// Get PHP version.
		$s_system_status['php_version'] = function_exists( 'phpversion' ) ? phpversion() : __( 'N/A (phpversion function does not exist)', 'shortcodeplugin' );

		// Get the server's IP address.
		$s_system_status['server_ip'] = isset( $_SERVER['SERVER_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_ADDR'] ) ) : '';

		// Get the server's port.
		$s_system_status['server_port'] = isset( $_SERVER['SERVER_PORT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_PORT'] ) ) : '';

		// Get the uptime.
		$s_system_status['uptime'] = function_exists( 'exec' ) ? @exec( 'uptime -p' ) : __( 'N/A (make sure exec function is enabled)', 'shortcodeplugin' );

		// Get the server path.
		$s_system_status['server_path'] = defined( 'ABSPATH' ) ? ABSPATH : __( 'N/A (ABSPATH constant not defined)', 'shortcodeplugin' );

		// Get the OS.
		$s_system_status['os'] = function_exists( 'php_uname' ) ? php_uname( 's' ) : __( 'N/A (php_uname function does not exist)', 'shortcodeplugin' );

		// Get WordPress version.
		$s_wordpress_status['wp_version'] = function_exists( 'get_bloginfo' ) ? get_bloginfo( 'version' ) : __( 'N/A (get_bloginfo function does not exist)', 'shortcodeplugin' );

		// Get and count active WordPress plugins.
		$s_wordpress_status['wp_active_plugins'] = function_exists( 'get_option' ) ? count( get_option( 'active_plugins' ) ) : __( 'N/A (get_option function does not exist)', 'shortcodeplugin' );

		// See if this site is multisite or not.
		$s_wordpress_status['wp_multisite'] = function_exists( 'is_multisite' ) && is_multisite() ? __( 'Yes', 'shortcodeplugin' ) : __( 'No', 'shortcodeplugin' );

		// See if WP Debug is enabled.
		$s_wordpress_status['wp_debug_enabled'] = defined( 'WP_DEBUG' ) ? __( 'Yes', 'shortcodeplugin' ) : __( 'No', 'shortcodeplugin' );

		// See if WP Cache is enabled.
		$s_wordpress_status['wp_cache_enabled'] = defined( 'WP_CACHE' ) ? __( 'Yes', 'shortcodeplugin' ) : __( 'No', 'shortcodeplugin' );

		// Get the total number of WordPress users on the site.
		$s_wordpress_status['wp_users'] = function_exists( 'count_users' ) ? count_users() : __( 'N/A (count_users function does not exist)', 'shortcodeplugin' );

		// Get the number of published WordPress posts.
		$s_wordpress_status['wp_posts'] = wp_count_posts()->publish >= 1 ? wp_count_posts()->publish : __( '0', 'shortcodeplugin' );

		// Get PHP memory limit.
		$s_system_status['php_memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'shortcodeplugin' );

		// Get the PHP error log path.
		$s_system_status['php_error_log_path'] = ! ini_get( 'error_log' ) ? __( 'N/A', 'shortcodeplugin' ) : ini_get( 'error_log' );

		// Get PHP max upload size.
		$s_system_status['php_max_upload'] = function_exists( 'ini_get' ) ? (int) ini_get( 'upload_max_filesize' ) : __( 'N/A (ini_get function does not exist)', 'shortcodeplugin' );

		// Get PHP max post size.
		$s_system_status['php_max_post'] = function_exists( 'ini_get' ) ? (int) ini_get( 'post_max_size' ) : __( 'N/A (ini_get function does not exist)', 'shortcodeplugin' );

		// Get the PHP architecture.
		if ( PHP_INT_SIZE == 4 ) {
			$s_system_status['php_architecture'] = '32-bit';
		} elseif ( PHP_INT_SIZE == 8 ) {
			$s_system_status['php_architecture'] = '64-bit';
		} else {
			$s_system_status['php_architecture'] = 'N/A';
		}

		// Get server host name.
		$s_system_status['server_hostname'] = function_exists( 'gethostname' ) ? gethostname() : __( 'N/A (gethostname function does not exist)', 'shortcodeplugin' );

		// Show the number of processes currently running on the server.
		$s_system_status['processes'] = function_exists( 'exec' ) ? @exec( 'ps aux | wc -l' ) : __( 'N/A (make sure exec is enabled)', 'shortcodeplugin' );

		// Get the memory usage.
		$s_system_status['memory_usage'] = function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage( true ) / 1024 / 1024, 2 ) : 0;

		// Get CPU usage.
		// Check to see if system is Windows, if so then use an alternative since sys_getloadavg() won't work.
		if ( stristr( PHP_OS, 'win' ) ) {
			$s_system_status['is_windows'] = true;
			$s_system_status['windows_cpu_usage'] = function_exists( 'exec' ) ? @exec( 'wmic cpu get loadpercentage /all' ) : __( 'N/A (make sure exec is enabled)', 'shortcodeplugin' );
		}

		// Get the memory limit.
		$s_system_status['memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'shortcodeplugin' );

		// Get the PHP maximum execution time.
		$s_system_status['php_max_execution_time'] = function_exists( 'ini_get' ) ? ini_get( 'max_execution_time' ) : __( 'N/A (ini_get function does not exist)', 'shortcodeplugin' );

		// Get outgoing IP address.
		$s_system_status['outgoing_ip'] = function_exists( 'file_get_contents' ) ? file_get_contents( 'http://ipecho.net/plain' ) : __( 'N/A (file_get_contents function does not exist)', 'shortcodeplugin' );

		$s_system_data['php'] = $s_system_status;
		$s_system_data['wp'] = $s_wordpress_status;

		return $s_system_data;
	}

	/**
	 * Generate html components.
	 *
	 * @param  string $s_components    html to display.
	 * @since  1.0.0
	 */
	public function mwb_s_plug_generate_html( $s_components = array() ) {
		if ( is_array( $s_components ) && ! empty( $s_components ) ) {
			foreach ( $s_components as $s_component ) {
				switch ( $s_component['type'] ) {

					case 'hidden':
					case 'number':
					case 'password':
					case 'email':
					case 'text':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $s_component['id'] ); ?>"><?php echo esc_html( $s_component['title'] ); // WPCS: XSS ok. ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $s_component['type'] ) ); ?>">
								<input
								name="<?php echo esc_attr( $s_component['id'] ); ?>"
								id="<?php echo esc_attr( $s_component['id'] ); ?>"
								type="<?php echo esc_attr( $s_component['type'] ); ?>"
								value="<?php echo esc_attr( $s_component['value'] ); ?>"
								class="<?php echo esc_attr( $s_component['class'] ); ?>"
								placeholder="<?php echo esc_attr( $s_component['placeholder'] ); ?>"
								/>
								<p class="s-descp-tip"><?php echo esc_html( $s_component['description'] ); // WPCS: XSS ok. ?></p>
							</td>
						</tr>
						<?php
						break;

					case 'textarea':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $s_component['id'] ); ?>"><?php echo esc_html( $s_component['title'] ); ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $s_component['type'] ) ); ?>">
								<textarea
								name="<?php echo esc_attr( $s_component['id'] ); ?>"
								id="<?php echo esc_attr( $s_component['id'] ); ?>"
								class="<?php echo esc_attr( $s_component['class'] ); ?>"
								rows="<?php echo esc_attr( $s_component['rows'] ); ?>"
								cols="<?php echo esc_attr( $s_component['cols'] ); ?>"
								placeholder="<?php echo esc_attr( $s_component['placeholder'] ); ?>"
								><?php echo esc_textarea( $s_component['value'] ); // WPCS: XSS ok. ?></textarea>
								<p class="s-descp-tip"><?php echo esc_html( $s_component['description'] ); // WPCS: XSS ok. ?></p>
							</td>
						</tr>
						<?php
						break;

					case 'select':
					case 'multiselect':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $s_component['id'] ); ?>"><?php echo esc_html( $s_component['title'] ); ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $s_component['type'] ) ); ?>">
								<select
								name="<?php echo esc_attr( $s_component['id'] ); ?><?php echo ( 'multiselect' === $s_component['type'] ) ? '[]' : ''; ?>"
								id="<?php echo esc_attr( $s_component['id'] ); ?>"
								class="<?php echo esc_attr( $s_component['class'] ); ?>"
								<?php echo 'multiselect' === $s_component['type'] ? 'multiple="multiple"' : ''; ?>
								>
								<?php
								foreach ( $s_component['options'] as $s_key => $s_val ) {
									?>
									<option value="<?php echo esc_attr( $s_key ); ?>"
										<?php
										if ( is_array( $s_component['value'] ) ) {
											selected( in_array( (string) $s_key, $s_component['value'], true ), true );
										} else {
											selected( $s_component['value'], (string) $s_key );
										}
										?>
										>
										<?php echo esc_html( $s_val ); ?>
									</option>
									<?php
								}
								?>
								</select> 
								<p class="s-descp-tip"><?php echo esc_html( $s_component['description'] ); // WPCS: XSS ok. ?></p>
							</td>
						</tr>
						<?php
						break;

					case 'checkbox':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc"><?php echo esc_html( $s_component['title'] ); ?></th>
							<td class="forminp forminp-checkbox">
								<label for="<?php echo esc_attr( $s_component['id'] ); ?>"></label>
								<input
								name="<?php echo esc_attr( $s_component['id'] ); ?>"
								id="<?php echo esc_attr( $s_component['id'] ); ?>"
								type="checkbox"
								class="<?php echo esc_attr( isset( $s_component['class'] ) ? $s_component['class'] : '' ); ?>"
								value="1"
								<?php checked( $s_component['value'], '1' ); ?>
								/> 
								<span class="s-descp-tip"><?php echo esc_html( $s_component['description'] ); // WPCS: XSS ok. ?></span>

							</td>
						</tr>
						<?php
						break;

					case 'radio':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $s_component['id'] ); ?>"><?php echo esc_html( $s_component['title'] ); ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $s_component['type'] ) ); ?>">
								<fieldset>
									<span class="s-descp-tip"><?php echo esc_html( $s_component['description'] ); // WPCS: XSS ok. ?></span>
									<ul>
										<?php
										foreach ( $s_component['options'] as $s_radio_key => $s_radio_val ) {
											?>
											<li>
												<label><input
													name="<?php echo esc_attr( $s_component['id'] ); ?>"
													value="<?php echo esc_attr( $s_radio_key ); ?>"
													type="radio"
													class="<?php echo esc_attr( $s_component['class'] ); ?>"
												<?php checked( $s_radio_key, $s_component['value'] ); ?>
													/> <?php echo esc_html( $s_radio_val ); ?></label>
											</li>
											<?php
										}
										?>
									</ul>
								</fieldset>
							</td>
						</tr>
						<?php
						break;

					case 'button':
						?>
						<tr valign="top">
							<td scope="row">
								<input type="button" class="button button-primary" 
								name="<?php echo esc_attr( $s_component['id'] ); ?>"
								id="<?php echo esc_attr( $s_component['id'] ); ?>"
								value="<?php echo esc_attr( $s_component['button_text'] ); ?>"
								/>
							</td>
						</tr>
						<?php
						break;

					case 'submit':
						?>
						<tr valign="top">
							<td scope="row">
								<input type="submit" class="button button-primary" 
								name="<?php echo esc_attr( $s_component['id'] ); ?>"
								id="<?php echo esc_attr( $s_component['id'] ); ?>"
								value="<?php echo esc_attr( $s_component['button_text'] ); ?>"
								/>
							</td>
						</tr>
						<?php
						break;

					default:
						break;
				}
			}
		}
	}
}
