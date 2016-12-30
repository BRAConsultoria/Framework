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

    public function setAPI(Request $API) 
    {
        $this->API = $API;
        return $this;
    }


}
