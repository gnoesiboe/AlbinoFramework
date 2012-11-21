<?php

namespace App;

require_once dirname(__FILE__) . '/../lib/Albino/Bootstrap.php';

use Albino;

/**
 * Bootstrap class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Bootstrap extends Albino\Bootstrap
{

  /**
   * Initiates database connections
   */
  protected function _initDatabaseManager()
  {
    \ALbino\DatabaseManager::getInstance()->setDatabase('default', new \Albino\Database(array(
      'user'      => 'root',
      'password'  => 'root',
      'host'      => 'localhost',
      'database'  => 'albino',
      'type'      => 'mysql'
    )));
  }
}