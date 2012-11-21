<?php

namespace Albino;

/**
 * ModelHydrator class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class ModelHydrator
{

  /**
   * @var array
   */
  protected $_options = array(
    'collection' => '\Albino\Collection\Model'
  );

  /**
   * @var string
   */
  protected $_model;

  /**
   * @param string $model
   * @param array $options
   */
  public function __construct($model, array $options = array())
  {
    $this->_model = $model;
    $this->_options = array_merge($this->_options, $options);
  }

  /**
   * @param array $results
   * @return \Albino\Collection\Model
   */
  public function hydrate(array $results)
  {
    $collectionClass = $this->_options['collection'];
    $collection = new $collectionClass($this->_model); /* @var \Albino\Collection\Model $collection */

    foreach ($results as $result)
    {

      /* @var \Albino\Model $model */
      $model = new $this->_model();
      $collection->push($model->fromDbResults($result));
    }

    return $collection;
  }
}