<?php

namespace Main;

use Framework\Application;

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
     * @var Application
     */
    protected $object;
    
    /**
     * @var string
     */
    protected $appHome;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->appHome = __DIR__ .'..'. \DIRECTORY_SEPARATOR.'..'. \DIRECTORY_SEPARATOR .'..'. \DIRECTORY_SEPARATOR;
        require_once($this->appHome . \DIRECTORY_SEPARATOR . 'vendor'. \DIRECTORY_SEPARATOR .'autoload.php');
    }

    /**
     * @covers Main\Core\Controller::__construct
     */
    public function testConstructor()
    {
        $app = new Application(['APP_ROOT' => $this->appHome .'/src/' ]);
        $this->oject = $app;
        $this->assertInstanceOf('Framework\Application', $app);
    }
}
