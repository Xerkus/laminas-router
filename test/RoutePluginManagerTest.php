<?php

/**
 * @see       https://github.com/laminas/laminas-router for the canonical source repository
 * @copyright https://github.com/laminas/laminas-router/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-router/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Mvc\Router;

use Laminas\Mvc\Router\RoutePluginManager;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @category   Laminas
 * @package    Laminas_Mvc_Router
 * @subpackage UnitTests
 * @group      Laminas_Router
 */
class RoutePluginManagerTest extends TestCase
{
    public function testLoadNonExistentRoute()
    {
        $routes = new RoutePluginManager();
        $this->setExpectedException('Laminas\ServiceManager\Exception\ServiceNotFoundException');
        $routes->get('foo');
    }
}
