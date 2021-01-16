<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the welcome html.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Shortcodeplugin
 * @subpackage Shortcodeplugin/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="mwb-s-main-wrapper">
	<div class="mwb-s-go-pro">
		<div class="mwb-s-go-pro-banner">
			<div class="mwb-s-inner-container">
				<div class="mwb-s-name-wrapper" id="mwb-s-page-header">
					<h3><?php esc_html_e( 'Welcome To MakeWebBetter', 'shortcodeplugin' ); ?></h4>
					</div>
				</div>
			</div>
			<div class="mwb-s-inner-logo-container">
				<div class="mwb-s-main-logo">
					<img src="<?php echo esc_url( SHORTCODEPLUGIN_DIR_URL . 'admin/images/logo.png' ); ?>">
					<h2><?php esc_html_e( 'We make the customer experience better', 'shortcodeplugin' ); ?></h2>
					<h3><?php esc_html_e( 'Being best at something feels great. Every Business desires a smooth buyer’s journey, WE ARE BEST AT IT.', 'shortcodeplugin' ); ?></h3>
				</div>
				<div class="mwb-s-active-plugins-list">
					<?php
					$mwb_s_all_plugins = get_option( 'mwb_all_plugins_active', false );
					if ( is_array( $mwb_s_all_plugins ) && ! empty( $mwb_s_all_plugins ) ) {
						?>
						<table class="mwb-s-table">
							<thead>
								<tr class="mwb-plugins-head-row">
									<th><?php esc_html_e( 'Plugin Name', 'shortcodeplugin' ); ?></th>
									<th><?php esc_html_e( 'Active Status', 'shortcodeplugin' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if ( is_array( $mwb_s_all_plugins ) && ! empty( $mwb_s_all_plugins ) ) { ?>
									<?php foreach ( $mwb_s_all_plugins as $s_plugin_key => $s_plugin_value ) { ?>
										<tr class="mwb-plugins-row">
											<td><?php echo esc_html( $s_plugin_value['plugin_name'] ); ?></td>
											<?php if ( isset( $s_plugin_value['active'] ) && '1' != $s_plugin_value['active'] ) { ?>
												<td><?php esc_html_e( 'NO', 'shortcodeplugin' ); ?></td>
											<?php } else { ?>
												<td><?php esc_html_e( 'YES', 'shortcodeplugin' ); ?></td>
											<?php } ?>
										</tr>
									<?php } ?>
								<?php } ?>
							</tbody>
						</table>
						<?php
					}
					?>
				</div>
			</div>
		</div>
