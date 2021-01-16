<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Shortcodeplugin
 * @subpackage Shortcodeplugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Shortcodeplugin
 * @subpackage Shortcodeplugin/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Shortcodeplugin_Admin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function s_admin_enqueue_styles( $hook ) {

		wp_enqueue_style( 'mwb-s-select2-css', SHORTCODEPLUGIN_DIR_URL . 'admin/css/shortcodeplugin-select2.css', array(), time(), 'all' );

		wp_enqueue_style( $this->plugin_name, SHORTCODEPLUGIN_DIR_URL . 'admin/css/shortcodeplugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function s_admin_enqueue_scripts( $hook ) {

		wp_enqueue_script( 'mwb-s-select2', SHORTCODEPLUGIN_DIR_URL . 'admin/js/shortcodeplugin-select2.js', array( 'jquery' ), time(), false );

		wp_register_script( $this->plugin_name . 'admin-js', SHORTCODEPLUGIN_DIR_URL . 'admin/js/shortcodeplugin-admin.js', array( 'jquery', 'mwb-s-select2' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name . 'admin-js',
			's_admin_param',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'reloadurl' => admin_url( 'admin.php?page=shortcodeplugin_menu' ),
			)
		);

		wp_enqueue_script( $this->plugin_name . 'admin-js' );
	}

	/**
	 * Adding settings menu for shortcodeplugin.
	 *
	 * @since    1.0.0
	 */
	public function s_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( __( 'MakeWebBetter', 'shortcodeplugin' ), __( 'MakeWebBetter', 'shortcodeplugin' ), 'manage_options', 'mwb-plugins', array( $this, 'mwb_plugins_listing_page' ), SHORTCODEPLUGIN_DIR_URL . 'admin/images/mwb-logo.png', 15 );
			$s_menus = apply_filters( 'mwb_add_plugins_menus_array', array() );
			if ( is_array( $s_menus ) && ! empty( $s_menus ) ) {
				foreach ( $s_menus as $s_key => $s_value ) {
					add_submenu_page( 'mwb-plugins', $s_value['name'], $s_value['name'], 'manage_options', $s_value['menu_link'], array( $s_value['instance'], $s_value['function'] ) );
				}
			}
		}
	}


	/**
	 * shortcodeplugin s_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function s_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'            => __( 'shortcodeplugin', 'shortcodeplugin' ),
			'slug'            => 'shortcodeplugin_menu',
			'menu_link'       => 'shortcodeplugin_menu',
			'instance'        => $this,
			'function'        => 's_options_menu_html',
		);
		return $menus;
	}


	/**
	 * shortcodeplugin mwb_plugins_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function mwb_plugins_listing_page() {
		$active_marketplaces = apply_filters( 'mwb_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			require SHORTCODEPLUGIN_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * shortcodeplugin admin menu page.
	 *
	 * @since    1.0.0
	 */
	public function s_options_menu_html() {

		include_once SHORTCODEPLUGIN_DIR_PATH . 'admin/partials/shortcodeplugin-admin-display.php';
	}

	/**
	 * Shortcodeplugin admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $s_settings_general Settings fields.
	 */
	public function s_admin_general_settings_page( $s_settings_general ) {
		$s_settings_general = array(
			array(
				'title' => __( 'Text Field Demo', 'shortcodeplugin' ),
				'type'  => 'text',
				'description'  => __( 'This is text field demo follow same structure for further use.', 'shortcodeplugin' ),
				'id'    => 's_text_demo',
				'value' => '',
				'class' => 's-text-class',
				'placeholder' => __( 'Text Demo', 'shortcodeplugin' ),
			),
			array(
				'title' => __( 'Number Field Demo', 'shortcodeplugin' ),
				'type'  => 'number',
				'description'  => __( 'This is number field demo follow same structure for further use.', 'shortcodeplugin' ),
				'id'    => 's_number_demo',
				'value' => '',
				'class' => 's-number-class',
				'placeholder' => '',
			),
			array(
				'title' => __( 'Password Field Demo', 'shortcodeplugin' ),
				'type'  => 'password',
				'description'  => __( 'This is password field demo follow same structure for further use.', 'shortcodeplugin' ),
				'id'    => 's_password_demo',
				'value' => '',
				'class' => 's-password-class',
				'placeholder' => '',
			),
			array(
				'title' => __( 'Textarea Field Demo', 'shortcodeplugin' ),
				'type'  => 'textarea',
				'description'  => __( 'This is textarea field demo follow same structure for further use.', 'shortcodeplugin' ),
				'id'    => 's_textarea_demo',
				'value' => '',
				'class' => 's-textarea-class',
				'rows' => '5',
				'cols' => '10',
				'placeholder' => __( 'Textarea Demo', 'shortcodeplugin' ),
			),
			array(
				'title' => __( 'Select Field Demo', 'shortcodeplugin' ),
				'type'  => 'select',
				'description'  => __( 'This is select field demo follow same structure for further use.', 'shortcodeplugin' ),
				'id'    => 's_select_demo',
				'value' => '',
				'class' => 's-select-class',
				'placeholder' => __( 'Select Demo', 'shortcodeplugin' ),
				'options' => array(
					'INR' => __( 'Rs.', 'shortcodeplugin' ),
					'USD' => __( '$', 'shortcodeplugin' ),
				),
			),
			array(
				'title' => __( 'Multiselect Field Demo', 'shortcodeplugin' ),
				'type'  => 'multiselect',
				'description'  => __( 'This is multiselect field demo follow same structure for further use.', 'shortcodeplugin' ),
				'id'    => 's_multiselect_demo',
				'value' => '',
				'class' => 's-multiselect-class mwb-defaut-multiselect',
				'placeholder' => __( 'Multiselect Demo', 'shortcodeplugin' ),
				'options' => array(
					'INR' => __( 'Rs.', 'shortcodeplugin' ),
					'USD' => __( '$', 'shortcodeplugin' ),
				),
			),
			array(
				'title' => __( 'Checkbox Field Demo', 'shortcodeplugin' ),
				'type'  => 'checkbox',
				'description'  => __( 'This is checkbox field demo follow same structure for further use.', 'shortcodeplugin' ),
				'id'    => 's_checkbox_demo',
				'value' => '',
				'class' => 's-checkbox-class',
				'placeholder' => __( 'Checkbox Demo', 'shortcodeplugin' ),
			),

			array(
				'title' => __( 'Radio Field Demo', 'shortcodeplugin' ),
				'type'  => 'radio',
				'description'  => __( 'This is radio field demo follow same structure for further use.', 'shortcodeplugin' ),
				'id'    => 's_radio_demo',
				'value' => '',
				'class' => 's-radio-class',
				'placeholder' => __( 'Radio Demo', 'shortcodeplugin' ),
				'options' => array(
					'yes' => __( 'YES', 'shortcodeplugin' ),
					'no' => __( 'NO', 'shortcodeplugin' ),
				),
			),

			array(
				'type'  => 'button',
				'id'    => 's_button_demo',
				'button_text' => __( 'Button Demo', 'shortcodeplugin' ),
				'class' => 's-button-class',
			),
		);
		return $s_settings_general;
	}

	/**
	 * Function for register custom post type
	 *
	 * @return void
	 */
	public function product_custom_post_type() {
		$labels = array(
			'name'                  => __( 'Products', 'shortcodeplugin' ),
			'singular_name'         => __( 'Product', 'shortcodeplugin' ),
			'featured_image'        => __( 'Product Logo', 'shortcodeplugin' ),
			'set_featured_image'    => __( 'Set Product Logo', 'shortcodeplugin' ),
			'remove_featured_image' => __( 'Remove Product Logo', 'shortcodeplugin' ),
			'use_featured_image'    => __( 'Use Logo', 'shortcodeplugin' ),
			'add_new'               => __( 'Add New Product', 'shortcodeplugin' ),
			'add_new_item'          => __( 'Add New Product', 'shortcodeplugin' ),
			'archives'              => __( 'Product Directory ', 'shortcodeplugin' ),
		);
		$args   = array(
			'labels'       => $labels,
			'public'       => true,
			'show_in_rest' => true,
			'has_archive'  => 'products',
			'rewrite'      => array( 'slug' => 'product' ),
			'menu_icon'    => 'dashicons-products',
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
		);

		register_post_type( 'product', $args );
	}

	/**
	 * Function for register Pro Cat taxonomy.
	 *
	 * @return void
	 */
	public function pro_cat_register_taxonomy() {
		$labels = array(
			'name'          => __( 'Products Category', 'myfirstplugin' ),
			'singular_name' => __( 'Product Category', 'myfirstplugin' ),
		);
		$args   = array(
			'labels'       => $labels,
			'public'       => true,
			'hierarchical' => true,
			'show_in_rest' => true,
			'rewrite'      => true,

		);
		$post_types = array( 'product' );

		register_taxonomy( 'pro_cat', $post_types, $args );
	}
}
