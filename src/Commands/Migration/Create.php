<?php namespace Hafiz\Commands\Migration;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

/**
 * Creates a new migration file.
 *
 * @package CodeIgniter\Commands
 */
class Create extends BaseCommand
{

    /**
     * The group the command is lumped under
     * when listing commands.
     *
     * @var string
     */
    protected $group = 'CI4-Recharge';

    /**
     * The Command's name
     *
     * @var string
     */
    protected $name = 'make:migrate';

    /**
     * the Command's short description
     *
     * @var string
     */
    protected $description = 'Creates a migration file.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'migrate:create [migration_name] [Options]';

    /**
     * the Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'migration_name' => 'The migration file name',
    ];

    /**
     * the Command's Options
     *
     * @var array
     */
    protected $options = [
        '-n' => 'Set migration namespace',
    ];

    /**
     * Creates a new migration file with the current timestamp.
     *
     * @param array $params
     */
    public function run(array $params = [])
    {
        helper(['inflector', 'filesystem']);
        $name = array_shift($params);

        if (empty($name)) {
            $name = CLI::prompt(lang('Recharge.nameMigration'));
        }

        if (empty($name)) {
            CLI::error(lang('Recharge.badMigrationName'));
            return;
        }

        $ns = $params['-n'] ?? CLI::getOption('n');
        $homepath = APPPATH;

        if (!empty($ns)) {
            // Get all namespaces
            $namespaces = Services::autoloader()->getNamespace();

            foreach ($namespaces as $namespace => $path) {
                if ($namespace === $ns) {
                    $homepath = realpath(reset($path));
                    break;
                }
            }
        } else {
            $ns = 'App';
        }

        // Always use UTC/GMT so global teams can work together
        $config = config('Migrations');
        $fileName = gmdate($config->timestampFormat) . $name;

        // full path
        $path = $homepath . '/Database/Migrations/' . $fileName . '.php';

        // Class name should be pascal case now (camel case with upper first letter)
        $table = underscore($name);
        $name = pascalize($name);
        $date = date("d F, Y h:i:s A");

        $template = <<<EOD
<?php namespace $ns\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * @class $name
 * @author CI-Recharge
 * @package $ns
 * @created $date
 */
class {name} extends Migration
{
	public function up()
	{
	    \$this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            
            
            
            'created_at' => [
                'type' => 'DATETIME',
                'null' > false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'default' => null,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'default' => null,
            ],
        ]);
        
        \$this->forge->addPrimaryKey(['id']); // Primary Key
        \$this->forge->addKey([]); // Index Key
        \$this->forge->addForeignKey(); //Foreign Key
        
        \$this->forge->createTable('$table');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		\$this->forge->dropTable('$table');
	}
}

EOD;
        $template = str_replace('{name}', $name, $template);

        if (!write_file($path, $template)) {
            CLI::error(lang('Recharge.writeError', [$path]));
            return;
        }

        CLI::write('Created file: ' . CLI::color(str_replace($homepath, $ns, $path), 'green'));
    }

}
