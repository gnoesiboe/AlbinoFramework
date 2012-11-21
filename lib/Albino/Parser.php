<?php

namespace Albino;

/**
 * Parser class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
abstract class Parser
{

  /**
   * @abstract
   * @param string $file
   * @return array
   */
  abstract public function parse($file);

  /**
   * @param array $firstArray
   * @param array $secondArray
   *
   * @return array
   */
  protected function _arrayMergeRecursive($firstArray, $secondArray)
  {
    if (is_array($firstArray) && is_array($secondArray))
    {
      foreach ($secondArray as $key => $value)
      {
        if (isset($firstArray[$key]))
        {
          $firstArray[$key] = $this->_arrayMergeRecursive($firstArray[$key], $value);
        }
        else
        {
          if ($key === 0)
          {
            $firstArray = array(0 => $this->_arrayMergeRecursive($firstArray, $value));
          }
          else
          {
            $firstArray[$key] = $value;
          }
        }
      }
    }
    else
    {
      $firstArray = $secondArray;
    }

    return $firstArray;
  }
}