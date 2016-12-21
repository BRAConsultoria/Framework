#!/usr/bin/env php
<?php

$help = "type manage.php install|create <controller_name>";

$fileDir = \array_reverse(\explode('\\', __DIR__));

if($fileDir[0] === 'bin'){
    $composerRoot = \realpath(__DIR__ . '../../');
} else {
    $composerRoot = \realpath(__DIR__ . '../../../../');
}

$paths = [
    'home'      => $composerRoot,
    'src'       => $composerRoot . \DIRECTORY_SEPARATOR .'src'. \DIRECTORY_SEPARATOR,
    'template'  => $composerRoot . \DIRECTORY_SEPARATOR. 'src'. \DIRECTORY_SEPARATOR .'Template'. \DIRECTORY_SEPARATOR .'views' .\DIRECTORY_SEPARATOR
];

$dirs = [
    $paths['src'],
    $paths['template']
];

$templates = getTemplates();

if (\count($argv) > 1) {
    switch ($argv[1]) {
        case 'run':
            $phpExec    = (isset($argv[2]) ? $argv[2] : NULL);
            $port       = (isset($argv[3]) ? $argv[3] : '8081');
            run($paths, $dirs, $phpExec, $port);
            break;
        case 'create':
            create($argv[2], $templates, $paths, $dirs);
            break;
        case 'install':
            build($paths, $dirs);
            break;
        default:
            exit($help);
            break;
    }
} else {
    exit($help);
}

function run($paths, $dirs, $phpExec, $port)
{
    $home = $paths['home'];
    if (isBuilt($paths, $dirs) === true) {
        \chdir($home);

        $exec       = ((\is_null($phpExec) === false and \is_executable($phpExec) === true) ? $phpExec : 'php');
        $www        = \realpath($home . "../");
        $router     = \realpath(__DIR__ . \DIRECTORY_SEPARATOR . 'phpaccess.php' );
        $validate   = validateExec($exec);

        if($validate === false){
            exit("Nao possivel iniciar o servidor de desenvolvimento.");
        } else {
            print "App running at http://localhost:$port/{App_PATH}\n"
                    . "$validate Development Server started at ". \date("D M d H:i:s Y") ."\n"
                    . "Document root is C:\\xampp\\htdocs\\\n"
                    . "Press Ctrl-C to quit.\n";
            \exec("$exec -S localhost:$port -t $www $router", $stdout, $status);
            if($status > 0){
                print outputExec($stdout);
                exit("Problemas na execução no servidor de desenvolvimento.");
            }
        }
    } else {
        exit("\nApp not built yet. run manage.php build first!\n");
    }
}

function validateExec($exec)
{
    \exec($exec .' --version', $stdout, $status);
    if(isset($stdout[0]) and \preg_match('/^[PHP]{3}/', $stdout[0]) and $status === 0){
        return $stdout[0];
    } else {
        print outputExec($stdout);
        return false;
    }
}

function create($controller, $templates, $paths, $dirs)
{
    if(empty($controller)){
        exit("Controller must have at least one character!");
    }
    
    if(isBuilt($paths, $dirs) === true){

        $controllerName = \ucfirst(\strtolower($controller));
        $controllerDir  = $paths['src'] . \DIRECTORY_SEPARATOR . $controllerName;
        $viewDir        = $paths['template'] . \strtolower($controllerName);

        if(\is_dir($controllerDir) === true){
            exit("Controller name already taken.");
        }

        $controllerNameSpace = $controllerName;
        $className = $controllerName;
        $classNameSpance = $className;
        
        $controllerUser = \sprintf($templates['controller'], $controllerNameSpace, $controllerName, $className, $controllerName, \strtolower($controllerName));
        $classUser      = \sprintf($templates['class'], $classNameSpance, $className);


        $dir = \mkdir($controllerDir);
        if($dir === true){
            \file_put_contents($controllerDir . \DIRECTORY_SEPARATOR . $controllerName .'Controller.php', $controllerUser);
            \file_put_contents($controllerDir . \DIRECTORY_SEPARATOR . $className .'Class.php', $classUser);
            
            $createViewDir = (\is_dir($viewDir) === true ? false : \mkdir($viewDir));
            if($createViewDir === true){
                \file_put_contents($viewDir . \DIRECTORY_SEPARATOR . 'index.twig', '<h1>'. $controllerName .' home view</h1>');
                print("Controller succesfuly created at $controllerDir");
                exit();
            } else {
                exit("Fail: view's folder could not be created!");
            }
        } else {
            exit("Fail: controller's folder could not be created!");
        }
    } else {
        exit("App not built yet! run php manage.php install first.");
    }
}
function build(array $paths, array $dirs) 
{
    $home       = $paths['home'];

    print "> verifying dirs ...\n";
    foreach ($dirs as $dir) {
        if (\is_dir($dir) === false) {
            \exec("mkdir ". $dir);
        }
    }

    //criando a view home, que responde pela rota /
    \file_put_contents($paths['template'] . 'home.twig', "<h1>OnyxERP Framework HTTP/1.1 200 OK</h1>");

    //criando a view utilizada para retornar um json com uma mensagem de erro.
    \file_put_contents($paths['template'] . 'json_encode.twig', "{{ data|json_encode()|raw }}");

    \chdir($home);

    print("\n App succesfully built! have run! :)\n");
    exit();
}

function isBuilt(array $paths, array $dirs) 
{
    $home       = $paths['home'];

    $directories = true;

    foreach ($dirs as $dir) {
        if (\is_dir($dir) === false) {
            $directories = false;
            break;
        }
    }

    if ($directories === true) {
        return true;
    } else {
        return false;
    }
}

function outputExec(array $stdout) 
{
    $output = "";
    foreach ($stdout as $line) {
        $output .= $line . "\n";
    }
    return $output;
}

function getTemplates(){
$controllerTemplate = <<<EOF
<?php

namespace App\%s;

use Framework\Core\ControllerAbstract;
use Framework\Core\ControllerInterface;

class %sController extends ControllerAbstract implements ControllerInterface
{
    private \$class;
    private \$twig;

    public function __construct()
    {
        parent::__construct();
        \$this->class = new %sClass(\$this->getAPI(['base_uri' => "http://api.auth.alpha.onyxapp.com.br/v1/"]));

        //configurações do módulo para o twig vão aqui, como cache por exemplo
        \$this->twig = parent::getTwig();
    }

    /**
    * @route("GET")
    */
    public function main()
    {
        \$this->class->setJwt(parent::getJwt());
        //\$exemplo = \$this->class->getExemplo();
        return \$this->twig->render('%s/index.twig', []);
    }
}
EOF;

$classTemplate = <<<EOF
<?php

namespace App\%s;
use Framework\API\Request;
use Framework\Core\ServiceAbstract;
use Framework\Core\ServiceInterface;

class %sClass extends ServiceAbstract implements ServiceInterface
{

    public function __construct(Request \$API)
    {
        parent::__construct(\$API);
    }
    
    /**
     * Implementação de exemplo de requisição através da interface de abstração do GuzzleHttp para acesso às APIs do OnyxERP
     */
    public function getExamplo()
    {
        \$request = \$this->getAPI()
                ->setJwt(parent::getJwt())
                ->setHeader('Content-type', 'application/json');
        
        \$result = \$request->get('auth/');

        if(\$result->getStatusCode() !== 200){
            throw new \Exception("Falha ao obeter exemplo.");
        }

        return \$result->getDecoded();
    }
}
EOF;

return [
    'controller'    => $controllerTemplate,
    'class'         => $classTemplate
];
}