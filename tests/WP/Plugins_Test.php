<?
namespace AMIMOTO_Dashboard\Tests\WP;

use AMIMOTO_Dashboard\WP\Plugins;

class Plugins_Test extends \WP_UnitTestCase {

    /**
     * @dataProvider provide_test_is_activated_ncc_test_case
     */
    public function test_is_activated_ncc( $active_plugins, $expected ) {

        update_option( 'active_plugins', $active_plugins );

        $result = Plugins::is_activated_ncc();
        $this->assertEquals( $expected, $result );

        delete_option( 'active_plugins' );
    }
    public function provide_test_is_activated_ncc_test_case() {
        return [
            [array(), false],
            [array(
                'nginx-champuru/nginx-champuru.php'
            ), true],
            [array(
                'nginx-cache-controller/nginx-champuru.php'
            ), true],
            [array(
                'nginx-champuru/nginx-champuru.php',
                'nginx-cache-controller/nginx-champuru.php'
            ), true],
            [array(
                'another-nginx-champuru/nginx-champuru.php',
                'another-nginx-cache-controller/nginx-champuru.php'
            ), false],
        ];
    }

    /**
     * @dataProvider provide_get_plugin_file_path_test_case
     */
    public function test_get_plugin_file_path( $plugin_name, $expected ) {
        $result = Plugins::get_plugin_file_path( $plugin_name );
        $this->assertEquals( $expected, $result );
    }
    public function provide_get_plugin_file_path_test_case() {
        return [
            [ 'hello-world', ABSPATH . 'wp-content/plugins/hello-world' ]
        ];
    }

    /**
     * @dataProvider provide_list_amimoto_activated_plugins_test_case
     */
    public function test_list_amimoto_activated_plugins( $active_plugins, $expected ) {
        if ( isset( $active_plugins ) ) update_option( 'active_plugins', $active_plugins );

        $result = Plugins::list_amimoto_activated_plugins();
        $this->assertEquals( $expected, $result );

        if ( isset( $active_plugins ) ) delete_option( 'active_plugins' );
    }
    public function provide_list_amimoto_activated_plugins_test_case() {
        return [
            [
                null,
                array()
            ],
            [
                "null",
                array()
            ],
            [
                array(
                    'nginx-champuru/nginx-champuru.php',
                    'nginx-cache-controller/nginx-champuru.php'
                ),
                array(
                    'nginx-champuru/nginx-champuru.php',
                    'nginx-cache-controller/nginx-champuru.php'
                )
            ],
            [
                array(
                    'another-nginx-champuru/nginx-champuru.php',
                    'another-nginx-cache-controller/nginx-champuru.php'
                ),
                array()
            ],
            [
                array(
                    'nginx-champuru/nginx-champuru.php',
                    'another-nginx-champuru/nginx-champuru.php',
                    'another-nginx-cache-controller/nginx-champuru.php',
                    'nginx-cache-controller/nginx-champuru.php'
                ),
                array(
                    'nginx-champuru/nginx-champuru.php',
                    'nginx-cache-controller/nginx-champuru.php'
                )
            ]
        ];
    }

    /**
     * @dataProvider provide_get_plugin_slug_by_name_test_case
     */
    public function test_get_plugin_slug_by_name( $plugin_name, $expected ) {
        $result = Plugins::get_plugin_slug_by_name( $plugin_name );
        $this->assertEquals( $expected, $result );
    }
    public function provide_get_plugin_slug_by_name_test_case() {
        return [
            [ 'dummy', null ],
            [ 'C3 Cloudfront Cache Controller', 'c3-cloudfront-clear-cache/c3-cloudfront-clear-cache.php' ],
            [ 'Nginx Cache Controller on GitHub', 'nginx-cache-controller/nginx-champuru.php' ],
            [ 'Nginx Cache Controller on WP.org', 'nginx-champuru/nginx-champuru.php' ],
        ];
    }

    /**
     * @dataProvider provide_get_plugin_file_path_by_name_test_case
     */
    public function test_get_plugin_file_path_by_name( $plugin_name, $expected ) {
        $result = Plugins::get_plugin_file_path_by_name( $plugin_name );
        $this->assertEquals( $expected, $result );

    }
    public function provide_get_plugin_file_path_by_name_test_case() {
        return [
            [ 'dummy', null ],
            [
                'C3 Cloudfront Cache Controller', ABSPATH . 'wp-content/plugins/c3-cloudfront-clear-cache/c3-cloudfront-clear-cache.php'
            ],
            [ 'Nginx Cache Controller on GitHub', ABSPATH . 'wp-content/plugins/nginx-cache-controller/nginx-champuru.php' ],
            [ 'Nginx Cache Controller on WP.org', ABSPATH . 'wp-content/plugins/nginx-champuru/nginx-champuru.php' ],
        ];
    }
}