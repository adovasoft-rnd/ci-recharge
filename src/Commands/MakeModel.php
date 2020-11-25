<?php namespace Hafiz\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Hafiz\Libraries\DBHandler;
use Hafiz\Libraries\FileHandler;

/**
 * Make Command Class for a skeleton Model Class
 * model Class that collect property from table
 *
 * @package CodeIgniter\Commands
 * @extend BaseCommand
 */
class MakeModel extends BaseCommand
{

    /**
     * The group command is heading under all
     * commands will be listed
     * @var string
     */
    protected $group = 'CI4-Recharge';

    /**
     * The Command's name
     * @var string
     */
    protected $name = 'make:model';

    /**
     * The Command's short description
     * @var string
     */
    protected $description = 'Creates a Model file.';

    /**
     * The Command's usage
     * @var string
     */
    protected $usage = 'make:model [model_name] [Options]';

    /**
     * The Command's Arguments
     * @var array
     */
    protected $arguments = [
        'model_name' => 'The Model file name',
    ];

    /**
     * The Command's Options
     * @var array
     */
    protected $options = [
        '-n' => 'Set model namespace',
        '-t' => 'Set Model Database table',
    ];

    /**
     * Creates a new entity file with the current timestamp.
     * @param array $params
     * @return void
     */
    public function run(array $params = [])
    {
        helper(['inflector', 'filesystem']);
        $file = new FileHandler();

        $name = array_shift($params);
        $ns = $params['-n'] ?? CLI::getOption('n');
        $table = $params['-t'] ?? CLI::getOption('t');

        if (empty($name)) {
            $name = CLI::prompt(lang('Recharge.modelName'), null, 'required|string');
        }

        if (empty($name)) {
            CLI::error(lang('Recharge.badName'));
            return;
        }

        //namespace locator
        $nsinfo = $file->getNamespaceInfo($ns, 'App');

        //class & file name
        $ns = $nsinfo['ns'];
        $targetDir = $nsinfo['path'] . '/Models/';
        $name = singular(pascalize($name)) . (stripos($name, 'Model') === FALSE ? 'Model' : '');
        $filepath = $targetDir . $name . '.php';

        if ($file->verifyDirectory($filepath)) {
            //do we have to add table info
            if (!empty($table)) {
                $db = new DBHandler();
                if ($db->checkTableExist($table)) {
                    $properties = $db->getModelProperties($table);
                    extract($properties);
                }
            }

            //for soft deleted option
            $softDelete = 'false';
            $deleteField = '';
            $validationRules = '[]';

            if (isset($attributes)) {
                if (stripos($attributes, 'deleted_at') !== false) {
                    $softDelete = 'true';
                    $deleteField = "protected \$deletedField  = 'deleted_at';";
                }
            }
            if (isset($rules)) {
                $validationRules = $rules;
            }

            $data = [
                '{namespace}' => $ns,
                '{name}' => $name,
                '{created_at}' => date("d F, Y h:i:s A"),
                '{attributes}' => $attributes ?? NULL,
                '{table}' => $table ?? NULL,
                '{primary_id}' => $primary_id ?? NULL,
                '{delete_field}' => $deleteField,
                '{soft_delete}' => $softDelete,
                '{rules}' => $validationRules,
            ];

            //check a directory exist
            if ($file->checkFileExist($filepath) == true) {
                $template = $file->renderTemplate('model', $data);


                if (!write_file($filepath, $template)) {
                    CLI::error(lang('Recharge.writeError', [$filepath]));
                    return;
                }

                CLI::write('Created file: ' . CLI::color($filepath, 'green'));
            }
        }
    }
}
