<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
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
global $s_mwb_s_obj;
$s_genaral_settings = apply_filters( 's_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<div class="s-secion-wrap">
	<table class="form-table s-settings-table">
		<?php
			$s_general_html = $s_mwb_s_obj->mwb_s_plug_generate_html( $s_genaral_settings );
			echo esc_html( $s_general_html );
		?>
	</table>
</div>
