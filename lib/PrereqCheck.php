<?php
/**
 * PHP Prerequisite Checker
 *
 * (c) 2014 Alexander Schenkel, info@alexi.ch
 */

abstract class PrereqCheck {
	public $name = "Insert check name here";
	protected $result;
	
	public function __construct($name = null) {
		if ($name) $this->name = $name;
		$this->result = new CheckResult(true,$this);
	}

	public function getResult() {
		return $this->result;
	}

	public function setSucceed() {
		$this->result->setSucceed();
	}

	public function setFailed($msg = '') {
		$this->result->setFailed($msg);
	}
    
    abstract public function check();
}