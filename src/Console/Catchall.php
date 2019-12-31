<?php

/**
 * @see       https://github.com/laminas/laminas-router for the canonical source repository
 * @copyright https://github.com/laminas/laminas-router/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-router/blob/master/LICENSE.md New BSD License
 */

/**
 * @namespace
 */
namespace Laminas\Mvc\Router\Console;

use Laminas\Console\Request as ConsoleRequest;
use Laminas\Filter\FilterChain;
use Laminas\Mvc\Router\Exception;
use Laminas\Stdlib\RequestInterface as Request;
use Laminas\Validator\ValidatorChain;
use Traversable;

/**
 * Segment route.
 *
 * @copyright  Copyright (c) 2005-2010 Laminas (https://www.zend.com)
 * @license    https://getlaminas.org/license/new-bsd     New BSD License
 * @see        http://guides.rubyonrails.org/routing.html
 */
class Catchall implements RouteInterface
{

    /**
     * Parts of the route.
     *
     * @var array
     */
    protected $parts;

    /**
     * Default values.
     *
     * @var array
     */
    protected $defaults;

    /**
     * Parameters' name aliases.
     *
     * @var array
     */
    protected $aliases;

    /**
     * List of assembled parameters.
     *
     * @var array
     */
    protected $assembledParams = array();

    /**
     * @var ValidatorChain
     */
    protected $validators;

    /**
     * @var FilterChain
     */
    protected $filters;

    /**
     * Create a new simple console route.
     *
     * @param  array                                    $defaults
     * @return Catchall
     */
    public function __construct(array $defaults = array())
    {
        $this->defaults = $defaults;
    }

    /**
     * factory(): defined by Route interface.
     *
     * @see    \Laminas\Mvc\Router\RouteInterface::factory()
     * @param  array|Traversable $options
     * @return Simple
     */
    public static function factory($options = array())
    {
        return new static($options['defaults']);
    }

    /**
     * match(): defined by Route interface.
     *
     * @see     Route::match()
     * @param   Request             $request
     * @return  RouteMatch
     */
    public function match(Request $request)
    {
        if (!$request instanceof ConsoleRequest) {
            return null;
        }

        return new RouteMatch($this->defaults);
    }

    /**
     * assemble(): Defined by Route interface.
     *
     * @see    \Laminas\Mvc\Router\RouteInterface::assemble()
     * @param  array $params
     * @param  array $options
     * @return mixed
     */
    public function assemble(array $params = array(), array $options = array())
    {
        $this->assembledParams = array();
    }

    /**
     * getAssembledParams(): defined by Route interface.
     *
     * @see    RouteInterface::getAssembledParams
     * @return array
     */
    public function getAssembledParams()
    {
        return $this->assembledParams;
    }
}
