<?php
/**
 * Empty class to get stub for tests
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Praxigento\BonusLoyalty\Setup;


include_once(__DIR__ . '/../phpunit_bootstrap.php');

class InstallData_UnitTest extends \Praxigento\Core\Lib\Test\BaseMockeryCase
{

    protected function setUp()
    {
        parent::setUp();
        $this->markTestSkipped('Test is deprecated after M1 & M2 merge is done.');
    }

    public function test_constructor()
    {
        $obj = new InstallData();
        $this->assertInstanceOf(\Praxigento\BonusLoyalty\Setup\InstallData::class, $obj);
    }
}