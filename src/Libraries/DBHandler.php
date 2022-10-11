<?php namespace Hafiz\Libraries;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\BaseConnection;
use Config\Database;
use Throwable;

/**
 * @class DBHandler
 * Handle all db collection and table
 * column generate
 *
 * @author hafijul233
 *
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
     *
     * @param string|null $group
     */
    public function __construct(string $group = null)
    {
        try {
            $this->db = Database::connect($group);
            $this->db->initialize();
        } catch (Throwable $exception) {
            CLI::error($exception->getMessage());
            die();
        }
    }

    /**
     *
     */
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
     * Return a list of All tables
     * Name from a specific database group
     * or default on
     *
     * @return array
     */
    public function getTableNames(): array
    {
        $tables = $this->db->listTables() ?? [];

        if (empty($tables)) {
            CLI::error(lang('Recharge.TablesNotFound'));
            exit(1);
        }

        return $tables;
    }

    /**
     * return a list of all fields and
     * key generated from a table
     *
     * @param string $table
     *
     * @return array
     */
    public function getTableInfos(string $table): array
    {
        $fields = $this->generateField($table);

        $indexes = $this->generateKeys($table);

        $relations = $this->generateForeignKeys($table);

        return ['attributes' => $fields,
            'keys' => $indexes . "\n" . $relations
        ];
    }

    /**
     * Generate Field array form a table
     *
     * @param string $table
     *
     * @return string
     */
    protected function generateField(string $table): ?string
    {
        $query = $this->db->query("DESCRIBE $table")->getResult();
        $fieldString = '';

        foreach ($query as $field) {
            $singleField = "\n\t\t'$field->Field' => [";
            //Type
            if (preg_match('/^([a-z]+)/', $field->Type, $matches) > 0)
                $singleField .= "\n\t\t\t'type' => '" . strtoupper($matches[1]) . "',";

            //Constraint
            if (preg_match('/\((.+)\)/', $field->Type, $matches) > 0) {
                //integer , varchar
                if (is_numeric($matches[1]))
                    $singleField .= "\n\t\t\t'constraint' => " . $matches[1] . ",";

                //float , double
                elseif (preg_match('/[\d]+\s?,[\d]+\s?/', $matches[1]) > 0)
                    $singleField .= "\n\t\t\t'constraint' => '" . $matches[1] . "',";

                //Enum Fields
                else {
                    $values = explode(',', str_replace("'", "", $matches[1]));

                    if (count($values) == 1)
                        $singleField .= "\n\t\t\t'constraint' => [" . $this->getGluedString($values) . "],";

                    else
                        $singleField .= "\n\t\t\t'constraint' => " . $this->getGluedString($values) . ",";
                }
            }

            //if field need null
            $singleField .= "\n\t\t\t'null' => " . (($field->Null == 'YES') ? 'true,' : 'false,');

            if (!is_null($field->Default) && (strpos($field->Default, 'current_timestamp()') === FALSE))
                $singleField .= "\n\t\t\t'default' => '$field->Default',";

            //unsigned
            if (strpos($field->Type, 'unsigned') !== false)
                $singleField .= "\n\t\t\t'unsigned' => true,";

            //autoincrement
            if (strpos($field->Extra, 'auto_increment') !== false)
                $singleField .= "\n\t\t\t'auto_increment' => true,";

            $singleField .= "\n\t\t],";
            $fieldString .= $singleField;
        }

        return $fieldString;
    }

    /**
     * Glue a array into a single string
     *
     * @param array $arr
     *
     * @param bool $is_assoc
     *
     * @return string
     * @author hafijul233
     *
     */
    protected function getGluedString(array $arr, bool $is_assoc = false): string
    {

        //array consist of one element
        if (count($arr) == 1)
            return "'" . strval(array_shift($arr)) . "'";

        else {

            $str = '';
            if (!$is_assoc) {
                foreach ($arr as $item) {
                    if (strlen($item) > 0)
                        $str .= "'$item', ";
                }

            } else {
                foreach ($arr as $index => $item) {
                    if (strlen($item) > 0)
                        $str .= "'$index' => '$item',";
                }
            }

            return "[ " . rtrim($str, ', ') . "]";
        }
    }

    /**
     * @param string $table
     *
     * @return string|null
     */
    protected function generateKeys(string $table): ?string
    {
        $index = $this->db->getIndexData($table);

        $keys = [];
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
     * @return bool|null
     */
    public function checkTableExist(string $table): ?bool
    {
        if (!$this->db->tableExists($table)) {
            CLI::error(lang('Recharge.TableNotExists'));
            exit(1);
        }

        return true;
    }

    /**
     * @param string $table
     * @return string|null
     */
    public function generateRowArray(string $table): ?string
    {
        $result = $this->db->table($table)->get()->getResult();
        $container = "";
        foreach ($result as $row) {
            $temp = "\n\t\t\t[";
            foreach ($row as $index => $value) {
                $temp .= "'$index' => '" . addslashes($value) . "', ";
            }
            $temp .= "],";
            $container .= $temp;
        }

        return $container;
    }

    /**
     * @param string $table
     * @return array
     */
    public function getEntityProperties(string $table): array
    {
        $attributes = [];
        $dates = [];
        $casts = [];

        $fields = $this->db->getFieldData($table);

        foreach ($fields as $field) {
            $attributes[] = $field->name;

            //property dates
            if ($field->type == 'datetime') {
                $dates[] = $field->name;
                $casts[$field->name] = (($field->nullable) ? "?" : '') . "datetime";
            }

            //property cast
            if ($field->type == 'tinyint')
                $casts[$field->name] = (($field->nullable) ? "?" : '') . "boolean";

        }

        return [
            'attributes' => str_replace(['[', ']'], '', $this->getGluedString($attributes)),
            'dates' => str_replace(['[', ']'], '', $this->getGluedString($dates)),
            'casts' => str_replace(['[', ']'], '', $this->getGluedString($casts, true)),
        ];
    }

    /**
     * @param string $table
     * @return array
     */
    public function getModelProperties(string $table): array
    {
        $primary_ids = [];
        $attributes = [];

        $fields = $this->db->getFieldData($table);

        foreach ($fields as $field) {
            if ($field->primary_key === 1)
                $primary_ids[] = $field->name;

            elseif ($field->name == 'created_at' || $field->name == 'updated_at')
                continue;

            else
                $attributes[] = $field->name;
        }

        //Model only support single column in primary key getting the first one

        $primary_id = (count($primary_ids) > 0) ? array_shift($primary_ids) : '';
        $allowed_fields = array_merge($primary_ids, $attributes);

        return [
            'primary_id' => $primary_id,
            'attributes' => str_replace(['[', ']'], '', $this->getGluedString($allowed_fields)),
            'rules' => $this->validationRules($fields),
        ];

    }

    /**
     * Takes the information from getFieldData() and creates the basic
     * validation rules for those fields.
     *
     * @param array $fields
     *
     * @return mixed|string
     */
    public function validationRules(array $fields)
    {
        if (empty($fields)) return '[]';

        $rules = [];

        foreach ($fields as $field) {
            if (in_array($field->name, ['created_at', 'updated_at']))
                continue;

            $rule = [];

            if ($field->nullable == false) {
                $rule[] = "required";
            } else {
                $rule[] = "permit_empty";
            }

            switch ($field->type) {
                // Numeric Types
                case 'tinyint':
                case 'smallint':
                case 'mediumint':
                case 'int':
                case 'integer':
                case 'bigint':
                    $rule[] = 'integer';
                    break;

                case 'decimal':
                case 'dec':
                case 'numeric':
                case 'fixed':
                    $rule[] = 'decimal';
                    break;

                case 'float':
                case 'double':
                    $rule[] = 'numeric';
                    break;

                case 'date':
                    $rule[] = 'valid_date';
                    break;

                // Text Types
                case 'char':
                case 'varchar':
                case 'text':
                    $rule[] = 'string';
                    break;
            }

            if (!empty($field->max_length)) {
                $rule[] = "max_length[$field->max_length]";
            }

            $rules[$field->name] = implode('|', $rule);
        }

        return $this->getGluedString($rules, true);
    }
}
