<?php
namespace AMIMOTO_Dashboard\Tests\WP;

use AMIMOTO_Dashboard\WP\Mail_Fixtures;

class Mail_Fixtures_Test extends \WP_UnitTestCase {
    /**
     * @dataProvider provide_patch_mail_address_test_case
     */
    public function test_patch_mail_address( $email_address, $server, $expected ) {
        $target = new Mail_Fixtures();
        $this->assertEquals( $expected, $target->patch_mail_address( $email_address, $server ) );
    }
    public function provide_patch_mail_address_test_case() {
        return [
            [
                'test@example.com', null, 'test@example.com'
            ],
            [
                'test@example.com', array(
                    'SERVER_NAME' => '_'
                ), 'wordpress@' . parse_url( get_home_url( get_current_blog_id() ), PHP_URL_HOST )
            ],
        ];
    }
}
