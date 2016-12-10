<?php

namespace Main\API;
use GuzzleHttp\Client as GuzzleClient;

class Client
{
    /**
     * @var GuzzleClient Instância do GuzzleHTTP
     */
    private $guzzle;

    /**
     * @var string Método HTTP a ser executado.
     */
    private $method;

    /**
     * @var string URL Base da API a ser acessada
     */
    private $urlBase;

    /**
    * @var string $payload Request body já em formato JSON
    */
    private $payload;

    /**
    * @var string $jwt Json Web Token
    */
    private $jwt;

    /**
     * @var array Headers
     */
    private $headers;
    
    public function __construct($conf = []) 
    {
        $this->guzzle = new GuzzleClient($conf);

        //constante definida no bootstrap
        $this->setUrlBase(\URL_BASE_API);
    }

    /**
     * Inicia uma requisição GET HTTP
     */
    public function get($endPoint = '/')
    {
        $this->method = 'GET';
        return $this->exec($endPoint);
    }

    /**
     * Inicia uma requisição POST HTTP
     */
    public function post($endPoint = '/')
    {
        $this->method = 'POST';
        return $this->exec($endPoint);
    }

    /**
     * Inicia uma requisição PUT HTTP
     */
    public function put($endPoint = '/')
    {
        $this->method = 'PUT';
        return $this->exec($endPoint);
    }
    
    /**
     * Inicia uma requisição DELETE HTTP
     */
    public function delete($endPoint = '/')
    {
        $this->method = 'DELETE';
        return $this->exec($endPoint);
    }

    /**
     * Executa a requisição inciada por alguma das operações HTTP.
     */
    private function exec($endPoint)
    {
        $jwt = $this->getJwt();
        $headers = $this->headers;
        $payload = $this->getPayload();

        $exec = $this->guzzle->request($this->method, $this->getUrlBase() .'/'. $endPoint);
        return $exec->getBody();
    }

    public function setHeader($key, $val)
    {
        $this->headers[$key] = $val;
        return $this;
    }
    
    public function getUrlBase() 
    {
        return $this->urlBase;
    }
    
    public function setUrlBase($urlBase) 
    {
        $this->urlBase = $urlBase;
        return $this;
    }
    
    public function getPayload() 
    {
        return $this->payload;
    }
    
    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }
    
    public function getJwt() 
    {
        return $this->jwt;
    }
    
    public function setJwt($jwt) 
    {
        $this->jwt = $jwt;
        return $this;
    }
}