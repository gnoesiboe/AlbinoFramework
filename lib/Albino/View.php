<?php

namespace Albino;

/**
 * View class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class View extends DataHolder
{

  /**
   * @var string
   */
  protected $_template = null;

  /**
   * @var \Albino\View
   */
  protected $_decorator = null;

  /**
   * @param string $template
   * @param array $data
   */
  public function __construct($template = null, array $data = array())
  {
    $this->setTemplate($template);
    parent::__construct($data);
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->render();
  }

  /**
   * Renders the template
   *
   * @param array $data
   * @return string
   *
   * @throws Exception\InvalidConfiguration
   */
  public function render($data = array())
  {
    if (is_null($this->_template) === true)
    {
      throw new \Albino\Exception\InvalidConfiguration(sprintf('No template file set'));
    }

    if (file_exists($this->_template) === false)
    {
      throw new \Albino\Exception\InvalidConfiguration(sprintf('Template file: \'%s\' doesn\'t exist', $this->_template));
    }

    $this->mergeData($data);
    extract($this->_data);

    ob_start();
    require $this->_template;
    $return = ob_get_clean();

    if ($this->hasDecorator() === true)
    {
      $return = $this->_decorator->render(array('content' => $return));
    }

    return $return;
  }

  /**
   * @param string $module
   * @param string $controller
   * @param string $action
   * @param array $params
   *
   * @return string
   */
  public function renderAction($module, $controller, $action, array $params = array())
  {
    return Application::getInstance()->getDispatcher()->renderAction($module, $controller, $action, $params, false);
  }

  /**
   * @param string $template
   * @return View
   */
  public function setTemplate($template)
  {
    $this->_template = $template;
    return $this;
  }

  /**
   * @param View $view        Should be either null of an instance of \Albino\View
   * @return View
   */
  public function setDecorator($view = null)
  {
    if ($view === false)
    {
      $view = null;
    }

    $this->_decorator = $view;
    return $this;
  }

  /**
   * @return bool
   */
  public function hasDecorator()
  {
    return is_null($this->_decorator) === false;
  }
}
