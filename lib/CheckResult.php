<?php
/**
 * PHP Prerequisite Checker
 *
 * (c) 2014 Alexander Schenkel, info@alexi.ch
 */

class CheckResult {
    public $check = null;
    public $message = '';

    private $_succeed = false;

    public function __construct($result = true, PrereqCheck $check = null) {
        $this->_succeed = $result;
        $this->check = $check;
    }

    public function setFailed($msg = '') {
        $this->_succeed = false;
        $this->message = $msg;
    }

    public function setSucceed() {
        $this->_succeed = true;
        $this->message = '';
    }

    public function failed() {
        return $this->_succeed !== true;
    }

    public function success() {
        return $this->_succeed === true;
    }
}
