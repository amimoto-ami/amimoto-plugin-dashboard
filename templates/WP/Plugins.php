<?php
namespace AMIMOTO_Dashboard\Templates\WP;
use AMIMOTO_Dashboard\Constants;
use AMIMOTO_Dashboard\C3_Service;
use AMIMOTO_Dashboard\WP\Plugins;
$text_domain = Constants::text_domain();

if ( ! apply_filters( 'amimoto_show_plugin_lists', true ) ) {
	return;
}

$plugins             = new Plugins();
$amimoto_plugins     = $plugins->list_amimoto_plugins();
$activated_plugins   = $plugins->get_activated_plugin_list();
$uninstalled_plugins = $plugins->list_uninstalled_plugins();
$redirect_page       = Constants::PANEL_ROOT;
?>

<table class='wp-list-table widefat plugins'>
	<thead>
		<tr>
			<th colspan='2'>
				<h2><?php _e( 'AMIMOTO support plugins', $text_domain ); ?></h2>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ( $amimoto_plugins as $plugin_url => $plugin ) {
			$plugin_type = $plugin['TextDomain'];
			if ( array_search( $plugin_url, $activated_plugins ) !== false ) {
				$stat     = 'active';
				$btn_text = __( 'Deactivate Plugin', $text_domain );
				$nonce    = Constants::PLUGIN_DEACTIVATION;
			} else {
				$stat     = 'inactive';
				$btn_text = __( 'Activate Plugin', $text_domain );
				$nonce    = Constants::PLUGIN_ACTIVATION;
			}
			$for_use = $plugins->get_amimoto_plugin_for_use( $plugin['TextDomain'] );
			?>
			<tr class="<?php echo esc_attr( $stat ); ?>">
				<td>
					<h2><?php echo esc_attr( $plugin['Name'] ); ?></h2>
					<dl>
						<dt><b><?php _e( 'For use:', $text_domain ); ?></b></dt>
						<dd><?php echo esc_attr( $for_use ); ?></dd>
						<dt><b><?php _e( 'Plugin Description:', $text_domain ); ?></b></dt>
						<dd><?php echo esc_attr( $plugin['Description'] ); ?></dd>
						<form method='post' action='' class='btn'>
							<?php submit_button( $btn_text, 'primary large' ); ?>
							<?php echo wp_nonce_field( $nonce, $nonce, true, false ); ?>
							<input type='hidden' name='plugin_type' value="<?php echo esc_attr( $plugin_type ); ?>" />
							<input type='hidden' name='redirect_page' value="<?php echo esc_attr( $redirect_page ); ?>" />
						</form>
						<?php
						if ( 'active' === $stat ) {
							$action = $plugins->get_action_type( $plugin_type );
							?>
							<form method='post' action='./admin.php?page=<?php echo esc_attr( $action ); ?>' class='btn'>
								<?php submit_button( __( 'Setting Plugin', $text_domain ), 'primary large' ); ?>
								<?php echo wp_nonce_field( Constants::PLUGIN_SETTING, Constants::PLUGIN_SETTING, true, false ); ?>
								<input type='hidden' name='plugin_type' value="<?php echo esc_attr( $plugin_type ); ?>" />
							</form>
						<?php } ?>
					</dl>
				</td>
			</tr>
			<?php
		}//end foreach
		?>
		<?php
		foreach ( $uninstalled_plugins as $plugin_name => $plugin_url ) {
			if ( 'Nginx Cache Controller on WP.org' == $plugin_name ) {
				if ( $plugins->is_exists_ncc() ) {
					continue;
				}
				$plugin_name = 'Nginx Cache Controller';
			}
			$plugin_install_url = './plugin-install.php?tab=search&type=term&s=' . urlencode( $plugin_name );
			$description        = $plugins->get_amimoto_plugin_description( $plugin_name );
			$for_use            = $plugins->get_amimoto_plugin_for_use( $plugin_name );
			?>
			<tr class='inactive'>
				<td>
					<h2><?php echo esc_attr( $plugin_name ); ?></h2>
					<dl>
						<dt><b><?php _e( 'For use:', $text_domain ); ?></b></dt>
						<dd><?php echo esc_attr( $for_use ); ?></dd>
						<dl><dt><b><?php _e( 'Plugin Description:', $text_domain ); ?></b></dt>
						<dd><?php echo esc_attr( $description ); ?></dd>
					</dl>
					<a
						class='install-now button'
						href='<?php echo esc_attr( $plugin_install_url ); ?>'
						aria-label='Install <?php echo esc_attr( $plugin_name ); ?> now'
						data-name='<?php echo esc_attr( $plugin_name ); ?>'
					>Install Now</a>
				</td>
			</tr>
			<?php
		}//end foreach
		?>
	</tbody>
</table>
