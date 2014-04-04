<?php
require_once(dirname(__FILE__).'/lib/CheckResult.php');
require_once(dirname(__FILE__).'/lib/PrereqCheck.php');
require_once(dirname(__FILE__).'/lib/PhpVersionPrereqCheck.php');
require_once(dirname(__FILE__).'/lib/PhpExtensionPrereqCheck.php');
require_once(dirname(__FILE__).'/lib/PhpIniPrereqCheck.php');

/**
 * A Prerequisites checker for PHP. It enables the user to easily check for
 * application pre-requisites, whatever that may be. This checker comes with some pre-defined
 * checkers:
 *
 * php_version: checks if the actual php version is egilible
 * php_extension: checks if a given php extension is loaded
 * php_ini: helper for checking php ini variables
 *
 * Allows to define own checks. For an example, have a look at prereq-checker.php in the tools/ dir.
 * A brief example:
 *
 * $pc = new PrereqChecker();
 * $pc->check('php_version','>=','5.3.0');
 *
 * Outputs the results either on command line or as web output.
 */
class PrereqChecker {
    private $_mode;
    private $_checks = array();
    private $_checkResults = array();

    public function __construct() {
        if (strtolower(php_sapi_name()) === 'cli') {
            $this->_mode = 'cli';
        } else {
            $this->_mode = 'web';
        }

        $this->addInternalChecks();
        $this->reset();
    }

    private function addInternalChecks() {
        $this->registerCheck('php_version', 'PhpVersionPrereqCheck');
        $this->registerCheck('php_extension', 'PhpExtensionPrereqCheck');
        $this->registerCheck('php_ini', 'PhpIniPrereqCheck');
    }

    public function setMode($mode) {
        if (in_array($mode,array('cli','web','silent'))) {
            $this->_mode = $mode;
        } else throw new Exception("Mode must be one of 'web','cli'");
    }

    public function getMode() {
        return $this->_mode;
    }


    public function registerCheck($checkName, $checkClass) {
        $this->_checks[$checkName] = $checkClass;
    }

    public function getCheck($checkName) {
        if (array_key_exists($checkName, $this->_checks)) {
            $className = $this->_checks[$checkName];
            if (class_exists($className)) {
                $checker = new $className();
                if ($checker instanceof PrereqCheck) {
                    return $checker;
                }
            }
        }
        throw new Exception('Check class for '.$checkName.' not found.');
    }


    public function check($checkName) {
        $arg_list = func_get_args();
        array_shift($arg_list);
        $checker = $this->getCheck($checkName);
        $ret = call_user_func_array(array($checker,'check'), $arg_list);
        if (!($ret instanceof CheckResult)) throw new Exception('check() function must return an instance of CheckResult.');
        $this->outputCheckResult($ret);
        $this->_checkResults[] = $ret;
        return $ret;
    }

    public function reset() {
        $this->_checkResults = array();
    }

    public function didAllSucceed() {
        foreach($this->_checkResults as $result) {
            if ($result->success() !== true) return false;
        }
        return true;
    }

    private function outputCheckResult(CheckResult $res) {
        $this->writeOutput($res);
    }

    private function writeOutput(CheckResult $res) {
        if ($this->_mode === 'cli') {
            $this->writeOutputCli($res);
        } else if ($this->_mode === 'web') {
            $this->writeOutputWeb($res);
        }
    }

    private function writeOutputCli(CheckResult $res) {
        $str = "\033[0m{$res->check->name}: ";
        if ($res->passed()) {
            $str .= "\033[0;32mPASSED\033[0m";
        } else if ($res->failed()) {
            $str .= "\033[0;31mFAILED: \033[0m{$res->message}";
        } else {
            $str .= "\033[0;33mWARNING: \033[0m{$res->message}";
        }
        echo "{$str}\n";
    }

    private function writeOutputWeb(PrereqCheck $res) {
        $str = "<div>{$res->check->name}: ";
        if ($res->success()) {
            $str .= "<span style=\"color: #00FF00\">PASSED</span>";
        } else if ($res->failed()) {
            $str .= "<span style=\"color: #FF0000\">FAILURE: </span>{$res->message}";
        } else {
            $str .= "<span style=\"color: #FFFF00\">WARNING: </span>{$res->message}";
        }
        echo "{$str}</div>";
    }
}
