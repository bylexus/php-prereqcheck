<?php
/**
 * PHP Prerequisite Checker
 * Demo Script for the Prereq Checker.
 *
 * (c) 2014 Alexander Schenkel, info@alexi.ch
 */

$here = dirname(__FILE__);
require_once($here.'/PrereqChecker.php');

$pc = new PrereqChecker();
if ($pc->getMode() == 'web') {
    echo '<pre style="background-color: #AAA; border: 1px solid black; padding: 10px;">';
}

$pc->checkMandatory('php_version','>=','5.3.0');

$pc->checkMandatory('php_extension','gd');
$pc->checkMandatory('php_extension','mbstring');
$pc->checkMandatory('php_extension','pdo');
$pc->checkMandatory('php_extension','pdo_pgsql');
$pc->checkMandatory('php_extension','xml');
$pc->checkMandatory('php_extension','soap');
$pc->checkMandatory('php_extension','openssl');

$pc->checkMandatory('php_ini','error_reporting',E_NOTICE,'bit_disabled');
$pc->checkOptional('php_ini','display_errors','off','boolean');
$pc->checkMandatory('php_ini','memory_limit','>=256MB','number');
$pc->checkMandatory('dir_writable','/tmp/');
$pc->checkMandatory('dir_writable','./');
$pc->checkMandatory('dir_writable','/some/unknown/dir/');

class MyOwnChecker extends PrereqCheck {
    public $name = 'My Own Checker';
    public function check($myparam = null) {
        $res = new CheckResult(true,$this);
        if ($myparam !== true) {
            $res->setFailed("Uh Oh! MyParam must be set to true!");
        }
        return $res;
    }
}

$pc->registerCheck('own_checker','MyOwnChecker');
$pc->checkMandatory('own_checker',true);
$pc->checkMandatory('own_checker',false);


class FileExistsChecker extends PrereqCheck {
    public function check($filename = null) {
        $this->name = "File exists: {$filename}";
        if (file_exists($filename)) {
            $this->setSucceed();
        } else {
            $this->setFailed('File does not exists.');
        }
    }
}
$pc->registerCheck('file_exists','FileExistsChecker');
$pc->checkMandatory('file_exists','some_file.txt');
$pc->checkMandatory('file_exists','./example-usage.php');

if ($pc->didAllSucceed()) {
    echo "All tests succeeded!\n";
} else {
    echo "Some tests failed. Please check.\n";
}

if ($pc->getMode() == 'web') {
    echo '</pre>';
}
