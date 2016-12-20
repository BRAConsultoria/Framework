<?php

namespace Framework\Core;
use Framework\API\Request;

abstract class ServiceAbstract implements ServiceInterface
{
    /**
    * @var array $payload Request payload body já decodificado em array
    */
    private $payload;

    /**
    * @var string $jwt Json Web Token
    */
    private $jwt;

    /**
    * @var Request $API Objeto da classe Request
    */
    private $API;
   
    public function __construct(Request $API)
    {
        $this->API = $API;
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
    
    public function getAPI() 
    {
        return $this->API;
    }

    public function setAPI(Request $API) 
    {
        $this->API = $API;
        return $this;
    }


}
