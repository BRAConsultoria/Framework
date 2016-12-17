<?php

namespace Main\API;
use GuzzleHttp\Client;
use Main\API\Response;

class Request
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
    * @var array $payload Request body em formato array nativo
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
        //constante definida no bootstrap
        $conf['base_uri'] = (isset($conf['base_uri']) ? $conf['base_uri'] : \URL_BASE_API);
        $this->guzzle = new Client($conf);
    }

    /**
     * Inicia uma requisição GET HTTP
     * @return Response Objeto contento Resposta da API.
     */
    public function get($endPoint = '/')
    {
        $this->method = 'GET';
        return $this->exec($endPoint);
    }

    /**
     * Inicia uma requisição POST HTTP
     * @return Response Objeto contento Resposta da API.
     */
    public function post($endPoint = '/', $body = NULL)
    {
        $this->method = 'POST';
        return $this->exec($endPoint, $body);
    }

    /**
     * Inicia uma requisição PUT HTTP
     * @return Response Objeto contento Resposta da API.
     */
    public function put($endPoint = '/', $body = NULL)
    {
        $this->method = 'PUT';
        return $this->exec($endPoint, $body);
    }
    
    /**
     * Inicia uma requisição DELETE HTTP
     * @return Response Objeto contento Resposta da API.
     */
    public function delete($endPoint = '/', $body = NULL)
    {
        $this->method = 'DELETE';
        return $this->exec($endPoint, $body);
    }

    /**
     * Executa a requisição inciada por alguma das operações HTTP.
     * @return Response Objeto contento Resposta da API.
     */
    private function exec($endPoint)
    {
        $conf = $this->config();

        $exec = $this->guzzle->request($this->method, $endPoint, $conf);

        $headers = $exec->getHeaders();
        $content = $exec->getBody()->getContents();

        $response = (new Response($exec->getStatusCode()))
                    ->setHeaders($headers)
                    ->setContent($content);

        if(isset($headers['Content-Type'][0]) and $headers['Content-Type'][0] == 'application/json'){
            try {
                $response->setDecoded(\json_decode($content, true));
            } catch (Exception $e) {
                
            }
        }

        return $response;
    }
    
    private function config()
    {
        $jwt = $this->getJwt();
        $headers = $this->headers;
        $payload = $this->getPayload();

        $conf = [
            'verify'        => false,
            'http_errors'   => false,
        ];
        
        //Adiciona abitrariamente o header Authorization, se um jwt foi setado.
        if(!empty($jwt)){
            $conf['headers'] = [
                'Authorization' => "Bearer ". $jwt
            ];
        }

        //Verifica os headers setados e os adiciona à configuração
        if(\is_array($headers)){
            foreach($headers as $key => $val){
                if(isset($headers[$key]) === true){
                    $conf['headers'][$key] =  $val;
                }
            }
        }

        //Verifica se um pyalod foi informado e se a requisição é do tipo que 
        //possui body(POST, PUT, DELETE), se for o caso, codifica em JSON e
        //adiciona o payload à configuração.
        if(\is_array($payload) and $this->method != "GET" and $this->method != "HEAD"){
            $conf['body'] = \json_encode($payload);
        }

        return $conf;
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
    
    public function setPayload(array $payload)
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