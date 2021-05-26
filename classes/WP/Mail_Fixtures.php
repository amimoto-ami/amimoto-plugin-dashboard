<?php
/**
 * Patch code for AMIMOTO AMI
 *
 * @author hideokamoto <hide.okamoto@digitalcube.jp>
 * @package Amimoto-dashboard
 * @since 0.3.0
 */

 namespace AMIMOTO_Dashboard\WP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Mail_Fixtures {
	function __construct() {
		add_filter( 'wp_mail_from', array( $this, 'patch_mail_address' ) );
	}

	/**
	 * Replace email address if use default.conf on Nginx
	 *
	 * @since 0.3.0
	 * @access public
	 * @param string $original_email_address
	 * @return string $original_email_address
	 **/
	public function patch_mail_address( $original_email_address, $server = null ) {
		if ( ! isset( $server ) ) {
			$server = $_SERVER;
		}
		if ( '_' === $server['SERVER_NAME'] ) {
			$original_email_address = 'wordpress@' . parse_url( get_home_url( get_current_blog_id() ), PHP_URL_HOST );
		}
		return apply_filters( 'amimoto_patch_mailaddress', $original_email_address );
	}
}
