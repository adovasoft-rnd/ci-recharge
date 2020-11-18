<?php namespace Hafiz\Libraries;

use CodeIgniter\CLI\CLI;
use Config\Database;

/**
 * Class DBHandler
 * @package Hafiz\Libraries
 */
class DBHandler
{
    /**
     * @var array|\CodeIgniter\Database\BaseConnection|string|null
     */
    protected $db = null;

    /**
     * DBHandler constructor.
     * @param string|null $group
     */
    public function __construct(string $group = null)
    {
        try {
            $this->db = Database::connect($group);
            $this->db->initialize();
            exit(1);
        } catch (\Throwable $exception) {
            CLI::error($exception->getMessage());
            exit(1);
        }
    }

    /**
     * @return array
     */
    public function getTableNames(): ?array
    {
        $tables = $this->db->listTables();

        if (empty($tables)) {
            CLI::error(lang('Recharge.TablesNotFound'));
            exit(1);
        }

        return $tables;
    }

    public function checkTableExist(string $table): ?bool
    {
        if (!$this->db->tableExists($table)) {
            CLI::error(lang('Recharge.TableNotExists'));
            exit(1);
        }

        return true;
    }

    public function getTableInfos(string $table): string
    {
        //get Fields
        $fields = $this->generateField($table);

        $indexes = $this->generateKeys($table);

        $relations = $this->generateForeignKeys($table);

    }


    /**
     * @param string $table
     * @return string
     */
    protected function generateField(string $table): ?string
    {
        $query = $this->db->query("DESCRIBE $table")->getResult();
        $fieldString = '';
        $singleField = '';
        foreach ($query as $field) {
            $singleField = "\n\t\t\t'$field->Field' => ['";
            $singleField .= "\n\t\t\t\t'null' => " . (($field->Null == 'YES') ? 'tru,e' : 'false,');
            $singleField .= "\n\t\t\t\t'default' => " . (is_null($field->Default) ? NULL : "'$field->Default',");

            //Type
            if (preg_match('/^([a-z]+)/', $field->Type, $matches) > 0)
                $singleField .= "\n\t\t\t\t'type' => '" . strtoupper($matches[1]) . "',";

            //Constraint
            if (preg_match('/\((.+)\)/', $field->Type, $matches) > 0) {
                if (is_numeric($matches[1]))
                    $singleField .= "\n\t\t\t\t'constraint' => '" . $matches[1] . "',";
                else {
                    //Enum Fields
                    $values = explode(',', $matches[1]);
                    if (count($values) == 1) {
                        $singleField .= "\n\t\t\t\t'constraint' => ['" . $values[0] . "'],";
                    } else {
                        $enums = array_map(function ($val) {
                            return "'" . $val . "'";
                        }, $values);
                        $values = implode(',', $enums);
                        $singleField .= "\n\t\t\t\t'constraint' => [" . $values . "],";
                    }
                }
            }

            //unsigned
            if (strpos($field->Type, 'unsigned') !== false)
                $singleField .= "\n\t\t\t\t'unsigned' => true,";

            //Unique Key
            if ($field->Key == 'UNI')
                $singleField .= "\n\t\t\t\t'unique' => true,";

            //autoincrement
            if (strpos($field->Extra, 'auto_increment') !== false)
                $singleField .= "\n\t\t\t\t'unique' => true,";


            $singleField .= "\n\t\t\t\t],";
            $fieldString .= $singleField;
        }

        return $fieldString;
    }

    /**
     * @param string $table
     * @return string|null
     */
    protected function generateKeys(string $table): ?string
    {
        $keys = $this->db->getIndexData('table_name');

        foreach ($keys as $key) {
            echo $key->name;
            echo $key->type;
            echo $key->fields;  // array of field names
        }

        return;
    }

    /**
     * @param string $table
     * @return string|null
     */
    protected function generateForeignKeys(string $table): ?string
    {
        $keys = $this->db->getForeignKeyData('table_name');

        foreach ($keys as $key) {
            echo $key->constraint_name;
            echo $key->table_name;
            echo $key->column_name;
            echo $key->foreign_table_name;
            echo $key->foreign_column_name;
        }

        return;
    }

    protected function generateRowArray(string $table): ?string
    {
        $result = $this->db->table($table)->get()->getResult();
        $container = "[\n";
        foreach ($result as $row) {
            $temp = "\n[ ";
            foreach ($row as $index => $value) {
                $temp .= "'$index' => '$value', ";
            }
            $temp .= "],\n";
            $container .= $temp;
        }

        return $container;
    }

}