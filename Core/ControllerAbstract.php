<?php

namespace Framework\Core;
use Framework\Template\Twig;
use Framework\API\Request;

abstract class ControllerAbstract implements ControllerInterface
{
    /**
    * @var array $requestParams parametros da request
    */
   private $requestParams = [];

    /**
    * @var array $payload Request payload body já decodificado em array
    */
   private $payload;

    /**
    * @var string $jwt Json Web Token
    */
   private $jwt;
   
    /**
     * @var Twig 
     */
    private $twig;

    /**
     * @var Request
     */
    private $API;

    public function __construct()
    {
        $this->twig = NULL;
        $this->API = NULL;
    }

    public function jsonSuccess($message = '')
    {
        return $this->getTwig()->loader()->render('json_encode.twig', [
            "data" => [
                'sucess' => 'true', 
                'message' => $message
            ]
        ]);
    }

    public function jsonError($error)
    {
        return $this->getTwig()->loader()->render('json_encode.twig', [
            "data" => [
                'sucess' => 'false', 
                'message' => $error
            ]
        ]);
    }
    
    public function getRequestParams()
    {
        return $this->requestParams;
    }

    /**
     * @return $this
     */
    public function setRequestParams(array $requestParams)
    {
        $this->requestParams = $requestParams;
        return $this;
    }
    
    public function getJwt() 
    {
        return $this->jwt;
    }

    /**
     * @return $this
     */
    public function setJwt($jwt) 
    {
        $this->jwt = $jwt;
        return $this;
    }
    
    public function getPayload() 
    {
        return $this->payload;
    }

    /**
     * @return $this
     */
    public function setPayload(array $payload) 
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * Retorna uma instância da classe de abstração do Twig, seguindo
     * a seguinte regra: se alguma config foi informada em $conf, então uma nova 
     * instância de Twig é retornada, caso contrário a instância já configurada
     *  em self::__construct() é retornada
     * 
     * @param array $conf Array de configurações para a criação da instância do Client
     * @return Request Objeto da interface de acesso à API
     */
    public function getTwig(array $conf = []) 
    {
        $conf['debug'] = \filter_input(\INPUT_GET, 'debug');
        if(\count($conf) > 1) {
            $this->setTwig((new Twig($conf))->loader());
        } else {
            if(\is_null($this->twig) === true){
                $this->setTwig((new Twig($conf))->loader());
            }
        }
        return $this->twig;
    }
    /**
     * @return $this
     */
    public function setTwig(Twig $twig) 
    {
        $this->twig = $twig;
        return $this;
    }

    /**
     * Retorna uma instância da classe de abstração do GuzzleHttp\Client, seguindo
     * a seguinte regra: se alguma config foi informada em $conf, então uma nova 
     * instância de Request é retornada, caso contrário a instância já configurada
     *  no self::__construct() é retornada
     * 
     * @param array $conf Array de configurações para a criação da instância do Client
     * @return Request Objeto da interface de acesso à API
     */
    public function getAPI(array $conf = []) 
    {
        if(\count($conf) > 0) {
            $this->setAPI(new Request($conf));
        } else {
            if(\is_null($this->API) === true){
                $this->setAPI(new Request([]));
            }
        }
        return $this->API;
    }

    /**
     * @return $this
     */
    public function setAPI(Request $API) 
    {
        $this->API = $API;
        return $this;
    }
}
