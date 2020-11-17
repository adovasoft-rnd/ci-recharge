# Implementation Of Configuration File
Create a basic config file at App namespace
#### Command
```$xslt
php spark make:config configuration_name
```
#### Example
```$xslt
php spark make:config test
```
#### Output
```$xslt
<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * @class Test configuration.
 * @author CI-Recharge
 * @package Config
 * @created 16 November, 2020 05:04:31 AM
 */

class Test extends BaseConfig
{
		//
}
```
Add namespace to the configuration location 
#### Command
```$xslt
php spark make:config configuration_name -n namespace_name
```
#### Example
```$xslt
php spark make:config test -n Example
```
#### Output
```$xslt
<?php namespace Example\Config;

use CodeIgniter\Config\BaseConfig;

/**
 * @class Test configuration.
 * @author CI-Recharge
 * @package Config
 * @created 16 November, 2020 05:04:31 AM
 */

class Test extends BaseConfig
{
		//
}
```

