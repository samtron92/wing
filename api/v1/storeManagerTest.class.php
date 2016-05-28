<?php
require_once('autoload.php');
class storeManagerTest extends PHPUnit_Framework_TestCase {
	public function setUp(){ }
	public function tearDown(){ }
	public function testStoreManagerGet()
	{
		$storeManagerObj = new storeManager();
		$action = $args = NULL;
		$response = $storeManagerObj->get($action, $args);
		$this->assertTrue($response['code'] != 200 );
	}
}
?>
