<?php
require_once(dirname(__FILE__).'/../../PrereqChecker.php');

class DirWritablePrereqCheckTest extends PHPUnit_Framework_TestCase {
	public function testCheckWritable() {
		$tmpfile = tempnam('/tmp/test', 'foo');
		$dc = new DirWritablePrereqCheck();
		$dc->check(dirname($tmpfile));
		@unlink($tmpfile);
		$this->assertTrue($dc->getResult()->success());
	}

	public function testCheckNonWritable() {
		$dc = new DirWritablePrereqCheck();
		$dirname = '/non-existing-dir/'.time();
		$dc->check($dirname);
		$this->assertTrue($dc->getResult()->failed());
		$this->assertEquals("Directory '{$dirname}' not writable.",$dc->getResult()->message);
	}
}
