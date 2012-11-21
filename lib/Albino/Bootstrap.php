<?php

namespace Albino;

/**
 * Bootstrap class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Bootstrap
{

  /**
   * @var string
   */
  protected $_defaultApplicationEnv = 'prod';

  /**
   * @var Application
   */
  protected $_application;

  /**
   * @var Autoloader
   */
  protected $_autoloader;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->_initEnvironment();
    $this->_initPaths();
    $this->_initAutoloader();
    $this->_initApplication();
    $this->_configureApplication();
    $this->_initDatabaseManager();
  }

  protected function _initEnvironment()
  {
    defined('APPLICATION_ENV') || define('APPLICATION_ENV', $this->_getApplicationEnv());
  }

  /**
   * Initiates the application
   */
  protected function _initApplication()
  {
    $this->_application = \App\Application::getInstance();
    $this->_application->configure(APPLICATION_ENV, $this->_getConfigFile());
  }

  /**
   * Initiates any database connections
   */
  protected function _initDatabaseManager()
  {
    // implement in application. Get DatabaseManager
  }

  /**
   * @return Application
   */
  public function getApplication()
  {
    return $this->_application;
  }

  /**
   * Configure path constants that are used throughout
   * the application.
   */
  protected function _initPaths()
  {
    defined('PROJECT_PATH') || define('PROJECT_PATH', $this->_getProjectPath());
    defined('APPLICATION_PATH') || define('APPLICATION_PATH', $this->_getApplicationPath());
    defined('LIBRARY_PATH') || define('LIBRARY_PATH', $this->_getLibraryPath());
    defined('DOCUMENT_ROOT') || define('DOCUMENT_ROOT', $this->_getDocumentRoot());
  }

  /**
   * Configures the application representative
   */
  protected function _configureApplication()
  {
    $this->getApplication()
      ->setRequest(\App\Factory::createRequest())
      ->setRouter(\App\Factory::createRouter())
      ->setDispatcher(\App\Factory::createDispatcher())
    ;
  }

  /**
   * @return string
   */
  protected function _getConfigFile()
  {
    return APPLICATION_PATH . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'application.ini';
  }

  /**
   * Configures the autoloader that is used
   * to automatically require php files when they're
   * needed.
   */
  protected function _initAutoloader()
  {
    require_once APPLICATION_PATH . DIRECTORY_SEPARATOR . 'Autoloader.php';
    $this->_autoloader = new \App\Autoloader();
    $this->_autoloader
      ->addNamespaces($this->_getAutoloaderNamespaces())
      ->register(true)
    ;
  }

  /**
   * @return array
   */
  protected function _getAutoloaderNamespaces()
  {
    return array(
      'App'     => PROJECT_PATH,
      'Albino'  => LIBRARY_PATH
    );
  }

  /**
   * @return string
   */
  protected function _getProjectPath()
  {
    return realpath(dirname(__FILE__) . '/../../');
  }

  /**
   * @return string
   */
  protected function _getApplicationPath()
  {
    return realpath(dirname(__FILE__) . '/../../App');
  }

  /**
   * @return string
   */
  protected function _getLibraryPath()
  {
    return realpath(dirname(__FILE__) . '/../');
  }

  /**
   * @return string
   */
  protected function _getDocumentRoot()
  {
    realpath(dirname(__FILE__) . '/../../public');
  }

  /**
   * @return string
   */
  protected function _getApplicationEnv()
  {
    return getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $this->_defaultApplicationEnv;
  }
}