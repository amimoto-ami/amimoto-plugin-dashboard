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

		$url = 'https://amimoto-plugins.s3.amazonaws.com/amimoto-plugins.json';
		$content = file_get_contents($url);
		$json = json_decode($content, true);

		foreach($json as $item => $plugin) {
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
	private function _amimoto_plugin_list_template(
		$title = '',
		$thumbnail = '',
		$slug = '',
		$short_description = ''
	) {

		// Load WP Core Thickbox Scripts
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');

		$wp_plugin_html = '
			<div class="plugin-card plugin-card-akismet">
				<div class="plugin-card-top">
					<div class="name column-name">
						<h3>
						<a href="'.get_admin_url('/').'plugin-install.php?tab=plugin-information&plugin='.$slug.'" class="thickbox open-plugin-details-modal">
							'.$title.'
							<img src="'.$thumbnail.'" class="plugin-icon" alt="">
						</a>
					</h3>
					</div>
					<div class="action-links">
						<ul class="plugin-action-buttons">
							<li>
								<a class="install-now button" data-slug="'.$slug.'" href="'.get_admin_url('/').'update.php?action=install-plugin&amp;plugin=bbpress&amp;_wpnonce=d55ae37dab" aria-label="Install bbPress 2.5.14 now" data-name="bbPress 2.5.14">Install Now</a>
							</li>
							<li>
								<a href="'.get_admin_url('/').'plugin-install.php?tab=plugin-information&amp;plugin='.$slug.'&amp;TB_iframe=true&amp;width=600&amp;height=550" class="thickbox open-plugin-details-modal" aria-label="More information about bbPress 2.5.14" data-title="bbPress 2.5.14">More Details</a>
							</li>
						</ul>
					</div>
					<div class="desc column-description">
						<p>'.$short_description.'</p>
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

		$html .= '<header><h2>Installed</h2></header>';
		$html .= '<div class="wp-list-table">';

		foreach ($amimoto_plugins as $p) {
			if (in_array($p['name'], $amimoto_installed)) {
				$html .= $this->_amimoto_plugin_list_template(
					$p['name'],
					$p['thumbnail'],
					$p['slug'],
					$p['short_description']
				);
			}
		}

		$html .= '</div>';

		$html .= '<header><h2>Installed</h2></header>';
		$html .= '<div class="wp-list-table">';

		foreach ($amimoto_plugins as $p) {
			if (in_array($p['name'], $amimoto_available)) {
				$html .= $this->_amimoto_plugin_list_template(
					$p['name'],
					$p['thumbnail'],
					$p['slug'],
					$p['short_description']
				);
			}
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 *  Get plugin list that uninstalled amimoto plugins
	 *
	 * @access private
	 * @return string(html)
	 * @since 0.0.1
	 */
	private function _get_uninstalled_amimoto_plugin_html() {
		$html  = '';
		foreach ( $this->amimoto_uninstalled_plugins as $plugin_name => $plugin_url ) {
			if ( 'Nginx Cache Controller on WP.org' == $plugin_name ) {
				if ( $this->is_exists_ncc() ) {
					continue;
				}
				$plugin_name = 'Nginx Cache Controller';
			}
			$plugin_install_url = "./plugin-install.php?tab=search&type=term&s=". urlencode( $plugin_name );
			$description = $this->_get_amimoto_plugin_description( $plugin_name );
			$for_use = $this->_get_amimoto_plugin_for_use( $plugin_name );
			$html .= "<tr class='inactive'><td>";
			$html .= "<h2>{$plugin_name}</h2>";
			$html .= '<dl><dt><b>'. __( 'For use:', self::$text_domain ). "</b></dt><dd>{$for_use}</dd>";
			$html .= '<dl><dt><b>'. __( 'Plugin Description:', self::$text_domain ). "</b></dt><dd>{$description}</dd>";
			$html .= '</dl>';
			$html .= "<a class='install-now button' href='{$plugin_install_url}' aria-label='Install {$plugin_name} now' data-name='{$plugin_name}'>Install Now</a>";
			$html .= '</td></tr>';
		}
		return $html;
	}

	/**
	 *  Get amimoto plugin description
	 *
	 * @access private
	 * @param (string) $plugin_name
	 * @return string
	 * @since 0.0.1
	 */
	private function _get_amimoto_plugin_description( $plugin_name ) {
		switch ( $plugin_name ) {
			case 'Nginx Cache Controller':
				$description = __( 'Provides some functions of controlling Nginx proxy server cache.', self::$text_domain );
				break;

			case 'Nephila clavata':
				$description = __( 'Allows you to mirror your WordPress media uploads over to Amazon S3 for storage and delivery.', self::$text_domain );
				break;

			case 'C3 Cloudfront Cache Controller':
				$description = __( "Controlle CloudFront's CDN server cache.", self::$text_domain );
				break;

			default:
				$description = '';
				break;
		}
		return $description;
	}

	/**
	 *  Get amimoto plugin description for use
	 *
	 * @access private
	 * @param (string) $plugin_name
	 * @return string
	 * @since 0.0.1
	 */
	private function _get_amimoto_plugin_for_use( $plugin_name ) {
		switch ( $plugin_name ) {
			case 'nginxchampuru':
			case 'Nginx Cache Controller':
				$description  = __( 'Nginx Reverse Proxy Cache', self::$text_domain );
				break;

			case 'nephila-clavata':
			case 'Nephila clavata':
				$description  = __( 'Amazon S3', self::$text_domain );
				break;

			case 'c3-cloudfront-clear-cache':
			case 'C3 Cloudfront Cache Controller':
				$description  = __( 'Amazon CloudFront', self::$text_domain );
				break;

			default:
				$description = '';
				break;
		}
		return $description;
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
