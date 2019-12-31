<?php

/**
 * @see       https://github.com/laminas/laminas-router for the canonical source repository
 * @copyright https://github.com/laminas/laminas-router/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-router/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Mvc\Router;

use Laminas\Stdlib\RequestInterface as Request;

/**
 * RouteInterface interface.
 *
 * @package    Laminas_Mvc_Router
 */
interface RouteInterface
{
    /**
     * Priority used for route stacks.
     *
     * @var integer
     * public $priority;
     */

    /**
     * Create a new route with given options.
     *
     * @param  array|\Traversable $options
     * @return void
     */
    public static function factory($options = array());

    /**
     * Match a given request.
     *
     * @param  Request $request
     * @return RouteMatch
     */
    public function match(Request $request);

    /**
     * Assemble the route.
     *
     * @param  array $params
     * @param  array $options
     * @return mixed
     */
    public function assemble(array $params = array(), array $options = array());
}
