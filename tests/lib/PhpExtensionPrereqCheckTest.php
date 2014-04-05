<?php
require_once(dirname(__FILE__).'/../../PrereqChecker.php');

class MockPhpExtCheck extends PhpExtensionPrereqCheck {
	public $exists = true;
	protected function extension_loaded($ext) {
		return $this->exists;
	}
}

class PhpExtensionPrereqCheckTest extends PHPUnit_Framework_TestCase {
	public function testCheckAvailable() {
		$dc = new MockPhpExtCheck();
		$dc->exists = true;
		$dc->check('MyMockExtension');
		$this->assertTrue($dc->getResult()->success());
	}

	public function testCheckNotAvailable() {
		$dc = new MockPhpExtCheck();
		$dc->exists = false;
		$dc->check('MyMockExtension');
		$this->assertTrue($dc->getResult()->failed());
		$this->assertEquals("Extension 'MyMockExtension' not loaded.",$dc->getResult()->message);
	}
}
