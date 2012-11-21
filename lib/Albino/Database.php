<?php

namespace Albino;

/**
 * Database class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Database extends DataHolder
{

  /**
   * @var string
   */
  const TYPE_MYSQL = 'mysql';

  /**
   * @var string
   */
  const DEFAULT_TYPE = 'mysql';

  /**
   * @var \PDO
   */
  private $_connection = null;

  /**
   * @param array $data
   */
  public function __construct(array $data = array())
  {
    parent::__construct(array_merge(array(
      'type' => self::DEFAULT_TYPE
    ), $data));
  }


  /**
   * @return Database
   * @throws Exception\InvalidConfiguration
   *
   * @return array
   */
  public function connect()
  {
    if ($this->hasConnection() === false)
    {
      try
      {
        $this->_connection = new \PDO($this->_generateDsn(), $this->get('user'), $this->get('password'), array(
          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ));
      }
      catch (\PDOException $e)
      {
        throw new \Albino\Exception\InvalidConfiguration($e->getMessage(), $e->getCode(), $e);
      }
    }

    return $this;
  }

  /**
   * Closes db connection
   */
  public function disconnect()
  {
    $this->_connection = null;
  }

  /**
   * @return string
   * @throws Exception\InvalidConfiguration
   */
  protected function _generateDsn()
  {
    switch ($this->get('type'))
    {
      case self::TYPE_MYSQL:
        return sprintf('%s:host=%s;dbname=%s', $this->get('type'), $this->get('host'), $this->get('database'));

      default:
        throw new \Albino\Exception\InvalidConfiguration(sprintf('Type \'%s\' isn\'t supported', $this->get('type')));
    }
  }

  /**
   * @return \PDO
   */
  public function getConnection()
  {
    return $this->_connection;
  }

  /**
   * @return bool
   */
  public function hasConnection()
  {
    return $this->_connection instanceof \PDO;
  }

  /**
   * @return bool
   * @throws Exception\MissingParameter
   */
  protected function _validateConnection()
  {
    if ($this->hasConnection() === false)
    {
      throw new \Albino\Exception\MissingParameter('No database connection available');
    }

    return true;
  }

  public function executeQuery($query, \Albino\ModelHydrator $hydrator, $params = array())
  {
    $this->connect();

    try
    {
      // prepare the statement and add the parameters
      $stmt = $this->getConnection()->prepare($query);
      foreach ($params as $key => $value)
      {
        list($value, $dataType) = $this->_toParamAndDataType($value);
        $stmt->bindParam($key, $value, $dataType);
      }

      // execute the statement
      $success = $stmt->execute();
      if ($success === false)
      {
        throw new \Albino\Exception\InvalidParameters(sprintf('Query \'%s\' failed.', $query));
      }

      // get result and hydrate it to the right form
      $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      return $hydrator->hydrate($results);
    }
    catch (\PDOException $e)
    {
      if (!$e instanceof \Albino\Exception)
      {
        throw new \Albino\Exception\InvalidConfiguration($e->getMessage(), (int) $e->getCode(), $e);
      }

      throw $e;
    }
  }

  /**
   * @param mixed $value
   * @return array
   */
  protected function _toParamAndDataType($value)
  {
    if (is_array($value) === true)
    {
      return $value;
    }

    $dataType = null;
    if (is_numeric($value) === true)
    {
      return array($value, \PDO::PARAM_STR);
    }

    // by default, return as string
    return array((string) $value, \PDO::PARAM_STR);
  }
}
