<?
namespace AMIMOTO_Dashboard\Tests;

use AMIMOTO_Dashboard\Constants;

class Constants_Test extends \WP_UnitTestCase {
    /**
     * @dataProvider provide_is_amimoto_managed_test_case
     */
    public function test_is_amimoto_managed( $server, $expected ) {
        $this->assertEquals( $expected, Constants::is_amimoto_managed( $server ) );
    }
    public function provide_is_amimoto_managed_test_case() {
        return [
            [
                null, false
            ],
            [
                array(
                    'HTTP_X_AMIMOTO_MANAGED' => 'false'
                ),
                true
            ],
            [
                array(
                    'HTTP_X_AMIMOTO_MANAGED' => 'true'
                ),
                true
            ],
        ];
    }
}