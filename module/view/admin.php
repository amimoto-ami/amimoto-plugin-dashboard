<?php
/**
 * Amimoto_Dash_Admin
 *
 * Amimoto Plugin Dashboard admin page scripts
 *
 * @author hideokamoto <hide.okamoto@digitalcube.jp>
 * @package Amimoto-plugin-dashboard
 */
class Amimoto_Dash_Admin extends Amimoto_Dash_Component {
	private static $instance;
	private static $text_domain;
	public $amimoto_plugins = array(
		'C3 Cloudfront Cache Controller' => 'c3-cloudfront-clear-cache/c3-cloudfront-clear-cache.php',
		'Nephila clavata' => 'nephila-clavata/plugin.php',
		'Nginx Cache Controller on GitHub' => 'nginx-cache-controller/nginx-champuru.php',
		'Nginx Cache Controller on WP.org' => 'nginx-champuru/nginx-champuru.php'
	);

	private function __construct() {
		self::$text_domain = Amimoto_Dash_Base::text_domain();
	}

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
	 */
	private function _get_activated_plugin_list() {
		$active_plugin_urls = get_option('active_plugins');
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
	 */
	private function _get_amimoto_plugin_list() {
		foreach ( $this->amimoto_plugins as $plugin_name => $plugin_url ) {
			$plugin_file_path = path_join( ABSPATH . 'wp-content/plugins', $plugin_url );
			if( ! file_exists( $plugin_file_path ) ) {
				unset($this->amimoto_plugins[ $plugin_name ] );
				continue;
			}
			$plugins[] = get_plugin_data( $plugin_file_path, false );
		}
		return $plugins;
	}

	/**
	 *  Show admin page html
	 *
	 * @access public
	 * @param none
	 * @return none
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
	 */
	public function get_content_html() {
		$html = "hoge";
		$plugins = $this->_get_amimoto_plugin_list();
		$activate_plugins = $this->_get_activated_plugin_list();
		return $html;
	}
}
