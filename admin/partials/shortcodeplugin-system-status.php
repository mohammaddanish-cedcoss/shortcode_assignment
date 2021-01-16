<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html for system status.
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
// Template for showing information about system status.
global $s_mwb_s_obj;
$s_default_status = $s_mwb_s_obj->mwb_s_plug_system_status();
$s_wordpress_details = is_array( $s_default_status['wp'] ) && ! empty( $s_default_status['wp'] ) ? $s_default_status['wp'] : array();
$s_php_details = is_array( $s_default_status['php'] ) && ! empty( $s_default_status['php'] ) ? $s_default_status['php'] : array();
?>
<div class="mwb-s-table-wrap">
	<div class="mwb-s-table-inner-container">
		<table class="mwb-s-table" id="mwb-s-wp">
			<thead>
				<tr>
					<th><?php esc_html_e( 'WP Variables', 'shortcodeplugin' ); ?></th>
					<th><?php esc_html_e( 'WP Values', 'shortcodeplugin' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( is_array( $s_wordpress_details ) && ! empty( $s_wordpress_details ) ) { ?>
					<?php foreach ( $s_wordpress_details as $wp_key => $wp_value ) { ?>
						<?php if ( isset( $wp_key ) && 'wp_users' != $wp_key ) { ?>
							<tr>
								<td><?php echo esc_html( $wp_key ); ?></td>
								<td><?php echo esc_html( $wp_value ); ?></td>
							</tr>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="mwb-s-table-inner-container">
		<table class="mwb-s-table" id="mwb-s-php">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Sysytem Variables', 'shortcodeplugin' ); ?></th>
					<th><?php esc_html_e( 'System Values', 'shortcodeplugin' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( is_array( $s_php_details ) && ! empty( $s_php_details ) ) { ?>
					<?php foreach ( $s_php_details as $php_key => $php_value ) { ?>
						<tr>
							<td><?php echo esc_html( $php_key ); ?></td>
							<td><?php echo esc_html( $php_value ); ?></td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
