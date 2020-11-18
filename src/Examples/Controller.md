# Implementation Of Controller File

#### Create a Basic controller file in App namespace
##### Command
```$xslt
php spark make:controller controller_name
```
##### Example
```$xslt
php spark make:controller test
```
##### Output
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
#### Create a Basic controller file in Specific namespace
##### Command
```$xslt
php spark make:controller controller_name -n namespace_name
```
##### Example
```$xslt
php spark make:controller test3 -n Hafiz
```
##### Output
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

#### Create a Basic controller file in Specific namespace & specific Base/Parent Controller
##### Command
```$xslt
php spark make:controller controller_name -n namespace_name -b base_controller
```
##### Example
```$xslt
php spark make:controller test3 -n Hafiz -b BaseController
```
##### Output
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
#### Create a REST controller file in App namespace
##### Command
```$xslt
php spark make:controller controller_name -rest
```
##### Example
```$xslt
php spark make:controller test3 -rest
```
##### Output
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
##### Create a REST Controller with Namespace

##### Command
```
php spark make:controller controller_name -n namespace_name -rest
```
##### Example
```
php spark make:controller TextForm -n robin -rest
```
##### Output
```
<?php namespace robin\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\RESTful\ResourceController;

/**
 * @class TextForm
 * @author CI-Recharge
 * @package App
 * @extends ResourceController
 * @created 17 November, 2020 04:20:40 AM
 */


class TextForm extends ResourceController
{
    /**
     * TextForm constructor
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
#### Create a Rest controller file specific Base/Parent Controller
##### Command
```$xslt
php spark make:controller cont -b baseController_name -rest
```
##### Example
```
php spark make:controller Example2 -b InitController -rest
```

##### Output
```
<?php namespace App\Controllers;

use App\Controllers\InitController;
use CodeIgniter\RESTful\ResourceController;

/**
 * @class Example2
 * @author CI-Recharge
 * @package App
 * @extends ResourceController
 * @created 17 November, 2020 04:50:47 AM
 */


class Example2 extends ResourceController
{
    /**
     * Example2 constructor
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