<?php namespace Hafiz\Libraries;

use CodeIgniter\CLI\CLI;
use Config\Database;

/**
 * Class DBHandler
 * @package Hafiz\Libraries
 */
class DBHandler
{
    protected $db = null;

    public function __construct(string $group = null)
    {
        try {
            $this->db = Database::connect();
            $this->db->initialize();
            exit(1);
        } catch (\Throwable $exception) {
            CLI::error($exception->getMessage());
            exit(1);
        }
    }

    public function getTableNames(): array
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

    public function getTableFields(string $table): string
    {

    }

}