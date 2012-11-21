<?php

namespace Albino;

/**
 * Autoloader class.
 *
 * @package    <package>
 * @subpackage <subpackage
 * @author     <author>
 * @copyright  Freshheads BV
 */
class Autoloader
{

  /**
   * @var array
   */
  protected $_namespaces = array();

  /**
   * @return array
   */
  public function getNamespaces()
  {
    return $this->_namespaces;
  }

  /**
   * @param string $namespace
   * @return bool
   */
  public function hasNamespace($namespace)
  {
    return array_key_exists($namespace, $this->_namespaces);
  }

  /**
   * @param string $namespace
   * @param array|string $paths
   */
  public function addNamespace($namespace, $paths)
  {
    $this->_namespaces[$namespace] = (array) $paths;
  }

  /**
   * @param array $namespaces
   * @return Autoloader
   */
  public function addNamespaces($namespaces)
  {
    foreach ($namespaces as $namespace => $paths)
    {
      $this->addNamespace($namespace, $paths);
    }

    return $this;
  }

  /**
   * @param string $class
   */
  public function requireClass($class)
  {
    if ($file = $this->getFileForClass($class))
    {
      require_once $file;
    }
  }

  /**
   * @param string $class
   * @return string
   */
  public function getFileForClass($class)
  {
    if ($class[0] === '\\')
    {
      $class = substr($class, 1);
    }

    $pos = strrpos($class, '\\');
    $pos = strrpos($class, '\\');
    if ($pos !== false)
    {
      // namespaced class name
      $searchedNamespace = substr($class, 0, $pos);

      foreach ($this->getNamespaces() as $namespace => $paths)
      {
        if (strpos($searchedNamespace, $namespace))
        {
          continue;
        }

        foreach ($paths as $path)
        {
          $className = substr($class, $pos + 1);
          $file = $path . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $searchedNamespace) . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

          if (file_exists($file))
          {
            return $file;
          }
        }
      }
    }

    return null;
  }

  /**
   * @param boolean $prepend
   * @return Autoloader
   */
  public function register($prepend = false)
  {
    spl_autoload_register(array($this, 'requireClass'), true, $prepend);
    return $this;
  }
}