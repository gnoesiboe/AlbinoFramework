<?php

namespace Albino;

/**
 * Request class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Request extends DataHolder
{

  /**
   * @var string
   */
  const METHOD_GET = 'GET';

  /**
   * @var string
   */
  const METHOD_POST = 'POST';

  /**
   * Constructor
   */
  public function __construct()
  {
    if (php_sapi_name() === 'cli')
    {
      $this->_initCliParams();
    }
    else
    {
      $this->_initGetParameters();
      $this->_initPostParameters();
      $this->_initRequestMethod();
      $this->_initHeaders();
    }
  }

  /**
   * @return string
   */
  public function getMethod()
  {
    return $this->get('_method');
  }

  /**
   * @return mixed
   */
  public function getPath()
  {
    return $this->get('_get')->get('_path');
  }

  /**
   * @return array
   */
  public function getParams()
  {
    return array_merge($this->getGetParams(), $this->getPostParams());
  }

  /**
   * @param string $key
   * @param mixed $default
   *
   * @return string
   */
  public function getParam($key, $default = null)
  {
    return $this->getGetParam($key, $this->getPostParam($key, $default));
  }

  /**
   * @param string $key
   * @return bool
   */
  public function hasParam($key)
  {
    return $this->hasGetParam($key) || $this->hasPostParam($key);
  }

  /**
   * @param mixed $default
   * @return array
   */
  public function getGetParams($default = array())
  {
    return $this->has('_get') ? $this->get('_get')->toArray() : $default;
  }

  /**
   * @param string $key
   * @param string $value
   */
  protected function _setCliParam($key, $value)
  {
    $cli = $this->get('_cli'); /* @var DataHolder $cli */
    $cli->set($key, $value);
    $this->set('_cli', $cli);
  }

  /**
   * @param string $key
   * @param mixed $default
   *
   * @return string
   */
  public function getCliParam($key, $default = null)
  {
    if ($this->hasCliParam($key))
    {
      return $this->get('_cli')->get($key);
    }

    return $default;
  }

  /**
   * @param string $key
   * @return boolean
   */
  public function hasCliParam($key)
  {
    return $this->has('_cli') === true && $this->get('_cli')->has($key) === true;
  }

  /**
   * @param string $key
   * @param mixed $default
   *
   * @return string
   */
  public function getGetParam($key, $default = null)
  {
    if ($this->hasGetParam($key))
    {
      return $this->get('_get')->get($key);
    }

    return $default;
  }

  /**
   * @param string $key
   * @param string $value
   */
  public function setGetParam($key, $value)
  {
    $get = $this->get('_get'); /* @var DataHolder $get */
    $get->set($key, $value);
    $this->set('_get', $get);
  }

  /**
   * @param string $key
   * @return boolean
   */
  public function hasGetParam($key)
  {
    return $this->has('_get') === true && $this->get('_get')->has($key) === true;
  }

  /**
   * @param mixed $default
   * @return array
   */
  public function getPostParams($default = array())
  {
    return $this->has('_post') ? $this->get('_post')->toArray() : $default;
  }

  /**
   * @param string $key
   * @param mixed $default
   * @return string
   */
  public function getPostParam($key, $default = null)
  {
    if ($this->hasPostParam($key) === true)
    {
      return $this->get('_post')->get($key);
    }

    return $default;
  }

  /**
   * @param string $key
   * @return boolean
   */
  public function hasPostParam($key)
  {
    return $this->has('_post') === true && $this->get('_post')->has($key) === true;
  }

  /**
   * @param string $method
   * @return bool
   */
  public function isMethod($method)
  {
    return $this->get('_method') === $method;
  }

  /**
   * @param string $action
   * @return string
   */
  protected function _toActionMethodName($action)
  {
    return $action . 'Action';
  }

  /**
   * Harvests any command line parameters that are
   * formatted like --key=value
   */
  public function _initCliParams()
  {
    $this->set('_cli', new DataHolder());
    $rawParams = $_SERVER['argv'];

    foreach ($rawParams as $param)
    {
      if (preg_match('/^--(?P<key>[^=]+)=(?P<value>.*)$/', $param, $match))
      {
        $this->_setCliParam($match['key'], $match['value']);
      }
    }
  }

  /**
   * Defines the request method
   */
  protected function _initRequestMethod()
  {
    $this->set('_method', $_SERVER['REQUEST_METHOD']);
  }

  /**
   * Harvests the POST parameters from the global
   * $_POST array.
   */
  protected function _initPostParameters()
  {
    $this->set('_post', new DataHolder($_POST));
  }

  /**
   * Harvests the get parameters from the global
   * $_GET array and query string.
   */
  protected function _initGetParameters()
  {
    $get = new DataHolder($_GET);

    // add query params
    $queryString = substr($_SERVER['REQUEST_URI'], strlen($get->get('_path')) + 2);
    foreach (Util::queryStringToArray($queryString) as $key => $value)
    {
      $get->set($key, $value);
    }

    $this->set('_get', $get);
  }

  /**
   * Harvests the request headers.
   */
  protected function _initHeaders()
  {
    $headers = new DataHolder();

    foreach ($_SERVER as $key => $value)
    {
      if (strpos($key, 'HTTP_') === 0)
      {
        $headers->set(substr($key, 5), $value);
      }
      elseif (in_array($key, array('CONTENT_LENGTH', 'CONTENT_TYPE')))
      {
        $headers->set($key, $value);
      }
    }

    $this->set('_headers', $headers);
  }
}
