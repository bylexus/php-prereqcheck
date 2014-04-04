<?php
/**
 * Demo File for the Prereq Checker.
 */
$here = dirname(__FILE__);
require_once($here.'/PrereqChecker.php');




$pc = new PrereqChecker();
if ($pc->getMode() == 'web') {
    echo '<pre style="background-color: #AAA; border: 1px solid black; padding: 10px;">';
}

$pc->check('php_version','>=','5.3.0');

$pc->check('php_extension','gd');
$pc->check('php_extension','mbstring');
$pc->check('php_extension','pdo');
$pc->check('php_extension','pdo_pgsql');
$pc->check('php_extension','xml');
$pc->check('php_extension','soap');
$pc->check('php_extension','openssl');

/*
$pc->check('php_ini','display_errors', function($value) {
    if ($value == true) throw new WarningException('Errors should not be displayed to the frontend.');
    return true;
});
$pc->check('php_ini','error_reporting', function($value) {
    if (E_NOTICE & $value == E_NOTICE) {
        throw new FailureException('E_NOTICE must be disabled in php.ini.');
    }
    if (E_DEPRECATED & $value == E_DEPRECATED) {
        throw new FailureException('E_DEPRECATED must be disabled in php.ini.');
    }
    return true;
});

$pc->check('php_ini','magic_quotes_gpc', function($value) {
    if ($value == true) {
        throw new FailureException('magic_quotes_gpc must be turned off.');
    }
    return true;
});
$pc->check('php_ini','magic_quotes_runtime', function($value) {
    if ($value == true) {
        throw new FailureException('magic_quotes_runtime must be turned off.');
    }
    return true;
});

class MyOwnChecker extends PrereqCheck {
    public $name = 'My Own Checker';
    public function check($myparam = null) {
        if ($myparam === true) return true;
        throw new WarningException('Oops, MyValue is not true!');
    }
}

$pc->registerCheck('own_checker','MyOwnChecker');
$pc->check('own_checker',true);
*/
if ($pc->getMode() == 'web') {
    echo '</pre>';
}
