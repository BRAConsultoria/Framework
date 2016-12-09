<?php

namespace Main\Core;

class Request 
{
    private $body;
    private $params;
    private $headers;
    private $method;
    
    public function __construct($body)
    {
        $this->setBody($body);
    }
    
    public function getPayloadData()
    {
        return \json_decode($this->getBody());
    }
    
    public function getBody() 
    {
        return $this->body;
    }

    public function setBody($body) 
    {
        $this->body = $body;
        return $this;
    }
    
    public function getParams() 
    {
        return $this->params;
    }

    public function setParams($params) 
    {
        $this->params = $params;
        return $this;
    }
    
    public function getHeaders() 
    {
        return $this->headers;
    }

    public function setHeaders($headers) 
    {
        $this->headers = $headers;
        return $this;
    }
    
    public function getMethod() 
    {
        return $this->method;
    }

    public function setMethod($method) 
    {
        $this->method = $method;
        return $this;
    }
}