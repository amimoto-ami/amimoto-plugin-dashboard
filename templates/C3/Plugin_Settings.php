<?php
namespace AMIMOTO_Dashboard\Templates\C3;
use AMIMOTO_Dashboard\Constants;
use AMIMOTO_Dashboard\C3_Service;
$text_domain = Constants::text_domain();

if ( ! apply_filters( 'amimoto_show_c3_setting_form', true ) ) {
	return;
}
$c3                    = new C3_Service();
$has_ec2_instance_role = apply_filters( 'amimoto_has_ec2_instance_role', false );
$c3_settings           = $c3->get_plugin_options();
if ( ! isset( $c3_settings['distribution_id'] ) || ! $c3_settings['distribution_id'] ) {
	$c3_settings['distribution_id'] = '';
}
if ( ! isset( $c3_settings['access_key'] ) || ! $c3_settings['access_key'] ) {
	$c3_settings['access_key'] = '';
}
if ( ! isset( $c3_settings['secret_key'] ) || ! $c3_settings['secret_key'] ) {
	$c3_settings['secret_key'] = '';
}
$c3_settings = apply_filters( 'c3_settings', $c3_settings );
if ( ! isset( $c3_settings['access_key'] ) && ! isset( $c3_settings['secret_key'] ) ) {
	$has_ec2_instance_role = true;
}

$distribution_id = $c3_settings['distribution_id'];
if ( defined( 'AMIMOTO_CDN_ID' ) ) {
	$distribution_id = AMIMOTO_CDN_ID;
}

?>
<form method='post' action=''>
	<table class='wp-list-table widefat plugins' style="margin-bottom: 2rem;">
		<thead>
			<tr>
				<th colspan='2'>
					<h2><?php _e( 'CloudFront Connection Settings', $text_domain ); ?></h2>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>
					<?php _e( 'CloudFront Distribution ID', $text_domain ); ?>
				</th>
				<td>
					<input
						type='text'
						class='regular-text code'
						name='c3_settings[distribution_id]'
						value='<?php echo esc_attr( $distribution_id ); ?>'
						<?php echo defined( 'AMIMOTO_CDN_ID' ) ? 'dieabled' : null; ?>
					/>
				</td>
			</tr>
			<?php if ( ! $has_ec2_instance_role ) { ?>
				<tr>
					<th><?php _e( 'AWS Access Key', $text_domain ); ?></th>
					<td>
						<input
							type='text'
							class='regular-text code'
							name='c3_settings[access_key]'
							value='<?php echo esc_attr( $c3_settings['access_key'] ); ?>'
						/>
					</td>
				</tr>
				<tr>
					<th><?php _e( 'AWS Secret Key', $text_domain ); ?></th>
					<td>
						<input
							type='password'
							class='regular-text code'
							name='c3_settings[secret_key]'
							value='<?php esc_attr( $c3_settings['secret_key'] ); ?>'
						/>
					</td>
				</tr>
				<?php
			}//end if
			?>
			<tr>
				<td colspan='2'>
					<?php echo wp_nonce_field( Constants::CLOUDFRONT_SETTINGS, Constants::CLOUDFRONT_SETTINGS, true, false ); ?>
					<?php submit_button( __( 'Update CloudFront Settings', $text_domain ) ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</form>
