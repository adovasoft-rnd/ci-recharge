# Implementation Of Seeder File

Create Clean Seeder File in App namespace
#### Command
```$xslt
php spark make:seed seeder_name
```
#### Example
```$xslt
php spark make:seed test
```

#### Output
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
#### Command
```$xslt
php spark make:seed seeder_name
```
#### Example
```$xslt
php spark make:seed test -n Hafiz
```

#### Output
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