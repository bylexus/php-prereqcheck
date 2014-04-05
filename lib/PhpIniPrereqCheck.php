<?php
require_once(dirname(__FILE__).'/PrereqCheck.php');
require_once(dirname(__FILE__).'/CheckResult.php');

class PhpIniPrereqCheck extends PrereqCheck {
    private $_name = 'PHP Setting: ';

    public function check() {
        $arg_list = func_get_args();
        $param = $arg_list[0];
        $compareValue = $arg_list[1];
        $type = $arg_list[2];
        $this->name = $this->_name . $param;

        $res = new CheckResult(true,$this);
        $iniValue = ini_get($param);

        switch (strtolower($type)) {
            case 'bool':
            case 'boolean':
                $this->booleanCompare($res,$iniValue,$compareValue); break;
            case 'bit_disabled':
                $this->bitDisabledCompare($res,$iniValue,$compareValue); break;
            case 'bit_enabled':
                $this->bitEnabledCompare($res,$iniValue,$compareValue); break;
            case 'number':
                $this->numberCompare($res,$iniValue,$compareValue); break;
            default:
                $this->stringCompare($res, $iniValue, $compareValue);
        }
        return $res;
    }

    private function stringCompare(CheckResult $res, $iniValue, $compareValue) {
        if ($iniValue !== $compareValue) {
            $res->setFailed("ini value '{$iniValue}' does not match expected: '{$compareValue}'");
        }
        return $res;
    }

    private function booleanCompare(CheckResult $res, $iniValue, $compareValue) {
        $passed = false;
        $iniValue = $this->toBool($iniValue);
        $boolValue = $this->toBool($compareValue);

        if ($iniValue !== $boolValue) {
            $res->setFailed("ini value '{$iniValue}' does not match expected: '{$compareValue}'");
        }
        return $res;
    }

    private function bitDisabledCompare(CheckResult $res, $iniValue, $compareValue) {
        $compareValue = (int)$compareValue;
        $iniValue = (int)$iniValue;
        if (($iniValue & $compareValue) === $compareValue) {
            $res->setFailed("bitfield value '{$compareValue}' is enabled, should not.");
        }
        return $res;
    }

    private function bitEnabledCompare(CheckResult $res, $iniValue, $compareValue) {
        $compareValue = (int)$compareValue;
        $iniValue = (int)$iniValue;
        
        if (($iniValue & $compareValue) !== $compareValue) {
            $res->setFailed("bitfield value '{$compareValue}' is disabled, should be enabled.");
        }
        return $res;
    }

    private function numberCompare(CheckResult $res, $iniValue, $compareValue) {
        $matches = array();
        preg_match('/^([<>=]*)([0-9.-]+)([a-zA-Z]*)$/', $compareValue, $matches);
        if (count($matches) !== 4) {
            throw new Exception('Error in Size definition.');
        }

        $operator = '=';
        if ($matches[1]) $operator = $matches[1];

        $number = $matches[2].$matches[3];
        $iniBytes = $this->sizeStrToBytes($iniValue);
        $numberBytes = $this->sizeStrToBytes($number);
        $passed = true;
        switch ($operator) {
            case '>':
                if ($numberBytes > $iniBytes ) $passed = false; break;
            case '>=':
                if ($numberBytes >= $iniBytes ) $passed = false; ;break;
            case '<':
                if ($numberBytes < $iniBytes ) $passed = false; break;
            case '<=':
                if ($numberBytes <= $iniBytes ) $passed = false; break;
            case '=':
            default:
                if ($numberBytes !== $iniBytes ) $passed = false; break;
        }
        if (!$passed) {
            $res->setFailed("Ini Size '{$iniValue}' does not match the criteria '{$operator} {$number}'");
        }
        return $res;
    }

    private function sizeStrToBytes($sizeStr) {
        $matches = array();
        preg_match('/^([0-9.-]+)([a-zA-Z]+)$/', $sizeStr,$matches);
        $number = $matches[1];
        $exponent = strtolower($matches[2]);
        switch($exponent) {
            case 't':
            case 'tb': $number = $number * 1024;
            case 'g':
            case 'gb': $number = $number * 1024;
            case 'm':
            case 'mb': $number = $number * 1024;
            case 'k':
            case 'kb': $number = $number * 1024;
            default: $number = $number * 1.0;
        }
        return $number;
    }

    private function toBool($value) {
        $boolValues = array(true,'true',1,'1','on','yes');
        if (is_string($value)) {
            $value = strtolower($value);
        }
        foreach($boolValues as $compare) {
            if ($compare === $value) return true;
        }
        return false;
    }
}
