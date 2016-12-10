<?php
namespace Main\Core;

interface ControllerInterface
{
    /**
    * constructor
    */
    public function __construct();
    /**
    * main
    */
    public function main();
    /**
    * @return strung
    */
    public function jsonSuccess($param);
    /**
    * @return strung
    */
    public function jsonError($param);
    /**
     * @return $this
     */
    public function setRequestParams(array $param);
    /**
    * @return array requestParams
    */
    public function getRequestParams();
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