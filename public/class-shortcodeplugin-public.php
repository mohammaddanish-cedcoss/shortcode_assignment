<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Shortcodeplugin
 * @subpackage Shortcodeplugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * namespace shortcodeplugin_public.
 *
 * @package    Shortcodeplugin
 * @subpackage Shortcodeplugin/public
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Shortcodeplugin_Public {

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
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function s_public_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, SHORTCODEPLUGIN_DIR_URL . 'public/css/shortcodeplugin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function s_public_enqueue_scripts() {

		wp_register_script( $this->plugin_name, SHORTCODEPLUGIN_DIR_URL . 'public/js/shortcodeplugin-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 's_public_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name );
	}

	public function myshortcode() {
		add_shortcode( 'products', array( $this, 'products_shortcode' ) );
	}

	public function products_shortcode( $atts ) {
		if ( ! empty( $atts ) ) {
			$cat  = $atts['cat'];
			$show = $atts['show'];

			$query = new WP_Query( array(
				'post_type'      => 'product',
				'posts_per_page' => $show,
				'tax_query' => array(
					array(
						'taxonomy' => 'pro_cat',
						'field'    => 'slug',
						'terms'    => $cat
						)
					)
				)
			);
			if ( $query-> have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post(); ?>
					<div>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<?php the_post_thumbnail(); ?>
						<p><?php the_content(); ?></p>
					</div>
					<br>
				<?php
				}
			}
			wp_reset_postdata();
		} else {
			print_r( 'Not atts' );
		}

	}

	/**
	* Redirect away from the single view for my_custom_post_type only.
	* If the user is not logged in.
	*/
	public function single_view_adjustment( $template ) {
		global $post;
		if ( $post->post_type == 'product' && is_single() ) :
			// Search for the new template file either within the parent
			// or child themes.
			$new_template = plugin_dir_path( __FILE__ ) . 'my-template.php';
			if ( '' != $new_template ) {
				return $new_template;
			} 
		endif;
		return $template; // if the template doesn't exist, Wordpress will load the default template instead.
	}
}
