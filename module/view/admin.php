<?php
class Amimoto_Dash_Admin extends Amimoto_Dash_Component {
	private static $instance;
	private static $text_domain;
	public $require_text = '';

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

	public function get_content_html() {
		$html = "hoge";
		return $html;
	}

	public function init_panel() {
		$this->show_panel_html();
	}
}
