<?php

namespace ALbino;

/**
 * DatabaseManager class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class DatabaseManager extends DataHolder
{

  /**
   * @var DatabaseManager
   */
  protected static $_instance = null;

  /**
   * @static
   * @return DatabaseManager
   */
  public static function getInstance()
  {
    if (is_null(self::$_instance) === true)
    {
      self::$_instance = new DatabaseManager();
    }

    return self::$_instance;
  }

  /**
   * @param string $identifier
   * @param Database $database
   *
   * @return DatabaseManager
   */
  public function setDatabase($identifier, Database $database)
  {
    $this->set($identifier, $database);
    return $this;
  }

  /**
   * @param string  $identifier
   * @return Database
   */
  public function getDatabase($identifier)
  {
    return $this->get($identifier);
  }
}