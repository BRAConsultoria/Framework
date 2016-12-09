<?php

namespace Main\Core;
use Main\Template\Render;
use Main\API\Client;

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
     * @var Render 
     */
    protected $render;

    /**
     * @var Client
     */
    protected $API;

    public function __construct()
    {
        $this->render = new Render();
        $this->API = NULL;
    }

    public function jsonSuccess($message = '')
    {
        return $this->render->loader()->render('json_encode.twig', [
            "data" => [
                'sucess' => 'true', 
                'message' => $message
            ]
        ]);
    }

    public function jsonError($error)
    {
        return $this->render->loader()->render('json_encode.twig', [
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
     * @return Client Objeto da interface de acesso à API
     */
    public function getAPI(array $conf) 
    {
        if(\count($conf) > 0) {
            $this->setAPI(new Client($conf));
        } else {
            if(\is_null($this->API) === true){
                $this->setAPI(new Client([]));
            }
        }
        return $this->API;
    }

    /**
     * @return $this
     */
    public function setAPI(Client $API) 
    {
        $this->API = $API;
        return $this;
    }
}
