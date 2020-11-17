<?php namespace Hafiz\Commands\Filter;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

/**
 * Creates a new Filter file.
 * @package CodeIgniter\Commands
 * @extend BaseCommand
 */
class Create extends BaseCommand
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
    protected $name = 'make:filter';

    /**
     * The Command's short description
     * @var string
     */
    protected $description = 'Creates a filter file.';

    /**
     * The Command's usage
     * @var string
     */
    protected $usage = 'make:filter [filter_name] [Options]';

    /**
     * The Command's Arguments
     * @var array
     */
    protected $arguments = [
        'filter_name' => 'The Configuration file name',
    ];

    /**
     * The Command's Option
     * @var array
     */
    protected $options = [
        '-n' => 'Set Configuration namespace',
    ];

    /**
     * Creates a new Filter file with the current timestamp.
     * @param array $params
     * @return void
     */
    public function run(array $params = [])
    {
        helper(['inflector', 'filesystem']);

        $name = array_shift($params);

        if (empty($name)) {
            $name = CLI::prompt(lang('Recharge.nameFilter'));
        }

        if (empty($name)) {
            CLI::error(lang('Recharge.badFilterName'));
            return;
        }

        $ns = $params['-n'] ?? CLI::getOption('n');
        /** @var string real path $homepath */
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
            $ns = "App";
        }


        // Always use UTC/GMT so global teams can work together
        $fileName = pascalize($name) . ((stripos('seeder', $name) == false) ? 'Filter' : '');

        // full path
        $path = $homepath . '/Filters/' . $fileName . '.php';

        // Class name should be Pascal case
        $name = $fileName;
        $date = date("d F, Y h:i:s A");
        $template = <<<EOD
<?php namespace $ns\Filters;


use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Services;

/**
 * @class $name 
 * @author CI-Recharge
 * @package $ns
 * @extend Filter.
 * @created $date
 */

class $name implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface \$request
     *
     * @param null \$arguments
     * @return mixed
     */
    public function before(RequestInterface \$request, \$arguments = null)
    {
        //Validator Instants
        \$validator = Services::validation();
        
        //Session Instants
        \$session = Services::session();
        
    }

    //--------------------------------------------------------------------

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface \$request
     * @param ResponseInterface \$response
     * @param null \$arguments
     *
     * @return mixed
     */
    public function after(RequestInterface \$request, ResponseInterface \$response, \$arguments = null)
    {
        //
    }
}

EOD;

        if (!write_file($path, $template)) {
            CLI::error(lang('Recharge.writeError', [$path]));
            return;
        }

        CLI::write('Created file: ' . CLI::color(str_replace($homepath, $ns, $path), 'green'));
    }

}
