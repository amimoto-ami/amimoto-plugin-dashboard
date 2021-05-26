<?php
namespace AMIMOTO_Dashboard\Templates\NCC;
use AMIMOTO_Dashboard\Constants;
use AMIMOTO_Dashboard\C3_Service;
$text_domain = Constants::text_domain();

if ( ! apply_filters( 'amimoto_show_ncc_setting_form', true ) ) {
	return;
}
?>
<form method='post' action=''>
	<table class='wp-list-table widefat plugins'>
		<thead>
			<tr>
				<th colspan='2'><h2><?php _e( 'Nginx Cache Settings', $text_domain ); ?></h2></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>
					<b><?php _e( 'Change Nginx Cache Expires Shorten', $text_domain ); ?></b>
					<p><?php _e( 'All Nginx Cache Expires change 30sec.', $text_domain ); ?></p>
				</th>
				<td>
					<input type='hidden' name='invalidation_target' value='all' />
					<?php echo wp_nonce_field( Constants::CLOUDFRONT_UPDATE_NCC, Constants::CLOUDFRONT_UPDATE_NCC, true, false ); ?>
					<?php submit_button( __( 'Change Expires', $text_domain ) ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</form>
