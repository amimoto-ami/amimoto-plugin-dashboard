<?php
namespace AMIMOTO_Dashboard\Tests\Views;

use AMIMOTO_Dashboard\Views\Menus;

class Menus_Test extends \WP_UnitTestCase {
    public function test_remove_submenus() {
        global $submenu;
        
        $submenu = array(
            'options-general.php' => array(
                '10' => [ 'General', 'manage_options', 'options-general.php' ],
                '15' => [ 'Writing', 'manage_options', 'options-writing.php' ],
                '20' => [ 'Reading', 'manage_options', 'options-reading.php' ],
                '25' => [ 'Discussion', 'manage_options', 'options-discussion.php' ],
                '30' => [ 'Media', 'manage_options', 'options-media.php' ],
                '40' => [ 'Permalinks', 'manage_options', 'options-permalink.php' ],
                '45' => [ 'Privacy', 'manage_privacy_options', 'options-privacy.php' ],
                '46' => [
                  'CloudFront Settings',
                  'administrator',
                  'c3-admin-menu',
                  'CloudFront Settings'
                ]
            )   
        );
        $target = new Menus();

        $target->remove_submenus();

        $this->assertEquals(array(
            'options-general.php' => array(
                '10' => [ 'General', 'manage_options', 'options-general.php' ],
                '15' => [ 'Writing', 'manage_options', 'options-writing.php' ],
                '20' => [ 'Reading', 'manage_options', 'options-reading.php' ],
                '25' => [ 'Discussion', 'manage_options', 'options-discussion.php' ],
                '30' => [ 'Media', 'manage_options', 'options-media.php' ],
                '40' => [ 'Permalinks', 'manage_options', 'options-permalink.php' ],
                '45' => [ 'Privacy', 'manage_privacy_options', 'options-privacy.php' ],
            )   
        ), $submenu );
        $submenu = array();
    }

    /**
     * @dataProvider provide_test_drop_amimoto_plugin_menus_test_case
     */
    public function test_drop_amimoto_plugin_menus( $expected, $menus ) {
        $target = new Menus();

        $result = $target->drop_amimoto_plugin_menus( $menus );

        // Load current status
        $this->assertEquals( $expected, $result );
    }
    public function provide_test_drop_amimoto_plugin_menus_test_case() {
        return [
            [
                null,
                null,
            ],
            [
                array(),
                array(),
            ],
            [
                array(
                    array(
                        'Test Toplevel',
                        'manage_options',
                        'mt-top-level-handle',
                        'Test Toplevel',
                        'menu-top menu-icon-generic toplevel_page_mt-top-level-handle',
                        'toplevel_page_mt-top-level-handle',
                        'dashicons-admin-generic',
                    )
                ),
                array(
                    array(
                        'Test Toplevel',
                        'manage_options',
                        'mt-top-level-handle',
                        'Test Toplevel',
                        'menu-top menu-icon-generic toplevel_page_mt-top-level-handle',
                        'toplevel_page_mt-top-level-handle',
                        'dashicons-admin-generic',
                    )
                ),
            ],
            [
                array(
                    array(
                        'Test Toplevel',
                        'manage_options',
                        'mt-top-level-handle',
                        'Test Toplevel',
                        'menu-top menu-icon-generic toplevel_page_mt-top-level-handle',
                        'toplevel_page_mt-top-level-handle',
                        'dashicons-admin-generic',
                    ),
                    array(
                        "AMIMOTO",
                         "administrator",
                         "amimoto_dash_root",
                         "Welcome to AMIMOTO Plugin Dashboard",
                         'menu-top menu-icon-generic toplevel_page_amimoto_dash_root',
                         "toplevel_page_amimoto_dash_root",
                         'dashicons-admin-generic'
                    )
                ),
                array(
                    array(
                        'Test Toplevel',
                        'manage_options',
                        'mt-top-level-handle',
                        'Test Toplevel',
                        'menu-top menu-icon-generic toplevel_page_mt-top-level-handle',
                        'toplevel_page_mt-top-level-handle',
                        'dashicons-admin-generic',
                    ),
                    array(
                        'Nginx Cache',
                        'administrator',
                        'nginx-champuru',
                        'Nginx Cache',
                        'menu-top menu-icon-generic toplevel_page_nginx-champuru',
                        'toplevel_page_nginx-champuru',
                        'dashicons-admin-generic',
                    ),
                    array(
                        "AMIMOTO",
                         "administrator",
                         "amimoto_dash_root",
                         "Welcome to AMIMOTO Plugin Dashboard",
                         'menu-top menu-icon-generic toplevel_page_amimoto_dash_root',
                         "toplevel_page_amimoto_dash_root",
                         'dashicons-admin-generic'
                    )
                ),
            ],
        ];
    }
}
