<?php

namespace Albino;

/**
 * Controller class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
abstract class Controller
{

  /**
   * @var View
   */
  protected $_view;

  /**
   * Code that is executed before every action
   * is called.
   */
  public function preExecute()
  {
    $this->_configureView();
  }

  /**
   * Code that is executed after very action
   * was executed.
   */
  public function postExecute()
  {
  }

  /**
   * @return Application
   */
  public function getApplication()
  {
    return \App\Application::getInstance();
  }

  /**
   * @return View
   */
  protected function _getView()
  {
    return $this->_view;
  }

  /**
   * @param View $view
   */
  protected function _setView(\Albino\View $view)
  {
    $this->_view = $view;
  }

  /**
   * Configures the view
   */
  protected function _configureView()
  {
    $currentRoute = $this->getApplication()->getRouter()->getCurrentRoute();
    $this->_view = \App\Factory::createView($this->_generateTemplatePathFor(
      $currentRoute->getModule(),
      $currentRoute->getAction()
    ));
  }

  /**
   * @param string $module
   * @param string $action
   *
   * @return string
   */
  protected function _generateTemplatePathFor($module, $action)
  {
    return APPLICATION_PATH . DIRECTORY_SEPARATOR . 'Module' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . $action . '.php';
  }

  /**
   * @return Route
   */
  protected function _getCurrentRoute()
  {
    return $this->getApplication()->getRouter()->getCurrentRoute();
  }
}