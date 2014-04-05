<?php
require_once(dirname(__FILE__).'/../../PrereqChecker.php');

class PCDummyCheck extends PrereqCheck {
	public function check() {}
}

class PrereqCheckTest extends PHPUnit_Framework_TestCase {
	public function testConstruction() {
		$pc = new PCDummyCheck('MyName');
		$this->assertInstanceOf('CheckResult',$pc->getResult());
		$this->assertEquals('MyName',$pc->name);
	}

	public function testSetSucceed() {
		$pc = new PCDummyCheck('MyName');
		$pc->setSucceed();
		$this->assertTrue($pc->getResult()->success());
	}

	public function testSetFailed() {
		$pc = new PCDummyCheck('MyName');
		$pc->setFailed('fail');
		$this->assertTrue($pc->getResult()->failed());
		$this->assertEquals('fail',$pc->getResult()->message);
	}
}