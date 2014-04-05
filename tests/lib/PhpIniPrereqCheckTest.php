<?php
require_once(dirname(__FILE__).'/../../PrereqChecker.php');

class PhpIniPrereqCheckTest extends PHPUnit_Framework_TestCase {
	public function testCheckString() {
		ini_set('date.timezone', 'Europe/Zurich');
		$dc = new PhpIniPrereqCheck();
		$dc->check('date.timezone','Europe/Zurich','string');
		$this->assertTrue($dc->getResult()->success());

		ini_set('date.timezone', 'Europe/Berlin');
		$dc->check('date.timezone','Europe/Zurich','string');
		$this->assertTrue($dc->getResult()->failed());
		$this->assertEquals("ini value 'Europe/Berlin' does not match expected: 'Europe/Zurich'",$dc->getResult()->message);
	}

	public function testCheckNumber() {
		ini_set('memory_limit', '32M');
		$dc = new PhpIniPrereqCheck();
		$dc->check('memory_limit','>=16M','number');
		$this->assertTrue($dc->getResult()->success());

		$dc = new PhpIniPrereqCheck();
		$dc->check('memory_limit','>= 64GB','number');
		$this->assertTrue($dc->getResult()->failed());
		$this->assertEquals("Ini Size '32M' does not match the criteria '>= 64GB'",$dc->getResult()->message);

		$dc = new PhpIniPrereqCheck();
		$dc->check('memory_limit','<64m','number');
		$this->assertTrue($dc->getResult()->success());

		$dc = new PhpIniPrereqCheck();
		$dc->check('memory_limit','< 32m','number');
		$this->assertTrue($dc->getResult()->failed());
		$this->assertEquals("Ini Size '32M' does not match the criteria '< 32m'",$dc->getResult()->message);
	}

	public function testCheckBoolean() {
		ini_set('display_errors', 'On');
		$dc = new PhpIniPrereqCheck();
		$dc->check('display_errors',true,'boolean');
		$this->assertTrue($dc->getResult()->success());

		ini_set('display_errors', 'Off');
		$dc = new PhpIniPrereqCheck();
		$dc->check('display_errors',true,'boolean');
		$this->assertTrue($dc->getResult()->failed());
		$this->assertEquals("ini value '' does not match expected: '1'",$dc->getResult()->message);
	}

	public function testCheckBitDisabled() {
		ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED);
		$dc = new PhpIniPrereqCheck();
		$dc->check('error_reporting',E_WARNING,'bit_disabled');
		$this->assertTrue($dc->getResult()->failed());
		$this->assertEquals("bitfield value '2' is enabled, should not.",$dc->getResult()->message);

		$dc = new PhpIniPrereqCheck();
		$dc->check('error_reporting',E_NOTICE,'bit_disabled');
		$this->assertTrue($dc->getResult()->success());
	}

	public function testCheckBitEnabled() {
		ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED);
		$dc = new PhpIniPrereqCheck();
		$dc->check('error_reporting',E_NOTICE,'bit_enabled');
		$this->assertTrue($dc->getResult()->failed());
		$this->assertEquals("bitfield value '8' is disabled, should be enabled.",$dc->getResult()->message);
		
		$dc = new PhpIniPrereqCheck();
		$dc->check('error_reporting',E_WARNING,'bit_enabled');
		$this->assertTrue($dc->getResult()->success());
		
	}
}
