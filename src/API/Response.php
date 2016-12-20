<?php

namespace Framework\API;

class Response 
{
    private $decoded;
    private $content;
    private $statusCode;
    private $headers;

    public function __construct($statusCode = 200)
    {
        unset($this->content);
        $this->setStatusCode($statusCode);
    }
    
    public function __toString()
    {
        return $this->getContent();
    }

    public function getDecoded() 
    {
        return $this->decoded;
    }

    public function setDecoded(array $decoded)
    {
        $this->decoded = $decoded;
        return $this;
    }
    
    public function getContent() 
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode) 
    {
        $this->statusCode = $statusCode;
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
}