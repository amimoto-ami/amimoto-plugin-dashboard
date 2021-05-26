<?php
namespace AMIMOTO_Dashboard\Templates\C3;
use AMIMOTO_Dashboard\Constants;
$text_domain = Constants::text_domain();

if ( ! apply_filters( 'amimoto_show_invalidation_form', true ) ) {
	return;
}
?>
<form method='post' action=''>
	<table class='wp-list-table widefat plugins' style="margin-bottom: 2rem;">
		<thead>
			<tr>
				<th colspan='2'>
					<h2><?php _e( 'CloudFront Cache Control', $text_domain ); ?></h2>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>
					<?php _e( 'Flush All Cache', $text_domain ); ?></th>
				<td>
				<input type='hidden' name='invalidation_target' value='all' />
					<?php echo wp_nonce_field( Constants::CLOUDFRONT_INVALIDATION, Constants::CLOUDFRONT_INVALIDATION, true, false ); ?>
					<?php submit_button( __( 'Flush All Cache', $text_domain ) ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<hr/>
