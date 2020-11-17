# Implementation Of Seeder File

Create Clean Seeder File in App namespace
#### Command
```$xslt
php spark make:seed seeder_name
```
#### Example
```$xslt
php spark make:seed Test
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
        $model = new TestModel();
        foreach ($data as $datum) {
            try {
                $model->save($datum);
            } catch (ReflectionException $e) {
                throw new Exception($e->getMessage());
            }
        }
        
        //Using Query Builder Class
        try {
            $this->db->table('table_name')->insertBatch($data);
        } catch (ReflectionException $e) {
                throw new Exception($e->getMessage());
        }
    }
}
```
#### Create a Seed file specific namespace
##### Command
```$xslt
php spark make:seed seed_name -n namespace_name
```
##### Example
```
php spark make:seed Country -n robin
```
##### Output
```
<?php namespace robin\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Exception;
use ReflectionException;

/**
 * @class CountrySeeder
 * @author CI-Recharge
 * @package robin
 * @extend Seeder
 * @created 17 November, 2020 05:42:58 AM
 */

class CountrySeeder extends Seeder
{
    public function run()
    {
        $data = [];
    
        // Using Model
        $model = new CountryModel();
        foreach ($data as $datum) {
            try {
                $model->save($datum);
            } catch (ReflectionException $e) {
                throw new Exception($e->getMessage());
            }
        }
        
        //Using Query Builder Class
        try {
            $this->db->table('table_name')->insertBatch($data);
        } catch (ReflectionException $e) {
                throw new Exception($e->getMessage());
        }
    }
}

```
