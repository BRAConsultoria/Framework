<?php

namespace Main\Core;

class Controller extends ControllerAbstract
{

    private $URI;
    private $controller;
    private $action;
    private $params = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function run() 
    {
        $URI = $this->getURI();

        $this->setRequestEnvironment($URI);

        $controller = $this->getController();
        $action = $this->getAction();

        try {

            $namespace = \DEFAULT_NAMESPACE . '\\'. $controller . '\\'. $controller .'Controller';

            if(empty($controller)){
                return $this->main();
            }

            if(\class_exists($namespace) === false){
               throw new \RuntimeException("Controller not found");
            }

            $jwt = $this->getJwt();
            $payload = $this->getPayload();

            $controllerClass = new $namespace();

            if($action === 'main'){
                return $controllerClass
                        ->setRequestParams($this->getParams())
                        ->setJwt($jwt)
                        ->setPayload($payload)
                        ->main();
            }

            if(\method_exists($controllerClass, $action) === false) {
                throw new \RuntimeException("Controller Action not found");
            }

            $route = $this->getControllerMethodAnnotationsRoute($namespace, $action);
            if(\filter_input(\INPUT_SERVER, 'REQUEST_METHOD') != $route){
                throw new \RuntimeException("Controller Action not found for requested route");
            }

            return $controllerClass
                    ->setRequestParams($this->getParams())
                    ->setJwt($jwt)
                    ->setPayload($payload)
                    ->{$action}();

        } catch (\RuntimeException $e) {
            return $this->jsonError($e->getMessage());
        } catch (\Exception $e) {
            if(\DEBUG_MODE === true){
                $Error = "<pre>".$e->getTraceAsString()."</pre>";
            }
            return $this->jsonError($e->getMessage() . $Error);
        }
    }
    
    public function main()
    {
        return $this->getTwig()->loader()->render('home.twig', []);
    }
    
    private function getControllerMethodAnnotationsRoute($controllerNS, $method = 'main') 
    {
        $annotations = [];
        \preg_match_all('/@[route]{5}(.*?)\n/i', (new \ReflectionClass($controllerNS))->getMethod($method), $annotations);

        if(isset($annotations[1][0])){
            return \strtoupper(\preg_replace('/[^a-z]/i', '', $annotations[1][0]));
        } else {
            throw new \Exception("Missing route annotation for controller method called");
        }
    }
    
    private function setRequestEnvironment($URI)
    {
        $exURI = \explode('/', $URI);

        if(\count($exURI) > 1){
            list($controller, $action) = $exURI;
        } else {
            $controller = $exURI[0];
            $action     = 'main';
        }

        $this->setController($controller);
        $this->setAction($action ?: 'main');

        if (\count($exURI) > 2 and ! empty($exURI[2])) {
            $i = 0;
            $offset = 0;

            $queryString = \array_slice($exURI, 2);
            $params = [];
            while ($offset < \count($queryString)){
                $pair = \array_slice($queryString, $offset, 2);
                if(\count($pair) === 2){
                    list($key, $val) = $pair;
                } else {
                    $key = $pair[0];
                    $val = NULL;
                }

                if(\strlen($key) > 0){
                    $params[$key] = $val;
                }
                $offset += 2;
            }
            $this->setParams($params);
        }
    }
    
    public function getJwt()
    {
        $headers = \getallheaders();
        if(isset($headers['Authorization'])){
            return \sscanf($headers['Authorization'], 'Bearer %s');
        } else {
            return false;
        }
    }

    public function getPayload()
    {
        $payload = \file_get_contents("php://input");
        if(!empty($payload)){
            return \json_decode($payload, true);
        } else {
            return [];
        }
    }

    public function getURI() 
    {
        return $this->URI;
    }

    public function setURI($URI) 
    {
        $this->URI = $URI;
        return $this;
    }

        
    public function getController()
    {
        return $this->controller;
    }

    public function getAction() 
    {
        return $this->action;
    }

    public function getParams() 
    {
        return $this->params;
    }

    private function setController($controller) 
    {
        $this->controller = $controller;
        return $this;
    }

    private function setAction($action) 
    {
        $this->action = $action;
        return $this;
    }

    private function setParams(array $params) 
    {
        $this->params = $params;
        return $this;
    }
}