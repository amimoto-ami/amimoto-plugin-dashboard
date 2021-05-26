<?php
namespace AMIMOTO_Dashboard;
use AMIMOTO_Dashboard\Constants;
use AMIMOTO_Dashboard\Views\Menus;
use AMIMOTO_Dashboard\Views\Plugin_C3;
use AMIMOTO_Dashboard\Views\Plugin_NCC;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly
}

class Views {
	public function __construct() {
		new Menus();
		new Plugin_C3();
		new Plugin_NCC();
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_theme_style' ) );
		add_action( 'admin_init', array( $this, 'manage_redirection' ) );
	}

	public function manage_redirection() {
		if ( empty( $_POST ) ) {
			return;
		}

		// For Nginx Cache Controller
		if ( isset( $_POST['expires'] ) && $_POST['expires'] ) {
			wp_redirect( admin_url( 'admin.php?page=nginx-champuru&message=true' ) );
		}
		if ( isset( $_POST['redirect_page'] ) && $_POST['redirect_page'] ) {
			wp_safe_redirect( menu_page_url( $_POST['redirect_page'], false ) );
		}
	}

	public function admin_theme_style() {
		wp_enqueue_style( 'amimoto-admin-style', path_join( AMI_DASH_URL, 'assets/admin.css' ), array(), '2016062301' );
	}
}
