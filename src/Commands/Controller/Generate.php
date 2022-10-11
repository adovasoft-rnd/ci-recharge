<?php namespace Hafiz\Commands\Controller;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;
use ReflectionClass;
use ReflectionException;

/**
 * Creates a new Controller file.
 * @package CodeIgniter\Commands
 * @extend BaseCommand
 */
class Generate extends BaseCommand
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
    protected $name = 'generate:controller';

    /**
     * The Command's short description
     * @var string
     */
    protected $description = 'Creates a controller from Model.';

    /**
     * The Command's usage
     * @var string
     */
    protected $usage = 'generate:controller [controller_name] [Options]';

    /**
     * The Command's Arguments
     * @var array
     */
    protected $arguments = [
        'controller_name' => 'The Controller file name'
    ];

    /**
     * The Command's Options
     * @var array
     */
    protected $options = [
        '-n' => 'Set controller namespace',
        '-b' => 'Set controller base/extends class',
        '-s' => 'Set controller sub-directory after namespace',
        '-rest' => 'Set controller sub-directory after namespace',
    ];

    /**
     * Creates a new Controller file with the current timestamp.
     * @param array $params
     * @return void
     * @throws ReflectionException
     */
    public function run(array $params = [])
    {
        helper(['inflector', 'filesystem']);

        $name = array_shift($params);

        if (empty($name)) {
            $name = CLI::prompt(lang('Recharge.nameController'));
        }

        if (empty($name)) {
            CLI::error(lang('Recharge.badControllerName'));
            return;
        }

        //Class Namespace
        $ns = $params['-n'] ?? CLI::getOption('n');

        //Extends Base Class
        $base = $params['-b'] ?? CLI::getOption('b');

        //Sub directory after Namespace
        $sub = $params['-s'] ?? CLI::getOption('s');

        //Rest Controller Style
        $is_rest = CLI::getOption('rest');

        /** @var string real path $homepath */
        $homepath = APPPATH;

        $package = "App";
        $baseNameSpace = 'use App\Controllers\BaseController;';
        $parentController = 'BaseController';

        //Finding Namespace Location
        if (!empty($ns)) {
            // Get all namespaces
            $namespaces = Services::autoloader()->getNamespace();

            foreach ($namespaces as $namespace => $path) {
                if ($namespace === $ns) {
                    $homepath = realpath(reset($path));
                    $package = $ns;
                    break;
                }
            }
        } else {
            $ns = "App";
        }

        //Finding Base Class
        if (!empty($base)) {
            // Get all namespaces
            $namespaces = Services::autoloader()->getNamespace();

            foreach ($namespaces as $namespace => $path) {
                if ($namespace == "App" || $namespace == $ns) {
                    $full_path = realpath(reset($path)) . "/Controllers/" . $base . ".php";
                    if (file_exists($full_path)) {
                        $tempObj = new ReflectionClass($namespace . "\Controllers\\" . $base);
                        $baseNameSpace = 'use ' . $tempObj->getName() . ";";
                        $package = $ns;
                        break;
                    }
                }
            }
        } else {
            $base = $parentController;
        }

        // Always use UTC/GMT so global teams can work together
        $fileName = pascalize($name);

        // full path
        $path = $homepath . '/Controllers/' . $fileName . '.php';

        // Class name should be Pascal case
        $name = pascalize($name);
        $date = date("d F, Y h:i:s A");

        //Basic Controller Template
        $basicTemplate = <<<EOD
<?php namespace $ns\Controllers;

$baseNameSpace

/**
 * @class $name
 * @author CI-Recharge
 * @package $package
 * @extend $base
 * @created $date
 */


class $name extends $base
{
    /**
     * $name constructor
     */
    public function __construct() 
    {
    
    }
    
    public function index()
    {
        echo 'Hello World!';
    }
}

EOD;

        //REST Controller Template
        $restTemplate = <<<EOD
<?php namespace $ns\Controllers;

$baseNameSpace
use CodeIgniter\RESTful\ResourceController;

/**
 * @class $name
 * @author CI-Recharge
 * @package $package
 * @extends ResourceController
 * @created $date
 */


class $name extends ResourceController
{
    /**
     * $name constructor
     */
    public function __construct() 
    {
    
    }
    /**
     * @return array|string
     */
    public function index()
    {
        
        return view('');
    }
    
    /**
     * @return array|string
     */
    public function create()
    {
        
        return view('');
    }
    
    /**
     * @return array|string
     */
    public function store()
    {
        
        return ;
    }
    
    /**
     * @param int|null \$id
     * @return array|string
     */
    public function show(int \$id = null){
        
        return view('');
    }
    
    /**
     * @param int|null \$id
     * @return array|string
     */
    public function edit(int \$id = null)
    {
        
        return view('');
    }
    
    /**
     * @param int|null \$id
     * @return array|string
     */
    public function update(int \$id = null)
    {
        
        return ;
    }
    
    /**
     * @param int|null \$id
     * @return array|string
     */
    public function delete(int \$id = null)
    {
        
        return ;
    }   
}

EOD;

        $template = ($is_rest == true) ? $restTemplate : $basicTemplate;

        if (!write_file($path, $template)) {
            CLI::error(lang('Recharge.writeError', [$path]));
            return;
        }

        CLI::write('Created file: ' . CLI::color(str_replace($homepath, $ns, $path), 'green'));
    }

}
