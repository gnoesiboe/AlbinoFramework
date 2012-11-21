<?php

namespace Albino;

/**
 * Application class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
abstract class Application extends DataHolder
{

  /**
   * @var \Albino\Application
   */
  protected static $_instance = null;

  /**
   * @var Config
   */
  protected $_config;

  /**
   * @var string
   */
  protected $_environment;

  /**
   * @static
   * @return Application
   */
  public static function getInstance()
  {
    if (is_null(self::$_instance) === true)
    {
      self::$_instance = new static();
    }

    return self::$_instance;
  }

  /**
   * @param string $environment
   * @param array $configFile
   */
  public function configure($environment, $configFile)
  {
    $this->_environment = $environment;
    $this->_config = $this->_parseConfigFile($configFile);
  }

  /**
   * @param string $file
   * @return array
   *
   * @throws Exception\InvalidConfiguration
   */
  protected function _parseConfigFile($file)
  {
    if (file_exists($file) === false)
    {
      throw new \Albino\Exception\InvalidConfiguration(sprintf('Config file: \'%s\' doesn\'t exist', $file));
    }

    $parser = new \Albino\Parser\Ini();
    return $parser->parse($file, $this->_environment);
  }

  /**
   * @return Config
   */
  public function getConfig()
  {
    return $this->_config;
  }

  /**
   * @return Request
   */
  public function getRequest()
  {
    return $this->get('_request');
  }

  /**
   * @param Request $request
   * @return Application
   */
  public function setRequest(Request $request)
  {
    $this->set('_request', $request);
    return $this;
  }

  /**
   * @param Response $response
   * @return Application
   */
  public function setResponse(Response $response)
  {
    $this->set('_response', $response);
    return $this;
  }

  /**
   * @param Router $router
   * @return Application
   */
  public function setRouter(Router $router)
  {
    $this->set('_router', $router);
    return $this;
  }

  /**
   * @return Router
   */
  public function getRouter()
  {
    return $this->get('_router');
  }

  /**
   * @param Dispatcher $dispatcher
   * @return Application
   */
  public function setDispatcher(Dispatcher $dispatcher)
  {
    $this->set('_dispatcher', $dispatcher);
    return $this;
  }

  /**
   * @return Dispatcher
   */
  public function getDispatcher()
  {
    return $this->get('_dispatcher');
  }

  /**
   * Start processing the input and return
   * the correct output.
   */
  public function run()
  {
    if (php_sapi_name() === 'cli')
    {
      echo "<pre>";
      print_r($this->getRequest());
      echo "</pre>";
      die();

      //@todo-albino
    }
    else
    {
      $router = $this->getRouter();
      $request = $this->getRequest();

      $router->match($request);

      // get params from the current route and add 'm to the
      // already set GET parameters
      foreach ($router->getCurrentRoute()->getParams() as $key => $value)
      {
        $request->setGetParam($key, $value);
      }

      // dispatch
      $this->getDispatcher()
        ->setRequest($this->getRequest())
        ->forwardTo($router->getCurrentRoute())
      ;
    }
  }
}
