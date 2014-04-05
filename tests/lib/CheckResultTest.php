<?php
require_once(dirname(__FILE__).'/../../PrereqChecker.php');

class DummyCheck extends PrereqCheck {
	public function check() {}
}

class CheckResultTest extends PHPUnit_Framework_TestCase {
	public function testConstruction() {
		$pc = new DummyCheck();
		$cr = new CheckResult(true,$pc);
		$this->assertInstanceOf('PrereqCheck',$cr->check);
		$this->assertTrue($cr->success());
	}

	public function testSetSucceed() {
		$pc = new DummyCheck();
		$cr = new CheckResult(true,$pc);

		$cr->setSucceed();
		$this->assertTrue($cr->success());
		$this->assertFalse($cr->failed());
	}

	public function testSetFailed() {
		$pc = new DummyCheck();
		$cr = new CheckResult(true,$pc);
		$cr->setFailed('fail');
		$this->assertTrue($cr->failed());
		$this->assertEquals('fail',$cr->message);
	}

	public function testSuccess() {
		$pc = new DummyCheck();
		$cr = new CheckResult(true,$pc);
		$this->assertTrue($cr->success());

		$cr->setFailed('fail');
		$this->assertFalse($cr->success());
	}

	public function testFailed() {
		$pc = new DummyCheck();
		$cr = new CheckResult(false,$pc);
		$this->assertTrue($cr->failed());

		$cr->setSucceed();
		$this->assertFalse($cr->failed());
	}
}