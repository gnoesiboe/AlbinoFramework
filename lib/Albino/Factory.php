<?php

namespace Albino;

/**
 * Factory class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
abstract class Factory
{

  /**
   * @var array
   */
  protected static $_tableStack = array();

  /**
   * @static
   * @return Request
   */
  public static function createRequest()
  {
    return new Request();
  }

  /**
   * @static
   * @return Response
   */
  public static function createResponse()
  {
    return new Response();
  }

  /**
   * @static
   *
   * @param string $type
   * @param array $options
   *
   * @return Route
   *
   * @throws Exception\InvalidParameters
   */
  public static function createRoute($type = \Albino\Route::TYPE_LITERAL, $options = array())
  {
    switch ($type)
    {
      case \Albino\Route::TYPE_LITERAL:
        return new Route\Literal($options);
        break;

      case \Albino\Route::TYPE_REGEX:
        return new Route\Regex($options);
        break;

      case \Albino\Route::TYPE_VARIABLE:
        return new Route\Variable($options);
        break;
    }

    throw new \Albino\Exception\InvalidParameters(sprintf('Route type \'%s\' not found', $type));
  }

  /**
   * @static
   * @return Router
   */
  public static function createRouter()
  {
    return new \App\Router();
  }

  /**
   * @static
   * @return Dispatcher
   */
  public static function createDispatcher()
  {
    return new \Albino\Dispatcher();
  }

  /**
   * @static
   * @param null $template
   * @param array $data
   * @return View
   */
  public static function createView($template = null, $data = array())
  {
    return new \Albino\View($template, $data);
  }

  /**
   * @static
   * @return Config
   */
  public static function createConfig()
  {
    return new \Albino\Config();
  }

  /**
   * @static
   * @param array $options
   *
   * @return FormField
   */
  public static function createFormField($options = array())
  {
    return new \Albino\FormField($options);
  }

  /**
   * @static
   * @param $type
   * @param array $options
   *
   * @return Validator\String
   *
   * @throws Exception\InvalidParameters
   */
  public static function createValidator($type, $options = array())
  {
    switch ($type)
    {
      case \Albino\Validator::TYPE_STRING:
        return new \Albino\Validator\String($options);
        break;
    }

    throw new \Albino\Exception\InvalidParameters(sprintf('Route type \'%s\' not found', $type));
  }
}