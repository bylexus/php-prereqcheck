<?php
require_once(dirname(__FILE__).'/../../PrereqChecker.php');

class PhpVersionPrereqCheckTest extends PHPUnit_Framework_TestCase {
	public function testCheck() {
		$dc = new PhpVersionPrereqCheck();
		$dc->check('>','5.2.0');
		$this->assertTrue($dc->getResult()->success());

		$dc->check('<','5.2.0');
		$this->assertTrue($dc->getResult()->failed());
		$this->assertEquals("Actual PHP Version (".php_version().") does not meet the requirement < 5.2.0",$dc->getResult()->message);
	}
}
