<?php

/**
 * @see       https://github.com/laminas/laminas-router for the canonical source repository
 * @copyright https://github.com/laminas/laminas-router/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-router/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Mvc\Router\Http;

use Laminas\Mvc\Router\RouteMatch as BaseRouteMatch;

/**
 * Part route match.
 *
 * @package    Laminas_Mvc_Router
 * @subpackage Http
 */
class RouteMatch extends BaseRouteMatch
{
    /**
     * Length of the matched path.
     *
     * @var integer
     */
    protected $length;

    /**
     * Create a part RouteMatch with given parameters and length.
     *
     * @param  array   $params
     * @param  integer $length
     */
    public function __construct(array $params, $length = 0)
    {
        parent::__construct($params);

        $this->length = $length;
    }

    /**
     * setMatchedRouteName(): defined by BaseRouteMatch.
     *
     * @see    BaseRouteMatch::setMatchedRouteName()
     * @param  string $name
     * @return RouteMatch
     */
    public function setMatchedRouteName($name)
    {
        if ($this->matchedRouteName === null) {
            $this->matchedRouteName = $name;
        } else {
            $this->matchedRouteName = $name . '/' . $this->matchedRouteName;
        }

        return $this;
    }

    /**
     * Merge parameters from another match.
     *
     * @param  RouteMatch $match
     * @return RouteMatch
     */
    public function merge(RouteMatch $match)
    {
        $this->params  = array_merge($this->params, $match->getParams());
        $this->length += $match->getLength();

        $this->matchedRouteName = $match->getMatchedRouteName();

        return $this;
    }

    /**
     * Get the matched path length.
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }
}
