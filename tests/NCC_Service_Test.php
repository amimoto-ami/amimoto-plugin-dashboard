<?php
namespace AMIMOTO_Dashboard\Tests;

use AMIMOTO_Dashboard\NCC_Service;

class NCC_Service_Test extends \WP_UnitTestCase {
    /**
     * @dataProvider provide_test_update_ncc_cache_expires_test_case
     */
    public function test_update_ncc_cache_expires( $expected, $previous_options ) {
        update_option( 'nginxchampuru-cache_expires', $previous_options );
        
        $target = new NCC_Service();
        $target->update_ncc_cache_expires();

        $option = get_option( 'nginxchampuru-cache_expires' );
        $this->assertEquals( $expected, $option );
        delete_option( 'nginxchampuru-cache_expires' );
    }
    public function provide_test_update_ncc_cache_expires_test_case() {
        return [
            [
                array(
                    "A" => 30,
                    "B" => 30,
                    "C" => 30,
                ),
                array(
                    "A" => 100,
                    "B" => 200,
                    "C" => 300,
                )
            ],
            [
                array(),
                array()
            ],
            [
                null,
                null
            ],
        ];
    }

    public function test_update_ncc_plugin_setting_returns_wp_error_when_ncc_is_not_activated() {
        $mock_plugins = new Mocks\WP\Plugins( array(
            'ncc' => array(
                'activated' => false
            )
        ) );
        $target = new NCC_Service( $mock_plugins );
        $result = $target->update_ncc_plugin_settings();
        $this->assertEquals( \is_wp_error( $result ), true);
    }

    public function test_update_ncc_plugin_setting_returns_no_wp_error_when_ncc_is_activated() {
        $mock_plugins = new Mocks\WP\Plugins( array(
            'ncc' => array(
                'activated' => true
            )
        ) );
        $target = new NCC_Service( $mock_plugins );
        $result = $target->update_ncc_plugin_settings();
        $this->assertEquals( \is_wp_error( $result ), false);
    }
}