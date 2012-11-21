<?php

namespace Albino;

/**
 * Dispatcher class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Dispatcher
{

  /**
   * @var Request
   */
  protected $_request;

  /**
   * @var array
   */
  protected $_controllerStack = array();

  /**
   * @param Request $request
   * @return Dispatcher
   */
  public function setRequest(Request $request)
  {
    $this->_request = $request;
    return $this;
  }

  /**
   * @param Route $route
   * @throws Exception\InvalidConfiguration
   */
  public function forwardTo(Route $route, $send = true)
  {
    $this->renderAction($route->getModule(), $route->getController(), $route->getAction(), $send);
  }

  /**
   * @param string $module
   * @param string $controller
   * @param string $action
   * @params array $params
   * @param bool $send
   *
   * @return mixed|void
   *
   * @throws Exception\InvalidConfiguration
   */
  public function renderAction($module, $controller, $action, $params = array(), $send = true)
  {
    $className = strtr('App\Module\<module>\Controller\<controller>', array(
      '<module>'     => $module,
      '<controller>' => $controller
    ));

    if (class_exists($className) === false)
    {
      throw new \Albino\Exception\InvalidConfiguration(sprintf('Class  \'%s\' doesn\'t exist', $className));
    }

    $instance = $this->_getController($className);
    $actionName = $action . 'Action';

    if (method_exists($instance, $actionName) === false)
    {
      throw new \Albino\Exception\InvalidConfiguration(sprintf('Action \'%s\' on the \'%s\' controller doesn\'t exist', $actionName, $controller));
    }

    $instance->preExecute();
    $response = $instance->$actionName($this->_request, $params);
    $instance->postExecute();

    if (!$response instanceof \Albino\Response)
    {
      $response = \App\Factory::createResponse()->setContent($response);
    }

    if ($send === true)
    {
      $response->sendHeaders();
      echo $response->renderContent();
    }
    else
    {
      return $response->getContent();
    }

    return '';
  }

  /**
   * @param string $className
   * @return \Albino\Controller
   */
  protected function _getController($className)
  {
    if (array_key_exists($className, $this->_controllerStack) === false)
    {
      $this->_controllerStack[$className] = new $className();
    }

    return $this->_controllerStack[$className];
  }
}