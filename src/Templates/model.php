<?php namespace {namespace}\Models;

use CodeIgniter\Model;

/**
 * Short description of this class usages
 *
 * @class {name}
 * @generated_by CI-Recharge
 * @package {namespace}
 * @extend Model
 * @created_at {created_at}
 */

class {name} extends Model
{
    /**
     * Table Configuration
     */
    protected $table = '{table}';
    protected $primaryKey = '{primary_id}';

    /**
     * Model & Table Column Customization
     */
    protected $allowedFields = [{attributes}];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    {delete_field}

    /**
     * Return Configuration
     */
    protected $returnType = 'object';
    protected $useSoftDeletes = {soft_delete};
    protected $validationRules = {rules};
    protected $validationMessages = [];
    protected $skipValidation = true;

    /**
     * Events Configurations
     */
    protected $beforeInsert = [];

    protected $afterInsert = [];

    protected $beforeUpdate = [];

    protected $afterUpdate = [];

    protected $afterFind = [];

    protected $afterDelete = [];
}

