php-prereqcheck
===============

Helper class to create Prerequisite Checks. It allows you to easily create a Prerequisite Check Script for your
project, e.g. matching (php) version, installed extensions, settings etc. It also allows you to write your
own checks to implement individual checks.

Sample usage
------------

```php
require_once('PrereqChecker.php');

$pc = new PrereqChecker();

$pc->checkMandatory('php_version','>=','5.3.0');
$pc->checkMandatory('php_extension','gd');
$pc->checkMandatory('php_extension','mbstring');
$pc->checkMandatory('php_extension','pdo');
$pc->checkOptional('php_ini','display_errors','off','boolean');
$pc->checkOptional('php_ini','memory_limit','>=256MB','number');

if ($pc->didAllSucceed()) {
    echo "All tests succeeded!\n";
} else {
    echo "Some tests failed. Please check.\n";
}
```

Built-in Checks
-----------------

### php_version

Checks if the actual PHP version matches a version comparison.

Example:
```php
$pc->checkMandatory('php_version','>=','5.3.0');
```

### php_extension

Checks if the given PHP extension is available.

Example:
```php
$pc->checkMandatory('php_extension','pdo');
```


### php_ini

Checks if a given PHP ini setting matches the criteria. Because it is not (always)
possible to determine the type of value, a comparison function is needed in the config:

Example:
```php
$pc->checkOptional('php_ini','default_timezone','Europe/Zurich','string');
$pc->checkOptional('php_ini','display_errors','off','boolean');
$pc->checkOptional('php_ini','error_reporting',E_STRICT,'bit_enabled');
$pc->checkOptional('php_ini','error_reporting',E_NOTICE,'bit_disabled');
$pc->checkOptional('php_ini','memory_limit','>=128M','number');
```

Possible comparison functions:

* boolean: Checks if the given value is true-ish or false-ish (e.g. 'Off' means false)
* string: exact string match (e.g. default_timezone = 'Europe/Zurich')
* enabled: Checks if the given bit(s) are set in the ini value (e.g. checks if E_WARNING is set in error_reporting)
* bit_disabled: Checks if the given bit(s) are NOT set in the ini value (e.g. checks if E_NOTICE is disabled in error_reporting)
* number: Checks a number value against a comparison, e.g. if memory_limit is >= 512m.


### dir_writable

Checks if a given dir exists and is writable.

Example:
```php
$pc->checkMandatory('dir_writable','/tmp/');
```

Write your own checks
----------------------

Writing your own checks is very simple. Just provide a `PrereqCheck` class and register it with the PrereqChecker.
Then you can run the defined check:

```php
# Define a class that extends PrereqCheck and implements the check() function:
class FileExistsChecker extends PrereqCheck {
    public function check($filename = null) {
        $this->name = "File exists: {$filename}";
        if (file_exists($filename)) {
        	# mark check as succeed (default, don't have to be called):
            $this->setSucceed();
        } else {
        	# mark check as failed, add a failure message:
            $this->setFailed('File does not exists.');
        }
    }
}

# Register check with the PrereqChecker:
$pc->registerCheck('file_exists','FileExistsChecker');

# Execute the check:
$pc->checkMandatory('file_exists','some_file.txt');
```

Documentation
---------------

... TBD ...