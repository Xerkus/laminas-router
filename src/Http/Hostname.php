<?php

/**
 * @see       https://github.com/laminas/laminas-router for the canonical source repository
 * @copyright https://github.com/laminas/laminas-router/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-router/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Mvc\Router\Http;

use Laminas\Mvc\Router\Exception;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Stdlib\RequestInterface as Request;
use Traversable;

/**
 * Hostname route.
 *
 * @see        http://guides.rubyonrails.org/routing.html
 */
class Hostname implements RouteInterface
{
    /**
     * RouteInterface to match.
     *
     * @var array
     */
    protected $route;

    /**
     * Constraints for parameters.
     *
     * @var array
     */
    protected $constraints;

    /**
     * Default values.
     *
     * @var array
     */
    protected $defaults;

    /**
     * List of assembled parameters.
     *
     * @var array
     */
    protected $assembledParams = array();

    /**
     * Create a new hostname route.
     *
     * @param  string $route
     * @param  array  $constraints
     * @param  array  $defaults
     */
    public function __construct($route, array $constraints = array(), array $defaults = array())
    {
        $this->route       = explode('.', $route);
        $this->constraints = $constraints;
        $this->defaults    = $defaults;
    }

    /**
     * factory(): defined by RouteInterface interface.
     *
     * @see    Route::factory()
     * @param  array|\Traversable $options
     * @throws \Laminas\Mvc\Router\Exception\InvalidArgumentException
     * @return Hostname
     */
    public static function factory($options = array())
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new Exception\InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
        }

        if (!isset($options['route'])) {
            throw new Exception\InvalidArgumentException('Missing "route" in options array');
        }

        if (!isset($options['constraints'])) {
            $options['constraints'] = array();
        }

        if (!isset($options['defaults'])) {
            $options['defaults'] = array();
        }

        return new static($options['route'], $options['constraints'], $options['defaults']);
    }

    /**
     * match(): defined by RouteInterface interface.
     *
     * @see    Route::match()
     * @param  Request $request
     * @return RouteMatch
     */
    public function match(Request $request)
    {
        if (!method_exists($request, 'getUri')) {
            return null;
        }

        $uri      = $request->getUri();
        $hostname = explode('.', $uri->getHost());
        $params   = array();

        if (count($hostname) !== count($this->route)) {
            return null;
        }

        foreach ($this->route as $index => $routePart) {
            if (preg_match('(^:(?P<name>.+)$)', $routePart, $matches)) {
                if (isset($this->constraints[$matches['name']]) && !preg_match('(^' . $this->constraints[$matches['name']] . '$)', $hostname[$index])) {
                    return null;
                }

                $params[$matches['name']] = $hostname[$index];
            } elseif ($hostname[$index] !== $routePart) {
                return null;
            }
        }

        return new RouteMatch(array_merge($this->defaults, $params));
    }

    /**
     * assemble(): Defined by RouteInterface interface.
     *
     * @see    Route::assemble()
     * @param  array $params
     * @param  array $options
     * @return mixed
     * @throws Exception\InvalidArgumentException
     */
    public function assemble(array $params = array(), array $options = array())
    {
        $mergedParams          = array_merge($this->defaults, $params);
        $this->assembledParams = array();

        if (isset($options['uri'])) {
            $parts = array();

            foreach ($this->route as $routePart) {
                if (preg_match('(^:(?P<name>.+)$)', $routePart, $matches)) {
                    if (!isset($mergedParams[$matches['name']])) {
                        throw new Exception\InvalidArgumentException(sprintf('Missing parameter "%s"', $matches['name']));
                    }

                    $parts[] = $mergedParams[$matches['name']];

                    $this->assembledParams[] = $matches['name'];
                } else {
                    $parts[] = $routePart;
                }
            }

            $options['uri']->setHost(implode('.', $parts));
        }

        // A hostname does not contribute to the path, thus nothing is returned.
        return '';
    }

    /**
     * getAssembledParams(): defined by RouteInterface interface.
     *
     * @see    Route::getAssembledParams
     * @return array
     */
    public function getAssembledParams()
    {
        return $this->assembledParams;
    }
}
