# Implementation Of Model File

#### Create a Model file

##### Command
```$xslt
php spark make:model model_Name
```
##### Example
```
php spark make:model User
```
##### Output
```
<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * @class UserModel.
 * @author CI-Recharge
 * @package App
 * @extend Model
 * @created 17 November, 2020 05:35:59 AM
 */

class UserModel extends Model
{
    /**
     * Table Configuration
     */
    //protected $DBGroup = '';
    protected $table      = '';
    protected $primaryKey = 'id';
    
    /**
     * Model & Table Column Customization
     */
    protected $allowedFields = ['field1', 'field2'];
    protected $useTimestamps = false;
    //protected $dateFormat = '';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    /**
     * Return Configuration
     */
    protected $returnType     = 'object';
    protected $useSoftDeletes = true;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;

    /**
     * Event /Observer Configurations
     */
    protected $allowCallbacks = true;
    
    protected $beforeInsert = [];
    
    protected $afterInsert = [];
    
    protected $beforeUpdate = [];
    
    protected $afterUpdate = [];
    
    protected $afterFind = [];
    
    protected $afterDelete = [];
}

```
#### Create a model file specific namespace
##### Command
```$xslt
php spark make:model model_name -n namespace_name
```
##### Example
```
php spark make:model User -n robin
```

##### Output
```
<?php namespace robin\Models;

use CodeIgniter\Model;

/**
 * @class UserModel.
 * @author CI-Recharge
 * @package robin
 * @extend Model
 * @created 17 November, 2020 05:37:47 AM
 */

class UserModel extends Model
{
    /**
     * Table Configuration
     */
    //protected $DBGroup = '';
    protected $table      = '';
    protected $primaryKey = 'id';
    
    /**
     * Model & Table Column Customization
     */
    protected $allowedFields = ['field1', 'field2'];
    protected $useTimestamps = false;
    //protected $dateFormat = '';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    /**
     * Return Configuration
     */
    protected $returnType     = 'object';
    protected $useSoftDeletes = true;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;

    /**
     * Event /Observer Configurations
     */
    protected $allowCallbacks = true;
    
    protected $beforeInsert = [];
    
    protected $afterInsert = [];
    
    protected $beforeUpdate = [];
    
    protected $afterUpdate = [];
    
    protected $afterFind = [];
    
    protected $afterDelete = [];
}

```