<?php
namespace AMIMOTO_Dashboard\Views;
use AMIMOTO_Dashboard\Constants;
use AMIMOTO_Dashboard\WP\Environment;
use AMIMOTO_Dashboard\WP\Plugins;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly
}

class Plugin_C3 {
	private $env;
	private $plugins;
	public function __construct( ...$args ) {
		if ( $args && ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( $value instanceof Environment ) {
					$this->env = $value;
				} elseif ( $value instanceof Plugins ) {
					$this->plugins = $value;
				}
			}
		}
		if ( ! isset( $this->env ) ) {
			$this->env = new Environment();
		}
		if ( ! isset( $this->plugins ) ) {
			$this->plugins = new Plugins();
		}
		add_action( 'admin_menu', array( $this, 'add_c3_menu' ) );
		add_filter( 'c3_has_ec2_instance_role', array( $this, 'hide_aws_credentials' ) );
	}

	public function hide_aws_credentials() {
		$is_amimoto_managed    = $this->env->is_amimoto_managed();
		$has_ec2_instance_role = apply_filters( 'amimoto_has_ec2_instance_role', $is_amimoto_managed );
		return $has_ec2_instance_role;
	}

	public function add_c3_menu() {
		$is_amimoto_managed = $this->env->is_amimoto_managed();
		$text_domain        = Constants::text_domain();

		if ( ! $this->plugins->is_activated_c3() ) {
			return;
		}

		$menu_label = __( 'CloudFront', $text_domain );
		if ( $is_amimoto_managed ) {
			$menu_label = __( 'CDN Cache', $text_domain );
		}

		add_submenu_page(
			Constants::PANEL_ROOT,
			__( 'C3 Cloudfront Cache Controller', $text_domain ),
			$menu_label,
			'administrator',
			Constants::PANEL_C3,
			array( $this, 'render_html' )
		);
	}

	public function render_html() {
		$is_amimoto_managed = $this->env->is_amimoto_managed();
		echo "<div class='wrap' id='amimoto-dashboard'>";
		require_once( AMI_DASH_PATH . 'templates/Plugin_Header.php' );
		echo '<div class="amimoto-dash-main">';
		if ( ! $is_amimoto_managed ) {
			require_once( AMI_DASH_PATH . 'templates/C3/Plugin_Settings.php' );
			require_once( AMI_DASH_PATH . 'templates/C3/Invalidation_Form.php' );
			if ( $this->plugins->is_activated_ncc() ) {
				require_once( AMI_DASH_PATH . 'templates/NCC/Plugin_Settings.php' );
			}
		} else {
			require_once( AMI_DASH_PATH . 'templates/C3/Invalidation_Form.php' );
        }
		$additional_html = apply_filters( 'amimoto_c3_add_settings', '' );
		if ( isset( $additional_html ) ) {
			echo $additional_html;
		}
		echo '</div>';
		echo '</div>';
	}
}
