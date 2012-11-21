<?php

namespace Albino;

/**
 * Route class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
abstract class Route extends DataHolder
{

  /**
   * @var string
   */
  const TYPE_LITERAL = 'literal';

  /**
   * @var string
   */
  const TYPE_REGEX = 'regex';

  /**
   * @var string
   */
  const TYPE_VARIABLE = 'variable';

  /**
   * @var array
   */
  protected $_requiredKeys = array(
    'pattern',
    'module',
    'controller',
    'action'
  );

  /**
   * @param array $options
   */
  public function __construct($options = array())
  {
    parent::__construct($options);

    $this->validate();
  }

  /**
   * @return string
   */
  public function getModule()
  {
    return $this->get('module');
  }

  /**
   * @return string
   */
  public function getController()
  {
    return $this->get('controller');
  }

  /**
   * @return string
   */
  public function getAction()
  {
    return $this->get('action');
  }

  /**
   * Returns variables that were parsed from
   * the request url.
   *
   * @return array
   */
  public function getParams()
  {
    return $this->get('_params', array());
  }

  /**
   * @param string $key
   * @param null $default
   *
   * @return string
   */
  public function getParam($key, $default = null)
  {
    $params = $this->get('_params', array());

    return isset($params[$key]) ? $params[$key] : $default;
  }

  /**
   * @param string $key
   * @param string $value
   */
  protected function setParam($key, $value)
  {
    $params = $this->get('_params', array());
    $params[$key] = $value;
    $this->set('_params', $params);
  }

  /**
   * @return string
   */
  public function getPattern()
  {
    return $this->get('pattern');
  }

  /**
   * @param \Albino\Request $request
   * @return bool
   */
  abstract public function match(\Albino\Request $request);
}