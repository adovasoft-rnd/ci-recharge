# Implementation Of Entity File

#### Create a Entity file
##### Command
```$xslt
php spark make:entity Entity_Name
```
##### Example
```
php spark make:entity Recharge
```

##### Output
```
<?php namespace App\Entities;

use CodeIgniter\Entity;

/**
 * @class Recharge
 * @author CI-Recharge
 * @package App
 * @extend Entity
 * @created 17 November, 2020 04:55:13 AM
 */

class Recharge extends Entity
{
    /**
     * Database Table Column names
     * index = column 
     * value = default
     * Eg: ['balance' => 0.00, 'name' => null]
     */
    protected $attributes = [];
    
    /**
     * Database Table Column To Property
     * Mapper
     * index = property
     * value = column
     * Eg: ['balance' => 'saving', 'phone' => 'mobile']
     */
    protected $datamap = [];
    
    /**
     * Property That will use timestamp
     */
    protected $dates = [];
    
    /**
     * Property Types Casted
     * Eg: ['is_banned' => 'boolean',
     * 'is_banned_nullable' => '?boolean']
     */
    protected $casts = [];

}

```
#### Create a Entity file specific namespace
##### Command
```$xslt
php spark make:entity entity_name -n namespace_name
```
##### Example
```
php spark make:entity Example1 -n robin
```

##### Output
```
<?php namespace robin\Entities;

use CodeIgniter\Entity;

/**
 * @class Example1
 * @author CI-Recharge
 * @package robin
 * @extend Entity
 * @created 17 November, 2020 04:58:07 AM
 */

class Example1 extends Entity
{
    /**
     * Database Table Column names
     * index = column 
     * value = default
     * Eg: ['balance' => 0.00, 'name' => null]
     */
    protected $attributes = [];
    
    /**
     * Database Table Column To Property
     * Mapper
     * index = property
     * value = column
     * Eg: ['balance' => 'saving', 'phone' => 'mobile']
     */
    protected $datamap = [];
    
    /**
     * Property That will use timestamp
     */
    protected $dates = [];
    
    /**
     * Property Types Casted
     * Eg: ['is_banned' => 'boolean',
     * 'is_banned_nullable' => '?boolean']
     */
    protected $casts = [];

}

```