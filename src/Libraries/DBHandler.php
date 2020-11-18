<?php namespace Hafiz\Libraries;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\BaseConnection;
use Config\Database;

/**
 * Class DBHandler
 * @package Hafiz\Libraries
 */
class DBHandler
{
    /**
     * @var array|BaseConnection|string|null
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
        } catch (\Throwable $exception) {
            CLI::error($exception->getMessage());
            exit(1);
        }
    }

    /**
     * @param string $table
     */
    public function generateSingleMigration(string $table): void
    {
        $tableInfo = $this->getTableInfos($table);

        $file = new FileHandler();

        $file->writeTable($table, $tableInfo[0], $tableInfo[1]);
    }


    public function generateDBMigration(): void
    {
        $tables = $this->getTableNames();
        foreach ($tables as $table) {
            $tableInfo = $this->getTableInfos($table);

            $file = new FileHandler();

            $file->writeTable($table, $tableInfo[0], $tableInfo[1]);
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

    public function getTableInfos(string $table): array
    {
        $fields = $this->generateField($table);

        $indexes = $this->generateKeys($table);

        $relations = $this->generateForeignKeys($table);

        return [$fields, $indexes, $relations];
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
            $singleField = "\n\t\t'$field->Field' => [";
            //Type
            if (preg_match('/^([a-z]+)/', $field->Type, $matches) > 0)
                $singleField .= "\n\t\t\t'type' => '" . strtoupper($matches[1]) . "',";

            //Constraint
            if (preg_match('/\((.+)\)/', $field->Type, $matches) > 0) {
                if (is_numeric($matches[1]) || preg_match('/[\d]+\s?,[\d]+\s?/', $matches[1]) > 0)
                    $singleField .= "\n\t\t\t'constraint' => '" . $matches[1] . "',";
                else {
                    //Enum Fields
                    $values = explode(',', $matches[1]);
                    if (count($values) == 1) {
                        $singleField .= "\n\t\t\t'constraint' => ['" . $values[0] . "'],";
                    } else {
                        $enums = array_map(function ($val) {
                            return "'" . $val . "'";
                        }, $values);
                        $values = implode(',', $enums);
                        $singleField .= "\n\t\t\t'constraint' => [" . $values . "],";
                    }
                }
            }

            $singleField .= "\n\t\t\t'null' => " . (($field->Null == 'YES') ? 'true,' : 'false,');

            if (strpos($field->Default, 'current_timestamp()') === FALSE)
                $singleField .= "\n\t\t\t'default' => '$field->Default',";

            //unsigned
            if (strpos($field->Type, 'unsigned') !== false)
                $singleField .= "\n\t\t\t'unsigned' => true,";

            //Unique Key
            if ($field->Key == 'UNI')
                $singleField .= "\n\t\t\t'unique' => true,";

            //autoincrement
            if (strpos($field->Extra, 'auto_increment') !== false)
                $singleField .= "\n\t\t\t'unique' => true,";


            $singleField .= "\n\t\t],";
            $fieldString .= $singleField;
        }

        return $fieldString;
    }

    /**
     * @param array $arr
     * @return string
     */
    protected function getGluedString(array $arr): string
    {
        //array consist of one element
        if (count($arr) == 1)
            return "'" . strval(array_shift($arr)) . "'";

        else {
            $str = '';
            foreach ($arr as $item)
                $str .= "'$item', ";

            return "[ " . rtrim($str, ', ') . "]";
        }
    }

    /**
     * @param string $table
     * @return string|null
     */
    protected function generateKeys(string $table): ?string
    {
        $index = $this->db->getIndexData($table);

        $keys['primary'] = '';
        $keys['foreign'] = '';
        $keys['unique'] = '';

        foreach ($index as $key) {
            switch ($key->type) {
                case 'PRIMARY' :
                {
                    $keys['primary'] = "\n\t\t\$this->forge->addPrimaryKey(" .
                        $this->getGluedString($key->fields) . ");";
                    break;
                }
                case 'UNIQUE' :
                {
                    $keys['unique'] .= "\n\t\t\$this->forge->addUniqueKey(" .
                        $this->getGluedString($key->fields) . ");";
                    break;
                }
                default :
                {
                    $keys['foreign'] .= "\n\t\t\$this->forge->addKey(" .
                        $this->getGluedString($key->fields) . ");";
                    break;
                }
            }
        }
        return implode("\n", $keys);
    }

    /**
     * @param string $table
     * @return string|null
     */
    protected function generateForeignKeys(string $table): ?string
    {
        $keys = $this->db->getForeignKeyData($table);
        $keyArray = [];
        foreach ($keys as $key)
            array_push($keyArray, "\n\t\t\$this->forge->addForeignKey('$key->column_name','$key->foreign_table_name','$key->foreign_column_name','CASCADE','CASCADE');");

        return implode('', array_unique($keyArray));
    }

    /**
     * @param string $table
     * @return string|null
     */
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