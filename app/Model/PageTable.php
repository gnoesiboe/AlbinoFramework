<?php

namespace App\Model;

/**
 * Page class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class PageTable extends \Albino\Table
{

  /**
   * @var PageTable
   */
  protected static $_instance = null;

  /**
   * @static
   * @return PageTable
   */
  public static function getInstance()
  {
    if (is_null(self::$_instance) === true)
    {
      self::$_instance = new PageTable();
    }

    return self::$_instance;
  }

  /**
   * @param int $id
   * @return \Albino\Collection\Model
   */
  public function getOneById($id)
  {
    return $this->executeQuery('SELECT * FROM page WHERE id = :id', array(':id' => $id))->getFirst();
  }

  /**
   * @return \Albino\Collection\Model
   */
  public function getAll()
  {
    return $this->executeQuery('SELECT * FROM page');
  }
}
