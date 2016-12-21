<?php

namespace Framework;
use Framework\Core\Controller;

class Application
{
    public function __construct(array $conf = []) 
    {
        if(isset($conf['APP_ROOT']) === false){
            throw new \Exception("Você deve informar o path absoluto da pasta 'src' da sua app.");
        }

        \define('DEFAULT_NAMESPACE', 'App');
        \define('DIR_ROOT', __DIR__ . \DIRECTORY_SEPARATOR);

        if(\defined('APP_ROOT') === false){
            \define('APP_ROOT', $conf['APP_ROOT']);
        }

        if(\defined('URL_BASE_API') === false){
            \define('URL_BASE_API', (isset($conf['URL_BASE_API']) ? $conf['URL_BASE_API'] : '/'));
        }

        if(\defined('DEBUG_MODE') === false){
            \define('DEBUG_MODE', (isset($conf['DEBUG_MODE']) ? $conf['DEBUG_MODE'] : false));
        }

        \set_error_handler(__NAMESPACE__ . "\\Application::sisError", \E_WARNING | \E_NOTICE);
        return $this;
    }
    
    /**
     * @param string|null $q Query string para localizar o controller da requisição
     *  se null, será sobrescrito por filter_input(INPUT_GET, 'q')
     * @param array|null $headers Headers da requisição HTTP em curso
     *  se null, será sobrescrito por getallheaders()
     * @param string|null $payload body em formato JSON, da requisição HTTP em curso
     *  se null, será sobrescrito por file_get_contents("php://input")
     * @return Controller Objeto do controller com dados da requisição setados.
     */
    public function run($q = NULL, array $headers = NULL, $payload = null)
    {
        if(\is_null($q) === true){
            $q = (\filter_input(\INPUT_GET, 'q') ?: $_SERVER['REQUEST_URI']);
        }

        if(\is_null($headers) === true){
            $headers = \getallheaders();
        }

        if(\is_null($payload) === true){
            $payload = (\json_decode(\file_get_contents("php://input"), true) ?: []);
        }

        return (new Controller())
                ->setHeaders($headers)
                ->setPayload($payload)
                ->setURI($q)
                ->run();
    }
    
    private function getErrorType($errno)
    {
        $erros = [1 => "Error",
            2 => "Warning",
            4 => "Parse error",
            8 => "Notice",
            16 => "Core error",
            32 => "Core warning",
            64 => "Compile error",
            128 => "Compile warning",
            256 => "User error",
            512 => "User warning",
            1024 => "User notice",
            6143 => "Undefined erro",
            2048 => "Strict error",
            4096 => "Recoverable error"
        ];

        return($erros[$errno] ? : $erros[6143]);
    }

    public function sisError($errno, $errstr, $errfile, $errline)
    {
        throw new \Exception(\sprintf("%s: %s in %s on line %s", self::getErrorType($errno), $errstr, $errfile, $errline));
    }
}