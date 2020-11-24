<?php

use const Database\Seeds;

/**
 * Short description of this class usages
 *
 * @class {name}
 * @generated_by CI-Recharge
 * @package {namespace}
 * @extend Entity
 * @created_at {created_at}
 */

class
{
name
}

Seeds;

namespace {

    namespace} extends Seeder
{
    public
    function run()
    {
        $data = [
            {
                seeder}
        ]

        // Using Model
        /*$model = new $nameModel();
        foreach ($data as $datum) {
            try {
                $model->save($datum);
            } catch (ReflectionException $e) {
                throw new Exception($e->getMessage());
            }
        }*/

        //Using Query Builder Class
        try {
            $this->db->table('{table}')->insertBatch($data);

        } catch (ReflectionException $e) {
            throw new Exception($e->getMessage());
        }
    }
}