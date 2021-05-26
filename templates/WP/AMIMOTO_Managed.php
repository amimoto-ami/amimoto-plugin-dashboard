<?php
namespace AMIMOTO_Dashboard\Templates;
use AMIMOTO_Dashboard\Constants;
use AMIMOTO_Dashboard\WP\Environment;

$text_domain = Constants::text_domain();
$env         = new Environment();
if ( ! $env->is_amimoto_managed() ) {
	return;
}
?>
<table class='wp-list-table widefat plugins'>
	<thead>
		<tr>
			<th colspan='2'>
				<h2><?php _e( 'AMIMOTO Cache Control', $text_domain ); ?></h2>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>
				<b><?php _e( 'Flush All CDN Cache', $text_domain ); ?></b>
				<p></p>
			</th>
			<td>
				<form method='post' action=''>
					<input type='hidden' name='invalidation_target' value='all' />
					<?php echo wp_nonce_field( Constants::CLOUDFRONT_INVALIDATION, Constants::CLOUDFRONT_INVALIDATION, true, false ); ?>
					<?php submit_button( __( 'Flush All CDN Cache', $text_domain ) ); ?>
				</form>
			</td>
		</tr>
		<tr>
			<th>
				<b><?php _e( 'Reset Nginx Cache Setting', $text_domain ); ?></b>
				<p><?php _e( 'All Nginx Cache Expires change 30sec.', $text_domain ); ?></p></th>
				<td>
				<form method='post' action=''>
					<input type='hidden' name='invalidation_target' value='all' />
					<?php echo wp_nonce_field( Constants::CLOUDFRONT_UPDATE_NCC, Constants::CLOUDFRONT_UPDATE_NCC, true, false ); ?>
					<?php submit_button( __( 'Reset Nginx Cache Setting', $text_domain ) ); ?>
				</form>
			</td>
		</tr>
	</tbody>
</table>
