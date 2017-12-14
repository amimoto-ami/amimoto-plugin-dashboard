<?php
/**
 * Amimoto_Dash_Admin Class file
 *
 * @author hideokamoto <hide.okamoto@digitalcube.jp>
 * @package Amimoto-dashboard
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Amimoto Plugin Dashboard admin page scripts
 *
 * @class Amimoto_Dash_Admin
 * @since 0.0.1
 */
class Amimoto_Dash_Admin extends Amimoto_Dash_Component {
	private static $instance;
	private static $text_domain;
	public $amimoto_plugins = array();
	public $amimoto_uninstalled_plugins = array();

	private function __construct() {
		self::$text_domain = Amimoto_Dash_Base::text_domain();
		$this->amimoto_plugins = $this->get_amimoto_plugin_file_list();
	}

	/**
	 * Get Instance Class
	 *
	 * @return Amimoto_Dash_Admin
	 * @since 0.0.1
	 * @access public
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}


	/**
	 *  Get Activated AMIMOTO Plugin list
	 *
	 *  Get activated plugin list that works for AMIMOTO AMI.
	 *
	 * @access private
	 * @param none
	 * @return array
	 * @since 0.0.1
	 */
	private function _get_activated_plugin_list() {
		$active_plugin_urls = get_option( 'active_plugins' );
		$plugins = array();
		foreach ( $active_plugin_urls as $plugin_url ) {
			if ( ! array_search( $plugin_url , $this->amimoto_plugins ) ) {
				continue;
			}
			$plugins[] = $plugin_url;
		}
		return $plugins;
	}

	/**
	 *  AMIMOTO Plugin API
	 *
	 *  Makeshift AMIMOTO Plugin API with a JSON Backup
	 *
	 * @access private
	 * @param none
	 * @return array
	 * @since 0.0.1
	 */
	private function _get_amimoto_plugin_api() {
		$url = 'https://amimoto-plugins.s3.amazonaws.com/amimoto-plugins.json';
		$handle = curl_init($url);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($handle);
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		if($httpCode != 200) {
			$url = WP_PLUGIN_DIR . '/amimoto-plugin-dashboard/assets/amimoto-plugins.json';
		}

		curl_close($handle);


		$content = file_get_contents($url);
		$json = json_decode($content, true);

		return $json;
	}

	/**
	 *  Get Exists AMIMOTO Plugin list
	 *
	 *  Get exists plugin list that works for AMIMOTO AMI.
	 *
	 * @access private
	 * @param none
	 * @return array
	 * @since 0.0.1
	 */
	private function _get_amimoto_plugin_list() {
		$plugins = array();
		$amimoto_plugins = $this->_get_amimoto_plugin_api();

		foreach($amimoto_plugins as $item => $plugin) {
			$plugins[] = $plugin;
		}

		return $plugins;
	}


	/**
	 *  AMIMOTO Plugin List
	 *
	 *  Build list of AMIMOTO Plugins
	 *
	 * @access private
	 * @param none
	 * @return array
	 * @since 0.0.1
	 */
	private function _amimoto_plugin_list_template( $args = array(
		'slug' => null,
		'title' => null,
		'thumbnail' => null,
		'desc' => null
	)) {

		// Load WP Core Thickbox Scripts
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');

		$details_link = self_admin_url(
			'plugin-install.php?tab=plugin-information&amp;plugin=' . $args['slug'] .
			'&amp;TB_iframe=true&amp;width=600&amp;height=550'
		);

		$actions_link = self_admin_url(
			'update.php?action=install-plugin&plugin=c3-cloudfront-clear-cache&_wpnonce=19d34c3b3c'
		);

		// echo '<pre>' . print_r(get_plugins()) . '</pre>';
		// echo '<pre>' . print_r(is_plugin_active('jetpack/jetpack.php')) . '</pre>';

		$wp_plugin_html = '
			<div class="plugin-card plugin-card-akismet">
				<div class="plugin-card-top">
					<div class="name column-name">
						<h3>
						<a href="'.esc_url( $details_link ).'" class="thickbox open-plugin-details-modal">
							'.$args['title'].'
							<img src="'.$args['thumbnail'].'" class="plugin-icon" alt="">
						</a>
					</h3>
					</div>
					<div class="action-links">
						<ul class="plugin-action-buttons">
							<li>
								<a class="install-now button" data-slug="'.$args['slug'].'" href="'.$actions_link.'" aria-label="Install bbPress 2.5.14 now" data-name="bbPress 2.5.14">Install Now</a>
							</li>
							<li>
								<a href="'.get_admin_url('/').'plugin-install.php?tab=plugin-information&amp;plugin='.$args['slug'].'&amp;TB_iframe=true&amp;width=600&amp;height=550" class="thickbox open-plugin-details-modal" aria-label="More information about bbPress 2.5.14" data-title="bbPress 2.5.14">More Details</a>
							</li>
						</ul>
					</div>
					<div class="desc column-description">
						<p>'.$args['desc'].'</p>
					</div>
				</div>
			</div>
			';

		return $wp_plugin_html;
	}

	/**
	 *  Create AMIMOTO Plugin List
	 *
	 * @access private
	 * @param none
	 * @return string(HTML)
	 * @since 0.0.1
	 */
	private function _get_amimoto_plugin_html() {
		$html = '';
		$plugin_list_template = $this->_amimoto_plugin_list_template();
		$amimoto_plugins = $this->_get_amimoto_plugin_list();
		$available_plugins_name = array_column($amimoto_plugins, 'name');
		$installed_plugins_name = array_column(get_plugins(), 'Name');

		// Show Plugins available but not installed
		$amimoto_available = array_diff( $available_plugins_name, $installed_plugins_name);
		$amimoto_installed = array_diff( $available_plugins_name, $amimoto_available);

		$html .= '
		<form id="plugin-filter" method="post">
			<div class="wp-list-table widefat plugin-install">
				<h2 class="screen-reader-text">AMIMOTO Plugins</h2>
				<div id="the-list">';

		$html .= '<div class="wp-list-table">';

		foreach ($amimoto_plugins as $p) {
			$args = array(
				'title' => $p['name'],
				'desc' => $p['short_description'],
				'thumbnail' => $p['thumbnail'],
				'slug' => $p['slug']
			);
			$html .= $this->_amimoto_plugin_list_template($args);
		}

		$html .= '</div>';

		$html .= '</div></div></form>';

		return $html;
	}

	/**
	 *  Get form action type
	 *
	 * @access private
	 * @param (string) $plugin_type
	 * @return string
	 * @since 0.0.1
	 */
	private function _get_action_type( $plugin_type ) {
		switch ( $plugin_type ) {
			case 'c3-cloudfront-clear-cache':
				$action = self::PANEL_C3;
				break;

			case 'nephila-clavata':
				$action = self::PANEL_S3;
				break;

			case 'nginxchampuru':
				$action = self::PANEL_NCC;
				break;

			case 'support':
				$action = self::PANEL_SUPPORT;
				break;

			default:
				$action = '';
				break;
		}
		return $action;
	}

	/**
	 *  Show admin page html
	 *
	 * @access public
	 * @param none
	 * @return none
	 * @since 0.0.1
	 */
	public function init_panel() {
		$this->show_panel_html();
	}

	/**
	 *  Get admin page html content
	 *
	 * @access public
	 * @param none
	 * @return string(HTML)
	 * @since 0.0.1
	 */
	public function get_content_html() {
		$html = '';
		$html .= $this->_get_amimoto_plugin_html();
		return $html;
	}
}
