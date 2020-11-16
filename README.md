# Codeigniter4 Recharge
**This package is under-development. Not Ready for Use.**
Location will be detected from autoloader service

##Configuration File
Create a basic config file at App namespace
####Command
```$xslt
php spark make:config configuration_name
```
####Example
```$xslt
php spark make:config test
```
####Output
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
####Command
```$xslt
php spark make:config configuration_name -n namespace_name
```
####Example
```$xslt
php spark make:config test -n Example
```
####Output
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

##Controller File
Create a Basic controller file in App namespace
####Command
```$xslt
php spark make:controller controller_name
```
####Example
```$xslt
php spark make:controller test
```
####Output
```$xslt
<?php namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * @class Test
 * @author CI-Recharge
 * @package App
 * @created 16 November, 2020 06:11:44 AM
 */


class Test extends BaseController
{
    /**
     * Test constructor
     */
    public function __construct() 
    {
    
    }
    
    public function index()
    {
        echo 'Hello World!';
    }
}
```
Create a Basic controller file in Specific namespace
####Command
```$xslt
php spark make:controller controller_name -n namespace_name
```
####Example
```$xslt
php spark make:controller test3 -n Hafiz
```
####Output
```$xslt
<?php namespace Hafiz\Controllers;

use App\Controllers\BaseController;

/**
 * @class Test3
 * @author CI-Recharge
 * @package Hafiz
 * @created 16 November, 2020 06:14:24 AM
 */


class Test3 extends BaseController
{
    /**
     * Test3 constructor
     */
    public function __construct() 
    {
    
    }
    
    public function index()
    {
        echo 'Hello World!';
    }
}
```

Create a Basic controller file in Specific namespace & specific Base/Parent Controller
####Command
```$xslt
php spark make:controller controller_name -n namespace_name -b base_controller
```
####Example
```$xslt
php spark make:controller test3 -n Hafiz -b BaseController
```
####Output
```$xslt
<?php namespace Hafiz\Controllers;

use App\Controllers\BaseController;

/**
 * @class Test3
 * @author CI-Recharge
 * @package Hafiz
 * @created 16 November, 2020 06:16:53 AM
 */


class Test3 extends BaseController
{
    /**
     * Test3 constructor
     */
    public function __construct() 
    {
    
    }
    
    public function index()
    {
        echo 'Hello World!';
    }
}
```
Create a REST controller file in App namespace
####Command
```$xslt
php spark make:controller controller_name -rest
```
####Example
```$xslt
php spark make:controller test3 -rest
```
####Output
```$xslt
<?php namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\RESTful\ResourceController;

/**
 * @class Test3
 * @author CI-Recharge
 * @package App
 * @created 16 November, 2020 06:18:09 AM
 */


class Test3 extends ResourceController
{
    /**
     * Test3 constructor
     */
    public function __construct() 
    {
    
    }
    /**
     * @return array|string
     */
    public function index()
    {
        
        return view('');
    }
    
    /**
     * @return array|string
     */
    public function create()
    {
        
        return view('');
    }
    
    /**
     * @return array|string
     */
    public function store()
    {
        
        return ;
    }
    
    /**
     * @param int|null $id
     * @return array|string
     */
    public function show(int $id = null){
        
        return view('');
    }
    
    /**
     * @param int|null $id
     * @return array|string
     */
    public function edit(int $id = null)
    {
        
        return view('');
    }
    
    /**
     * @param int|null $id
     * @return array|string
     */
    public function update(int $id = null)
    {
        
        return ;
    }
    
    /**
     * @param int|null $id
     * @return array|string
     */
    public function delete(int $id = null)
    {
        
        return ;
    }   
}
```
##REST Controller can be made with specific Namespace and specific Base Controller

#Seeder File
Create Clean Seeder File in App namespace
####Command
```$xslt
php spark make:seed seeder_name
```
####Example
```$xslt
php spark make:seed test
```

####Output
```$xslt
<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Exception;
use ReflectionException;

/**
 * @class TestSeeder Seeder.
 * @author CI-Recharge
 * @package App
 * @created 16 November, 2020 08:06:05 AM
 */

class TestSeeder extends Seeder
{
    public function run()
    {
        $data = [];
    
        // Using Model
        $model = new ExampleModel();
        foreach ($data as $datum) {
            try {
                $model->save($datum);
            } catch (ReflectionException $e) {
                throw new Exception($e->getMessage());
            }
        }
        
        //Using Query Builder Class
        try {
            $this->db->table('users')->insertBatch($data);
        } catch (ReflectionException $e) {
                throw new Exception($e->getMessage());
        }
    }
}
```

Create Clean Seeder File in App namespace
####Command
```$xslt
php spark make:seed seeder_name
```
####Example
```$xslt
php spark make:seed test -n Hafiz
```

####Output
```$xslt
<?php namespace Hafiz\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Exception;
use ReflectionException;

/**
 * @class TestSeeder Seeder.
 * @author CI-Recharge
 * @package App
 * @created 16 November, 2020 08:06:05 AM
 */

class TestSeeder extends Seeder
{
    public function run()
    {
        $data = [];
    
        // Using Model
        $model = new ExampleModel();
        foreach ($data as $datum) {
            try {
                $model->save($datum);
            } catch (ReflectionException $e) {
                throw new Exception($e->getMessage());
            }
        }
        
        //Using Query Builder Class
        try {
            $this->db->table('users')->insertBatch($data);
        } catch (ReflectionException $e) {
                throw new Exception($e->getMessage());
        }
    }
}
```
