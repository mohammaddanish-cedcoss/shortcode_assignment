<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Shortcodeplugin
 * @subpackage Shortcodeplugin/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}

global $s_mwb_s_obj;
$s_active_tab   = isset( $_GET['s_tab'] ) ? sanitize_key( $_GET['s_tab'] ) : 'shortcodeplugin-general';
$s_default_tabs = $s_mwb_s_obj->mwb_s_plug_default_tabs();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="mwb-s-main-wrapper">
	<div class="mwb-s-go-pro">
		<div class="mwb-s-go-pro-banner">
			<div class="mwb-s-inner-container">
				<div class="mwb-s-name-wrapper">
					<p><?php esc_html_e( 'shortcodeplugin', 'shortcodeplugin' ); ?></p></div>
					<div class="mwb-s-static-menu">
						<ul>
							<li>
								<a href="<?php echo esc_url( 'https://makewebbetter.com/contact-us/' ); ?>" target="_blank">
									<span class="dashicons dashicons-phone"></span>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( 'https://docs.makewebbetter.com/hubspot-woocommerce-integration/' ); ?>" target="_blank">
									<span class="dashicons dashicons-media-document"></span>
								</a>
							</li>
							<?php $s_plugin_pro_link = apply_filters( 's_pro_plugin_link', '' ); ?>
							<?php if ( isset( $s_plugin_pro_link ) && '' != $s_plugin_pro_link ) { ?>
								<li class="mwb-s-main-menu-button">
									<a id="mwb-s-go-pro-link" href="<?php echo esc_url( $s_plugin_pro_link ); ?>" class="" title="" target="_blank"><?php esc_html_e( 'GO PRO NOW', 'shortcodeplugin' ); ?></a>
								</li>
							<?php } else { ?>
								<li class="mwb-s-main-menu-button">
									<a id="mwb-s-go-pro-link" href="#" class="" title=""><?php esc_html_e( 'GO PRO NOW', 'shortcodeplugin' ); ?></a>
								</li>
							<?php } ?>
							<?php $s_plugin_pro = apply_filters( 's_pro_plugin_purcahsed', 'no' ); ?>
							<?php if ( isset( $s_plugin_pro ) && 'yes' == $s_plugin_pro ) { ?>
								<li>
									<a id="mwb-s-skype-link" href="<?php echo esc_url( 'https://join.skype.com/invite/IKVeNkLHebpC' ); ?>" target="_blank">
										<img src="<?php echo esc_url( SHORTCODEPLUGIN_DIR_URL . 'admin/images/skype_logo.png' ); ?>" style="height: 15px;width: 15px;" ><?php esc_html_e( 'Chat Now', 'shortcodeplugin' ); ?>
									</a>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="mwb-s-main-template">
			<div class="mwb-s-body-template">
				<div class="mwb-s-navigator-template">
					<div class="mwb-s-navigations">
						<?php
						if ( is_array( $s_default_tabs ) && ! empty( $s_default_tabs ) ) {

							foreach ( $s_default_tabs as $s_tab_key => $s_default_tabs ) {

								$s_tab_classes = 'mwb-s-nav-tab ';

								if ( ! empty( $s_active_tab ) && $s_active_tab === $s_tab_key ) {
									$s_tab_classes .= 's-nav-tab-active';
								}
								?>
								
								<div class="mwb-s-tabs">
									<a class="<?php echo esc_attr( $s_tab_classes ); ?>" id="<?php echo esc_attr( $s_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=shortcodeplugin_menu' ) . '&s_tab=' . esc_attr( $s_tab_key ) ); ?>"><?php echo esc_html( $s_default_tabs['title'] ); ?></a>
								</div>

								<?php
							}
						}
						?>
					</div>
				</div>

				<div class="mwb-s-content-template">
					<div class="mwb-s-content-container">
						<?php
							// if submenu is directly clicked on woocommerce.
						if ( empty( $s_active_tab ) ) {

							$s_active_tab = 'mwb_s_plug_general';
						}

							// look for the path based on the tab id in the admin templates.
						$s_tab_content_path = 'admin/partials/' . $s_active_tab . '.php';

						$s_mwb_s_obj->mwb_s_plug_load_template( $s_tab_content_path );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
