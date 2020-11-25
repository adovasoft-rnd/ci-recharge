<?php namespace {namespace}\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Exception;
use ReflectionException;

/**
 * Short description of this class usages
 *
 * @class {name}
 * @generated_by CI-Recharge
 * @package {namespace}
 * @extend Entity
 * @created_at {created_at}
 */

class {name} extends Seeder
{
    public function run()
    {
        $data = [{seeder}];

        // Using Model
        /*$model = new {name}Model();
        foreach ($data as $datum) {
            try {
                $model->save($datum);
            } catch (ReflectionException $e) {
                throw new Exception($e->getMessage());
            }
        }*/

        //Using Query Builder Class
        try
            $this->db->table('{table}')->insertBatch($data);

        catch (ReflectionException $e)
            throw new Exception($e->getMessage());
    }
}