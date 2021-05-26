<?php
namespace AMIMOTO_Dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly
}

class Constants {
	// Panel key
	const PANEL_ROOT = 'amimoto_dash_root';
	const PANEL_C3   = 'amimoto_dash_c3';
	const PANEL_NCC  = 'nginx-champuru';

	// Action key
	const PLUGIN_SETTING          = 'amimoto_setting';
	const PLUGIN_ACTIVATION       = 'amimoto_activation';
	const PLUGIN_DEACTIVATION     = 'amimoto_deactivation';
	const CLOUDFRONT_SETTINGS     = 'amimoto_cf_setting';
	const CLOUDFRONT_INVALIDATION = 'amimoto_cf_invalidation';
	const CLOUDFRONT_UPDATE_NCC   = 'amimoto_cf_ncc_setting';

	const AMIMOTO_PLUGINS = array(
		'C3 Cloudfront Cache Controller'   => 'c3-cloudfront-clear-cache/c3-cloudfront-clear-cache.php',
		'Nginx Cache Controller on GitHub' => 'nginx-cache-controller/nginx-champuru.php',
		'Nginx Cache Controller on WP.org' => 'nginx-champuru/nginx-champuru.php',
	);
	/**
	 * Get Plugin text_domain
	 *
	 * @return string
	 * @since 4.0.0
	 */
	public static function text_domain() {
		static $text_domain;

		if ( ! $text_domain ) {
			$data        = get_file_data( AMI_DASH_ROOT, array( 'text_domain' => 'Text Domain' ) );
			$text_domain = $data['text_domain'];
		}
		return $text_domain;
	}

}
