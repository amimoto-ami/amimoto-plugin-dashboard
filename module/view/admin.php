<?php
/**
 * Amimoto_Dash_Admin Class file
 *
 * @author hideokamoto <hide.okamoto@digitalcube.jp>
 * @package Amimoto-plugin-dashboard
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
		foreach ( $this->amimoto_plugins as $plugin_name => $plugin_url ) {
			$plugin_file_path = path_join( ABSPATH . 'wp-content/plugins', $plugin_url );
			if ( ! file_exists( $plugin_file_path ) ) {
				if ( 'Nginx Cache Controller on GitHub' != $plugin_name ) {
					$this->amimoto_uninstalled_plugins[ $plugin_name ] = $plugin_url;
				}
				unset( $this->amimoto_plugins[ $plugin_name ] );
				continue;
			}
			$plugins[ $plugin_url ] = get_plugin_data( $plugin_file_path, false );
		}
		return $plugins;
	}

	/**
	 *  Create AMIMOTO Plugin List HTML
	 *
	 * @access private
	 * @param none
	 * @return string(HTML)
	 * @since 0.0.1
	 */
	private function _get_amimoto_plugin_html() {
		$html = '';
		$plugins = $this->_get_amimoto_plugin_list();
		$active_plugin_urls = $this->_get_activated_plugin_list();
		$html .= "<table class='wp-list-table widefat plugins'>";
		$html .= '<tbody>';
		foreach ( $plugins as $plugin_url => $plugin ) {
			$plugin_type = $plugin['TextDomain'];
			if ( array_search( $plugin_url, $active_plugin_urls ) !== false ) {
				$stat = 'active';
				$btn_text = __( 'Deactivate Plugin' , self::$text_domain );
				$nonce = self::PLUGIN_DEACTIVATION;
			} else {
				$stat = 'inactive';
				$btn_text = __( 'Activate Plugin' , self::$text_domain );
				$nonce = self::PLUGIN_ACTIVATION;
			}
			$html .= "<tr class={$stat}><td>";
			$html .= "<h2>{$plugin['Name']}</h2>";
			$html .= "<p>{$plugin['Description']}</p>";
			$html .= "<form method='post' action=''>";
			$html .= get_submit_button( $btn_text );
			$html .= wp_nonce_field( $nonce , $nonce , true , false );
			$html .= "<input type='hidden' name='plugin_type' value={$plugin_type} />";
			$redirect_page = self::PANEL_ROOT;
			$html .= "<input type='hidden' name='redirect_page' value={$redirect_page} />";
			$html .= '</form>';
			if ( 'active' === $stat ) {
				$action = $this->_get_action_type( $plugin_type );
				$html .= "<form method='post' action='./admin.php?page={$action}'>";
				$html .= get_submit_button( __( 'Setting Plugin' , self::$text_domain ) );
				$html .= wp_nonce_field( self::PLUGIN_SETTING , self::PLUGIN_SETTING , true , false );
				$html .= "<input type='hidden' name='plugin_type' value={$plugin_type} />";
				$html .= '</form>';
			}
			$html .= '</td></tr>';
		}
		$html .= $this->_get_uninstalled_amimoto_plugin_html();
		$html .= '</tbody></table>';
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
			$plugin_install_url = "./plugin-install.php?tab=plugin-information&plugin=". urlencode( $plugin_name );
			$description = $this->_get_amimoto_plugin_description( $plugin_name );
			$html .= "<tr class='inactive'><td>";
			$html .= "<h2>{$plugin_name}</h2>";
			$html .= "<p>{$description}</p>";
			$html .= "<a class='install-now button' target='_blank' href='{$plugin_install_url}' aria-label='Install {$plugin_name} now' data-name='{$plugin_name}'>Install Now</a>";
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
		$activate_plugins = $this->_get_activated_plugin_list();
		return $html;
	}
}
