<?php

namespace Albino;

/**
 * Collection class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Collection implements \IteratorAggregate
{

  /**
   * @var array
   */
  protected $_content = array();

  /**
   * @var array
   */
  protected $_meta = array();

  /**
   * @param string $key
   * @param mixed $value
   */
  public function setMeta($key, $value)
  {
    $this->_meta[$key] = $value;
  }

  /**
   * @param string $key
   */
  public function removeMeta($key)
  {
    unset($this->_meta[$key]);
  }

  /**
   * @param mixed $item
   * @param string|int $key
   *
   * @return Collection
   */
  public function push($item, $key = null)
  {
    if (is_null($key) === true)
    {
      $this->_content[] = $item;
    }
    else
    {
      $this->_content[$key] = $item;
    }

    return $this;
  }

  /**
   * @param string $key
   * @return bool
   */
  public function has($key)
  {
    return array_key_exists($key, $this->_content);
  }

  /**
   * @param string|int $key
   * @param null $default     Default value to be returned when the key wasn't found
   *
   * @return mixed
   */
  public function get($key, $default = null)
  {
    if ($this->has($key) === true)
    {
      $this->_content[$key];
    }

    return $default;
  }

  /**
   * @param string|int $key
   * @return bool
   */
  public function remove($key)
  {
    if ($this->has($key))
    {
      unset($this->_content[$key]);

      return true;
    }

    return false;
  }

  /**
   * @return array
   */
  public function toArray()
  {
    $returnArray = array();

    foreach ($this->_content as $key => $value)
    {
      $returnArray[] = is_object($value) && method_exists($value, 'toArray') === true ? $value->toArray() : $value;
    }

    return $returnArray;
  }

  /**
   * Get collection data iterator
   *
   * @return \ArrayIterator
   */
  public function getIterator()
  {
    return new \ArrayIterator($this->_content);
  }

  /**
   * @return mixed
   */
  public function getFirst()
  {
    reset($this->_content);
    return $this->_content[key($this->_content)];
  }
}