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
function create($controller, $templates, $paths, $dirs)
{
    if(empty($controller)){
        exit("Controller must have at least one character!");
    }
    
    if(isBuilt($paths, $dirs) === true){

        $controllerName = \ucfirst(\strtolower($controller));
        $controllerDir = $paths['src'] . \DIRECTORY_SEPARATOR . $controllerName;

        if(\is_dir($controllerDir) === true){
            exit("Controller name already taken.");
        }

        $controllerNameSpace = $controllerName;
        $className = $controllerName;
        $classNameSpance = $className;
        
        $controllerUser = \sprintf($templates['controller'], $controllerNameSpace, $controllerName, $className, $controllerName);
        $classUser = \sprintf($templates['class'], $classNameSpance, $className);


        $dir = \mkdir($controllerDir);
        if($dir === true){
            \file_put_contents($controllerDir . \DIRECTORY_SEPARATOR . $controllerName .'Controller.php', $controllerUser);
            \file_put_contents($controllerDir . \DIRECTORY_SEPARATOR . $className .'Class.php', $classUser);
            print("Controller succesfuly created at $controllerDir");
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
    exit(0);
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
        return parent::jsonSuccess("Controller %s running OK");
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