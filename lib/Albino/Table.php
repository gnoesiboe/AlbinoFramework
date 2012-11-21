<?php

namespace Albino;

/**
 * Table class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
abstract class Table
{

  /**
   * @var Database
   */
  protected $_database = null;

  /**
   * @var ModelHydrator
   */
  protected $_defaultHydrator = null;

  /**
   * @var array
   */
  protected $_options = array(
    'database' => 'default'           // the database to use
  );

  /**
   * @param array $options
   */
  public function __construct($options = array())
  {
    $this->_options = array_merge($this->_options, $options);
    $this->_configure();
  }

  /**
   * Can be extended to further configure this instance
   */
  protected function _configure()
  {
  }

  /**
   * @param string $query                 Database query to be executed
   * @param array $params                 Query parameters for the PDO statement
   * @param ModelHydrator $hydrator       If omitted, the default hydrator is used
   *
   * @return Collection\Model
   */
  public function executeQuery($query, $params = array(), ModelHydrator $hydrator = null)
  {
    return $this->getDatabase()->executeQuery(
      $query,
      is_null($hydrator) === true ? $this->_getDefaultHydrator() : $hydrator,
      $params
    );
  }

  /**
   * @return ModelHydrator
   */
  protected function _getDefaultHydrator()
  {
    if (is_null($this->_defaultHydrator) === true)
    {
      $this->_defaultHydrator = new ModelHydrator($this->getModelName());
    }

    return $this->_defaultHydrator;
  }

  /**
   * @return \Albino\Model
   */
  public function getModel()
  {
    $modelName = $this->getModelName();
    return new $modelName();
  }

  /**
   * @return string
   */
  public function getModelName()
  {
    return preg_replace('/Table$/', '', get_class($this));
  }

  /**
   * @return Database
   * @throws Exception\InvalidConfiguration
   */
  public function getDatabase()
  {
    if (is_null($this->_database) === true)
    {
      $database = \ALbino\DatabaseManager::getInstance()->getDatabase($this->_options['database']);
      if (is_null($database) === true)
      {
        throw new \Albino\Exception\InvalidConfiguration(sprintf('Database \'%s\' doesn\'t exist', $this->_options['database']));
      }

      $this->_database = $database;
    }

    return $this->_database;
  }
}