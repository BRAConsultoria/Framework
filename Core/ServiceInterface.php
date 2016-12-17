<?php
namespace Main\Core;
use \Main\API\Request;

interface ServiceInterface
{
    /**
    * constructor
    */
    public function __construct(Request $API);
    /**
    * @return $this
    */
    public function setJwt($jwt);
    /**
    * @return string Json Web Token
    */
    public function getJwt();
    /**
    * @return $this
    */
    public function setPayload(array $payload);
    /**
    * @return array payload
    */
    public function getPayload();
}