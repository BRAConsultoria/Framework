<?php

namespace Main;

use Main\Core\Controller;

/**
 * DashboardServiceTest.
 *
 * PHP version 5.6
 *
 * @author    rinzler <github.com/feliphebueno>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version   1.5.0
 */
class ControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Controller
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $app = require_once realpath('index.php');
        $this->oject = new Controller();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }
    /**
     * @covers Main\Core\Controller::__construct
     */
    public function testConstructor()
    {
        require_once realpath('index.php');
        $this->assertInstanceOf('\Main\Core\Controller', new Controller());
    }
}
