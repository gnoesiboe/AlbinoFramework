<?php

namespace Albino;

/**
 * DataHolder class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class DataHolder implements \IteratorAggregate
{

  /**
   * @var array
   */
  protected $_data;

  /**
   * An array containing all required keys
   *
   * @var array
   */
  protected $_requiredKeys = array();

  /**
   * Constructor
   *
   * @param array $data
   */
  public function __construct(array $data = array())
  {
    $this->_data = $data;
  }

  /**
   * @param string|int $key
   * @return boolean
   */
  public function delete($key)
  {
    if ($this->has($key))
    {
      unset($this->_data[$key]);

      return true;
    }

    return false;
  }

  /**
   * @param string $key
   * @param mixed $value
   * @return DataHolder
   */
  public function set($key, $value)
  {
    $this->_data[$key] = $value;
    return $this;
  }

  /**
   * @param array $data
   * @return DataHolder
   */
  public function setData($data)
  {
    $this->_data = $data;
    return $this;
  }

  /**
   * @param array $data
   * @return DataHolder
   */
  public function mergeData($data)
  {
    $this->_data = array_merge($this->_data, $data);
    return $this;
  }

  /**
   * @param string|int $key
   * @param mixed $default        Default value to be returned when the key was not found
   *
   * @return mixed
   */
  public function get($key, $default = null)
  {
    if ($this->has($key))
    {
      return $this->_data[$key];
    }

    return $default;
  }

  /**
   * @param string|int $key
   * @return boolean
   */
  public function has($key)
  {
    return array_key_exists($key, $this->_data);
  }

  /**
   * @return array
   */
  public function toArray()
  {
    return $this->_data;
  }

  /**
   * Validates wether or not all the required keys are available
   * for this object.
   *
   * @return bool
   * @throws Exception\InvalidParameters
   */
  public function validate()
  {
    if (count($this->_requiredKeys) === 0)
    {
      return true;
    }

    $diff = array_diff($this->_requiredKeys, array_keys($this->_data));

    if (count($diff) > 0)
    {
      throw new \Albino\Exception\InvalidParameters(sprintf('Missing required data: %s', implode(', ', $diff)));
    }

    return true;
  }

  /**
   * Get collection data iterator
   *
   * @return \ArrayIterator
   */
  public function getIterator()
  {
    return new \ArrayIterator($this->_data);
  }
}