<?php

namespace App\Model;

/**
 * Page class.
 *
 * @package    <package>
 * @subpackage <subpackage
 * @author     <author>
 * @copyright  Freshheads BV
 *
 * @method \string getId
 * @method \string getTitle
 * @method \string getDescription
 */
class Page extends \Albino\Model
{

  /**
   * @return string
   */
  public function getUri()
  {
    //@todo-albino create a generate method on the routes to generate urls
    return '/pages/' . $this->getId();
  }
}