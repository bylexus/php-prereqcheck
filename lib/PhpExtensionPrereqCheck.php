<?php
require_once(dirname(__FILE__).'/PrereqCheck.php');
require_once(dirname(__FILE__).'/CheckResult.php');

/**
 * PHP Prerequisite Checker - PHP Extension Checker
 *
 * Checks if a given PHP extension is available.
 *
 * (c) 2014 Alexander Schenkel, info@alexi.ch
 */
class PhpExtensionPrereqCheck extends PrereqCheck {
    private $_name = 'PHP Extension: ';

    /**
     * @param string $extension Name of the required PHP extension
     */
    public function check($extension = null) {
        $this->name = $this->_name . $extension;

        if ($this->extension_loaded($extension) !== true) {
            $this->setFailed("Extension '{$extension}' not loaded.");
        }
    }

    protected function extension_loaded($extension) {
        return extension_loaded($extension);
    }
}