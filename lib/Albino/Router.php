<?php

namespace Albino;

/**
 * Router class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
abstract class Router
{

  /**
   * @var array
   */
  protected $_routes = array();

  /**
   * @var Route
   */
  protected $_currentRoute;

  /**
   * Construct
   */
  public function __construct()
  {
    $this->_configureDefaultRoutes();
    $this->configure();
  }

  /**
   * Configure the default routes
   */
  protected function _configureDefaultRoutes()
  {
    // 404 NOT FOUND page is placed as the fallback route
    $this->setRoute('notFound', \App\Factory::createRoute('regex', array(
      'pattern'     => '#.*#',
      'module'      => 'Error',
      'controller'  => 'Index',
      'action'      => 'notFound'
    )));
  }

  /**
   * To be implemented by the application.
   */
  abstract public function configure();

  /**
   * @return Route
   */
  public function getCurrentRoute()
  {
    return $this->_currentRoute;
  }

  /**
   * @param string $identifier
   * @param Route $route
   */
  public function setRoute($identifier, Route $route)
  {
    array_unshift($this->_routes, array(
      'identifier'  => $identifier,
      'route'       => $route
    ));
  }

  /**
   * @param array $routes
   */
  public function setRoutes(array $routes)
  {
    foreach ($routes as $identifier => $route)
    {
      $this->setRoute($identifier, $route);
    }
  }

  /**
   * @param Request $request                    The request object that the route is matched against
   * @throws Exception\InvalidConfiguration     Is returned when no fallback routes are configured and no route is matched
   * @return boolean
   */
  public function match(Request $request)
  {
    foreach ($this->_routes as $route)
    {
      if ($route['route']->match($request) === true)
      {
        $this->_currentRoute = $route['route'];
        return true;
      }
    }

    throw new \Albino\Exception\InvalidConfiguration(sprintf('Path \'%s\'not caught by any route', $request->getPath()));
  }
}