<?php

namespace Albino\Collection;

/**
 * Model class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Model extends \Albino\Collection
{

  /**
   * @var string
   */
  protected $_model = null;

  /**
   * @param string $model
   */
  public function __construct($model)
  {
    $this->_model = $model;
  }
}