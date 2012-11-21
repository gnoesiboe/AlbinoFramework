<?php

namespace Albino;

/**
 * Model class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Model extends DataHolder
{

  /**
   * Builds this model from DB results
   *
   * @param array $results
   * @return Model
   */
  public function fromDbResults($results)
  {
    foreach ($results as $key => $value)
    {
      $this->set($key, $value);
    }

    return $this;
  }

  /**
   * Magic method used to provide getter and
   * setter methods for models.
   *
   * @param string $name
   * @param array $arguments
   *
   * @throws Exception\InvalidMethod
   *
   * @return mixed|bool
   */

  function __call($name, $arguments)
  {
    if (preg_match('/^(get|^set)(.*)$/', $name, $match) !== false)
    {
      $action = $match[1];
      $key = \Albino\Util::toUnderscore($match[2]);

      if ($action === 'get')
      {
        if ($this->has($key) === false)
        {
          throw new \Albino\Exception\InvalidMethod(sprintf('Unknown method \'%s::%s\'', get_class($this), $name));
        }

        return $this->get($key);
      }
      else // $action === 'set'
      {
        $this->set($key, $arguments[0]);
        return true;
      }
    }

    throw new \Albino\Exception\InvalidMethod(sprintf('Unknown method \'%s::%s\'', get_class($this), $name));
  }
}