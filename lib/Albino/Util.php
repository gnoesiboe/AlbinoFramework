<?php

namespace Albino;

/**
 * Util class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Util
{

  /**
   * @static
   * @param string $queryString
   * @return array
   */
  public static function queryStringToArray($queryString)
  {
    if (strlen($queryString) === 0)
    {
      return array();
    }

    if ($queryString[0] === '?')
    {
      $queryString = substr($queryString, 1);
    }

    $return = array();
    foreach (explode('&', $queryString) as $item)
    {
      $result = explode('=', $item);

      $return[$result[0]] = isset($result[1]) ? $result[1] : null;
    }

    return $return;
  }

  /**
   * @static
   * @param string $camelCasedWord
   * @return string
   */
  public static function toUnderscore($camelCasedWord)
  {
    $replacePairs = array(
      '/([A-Z]+)([A-Z][a-z])/' => '\\1_\\2',
      '/([a-z\d])([A-Z])/'     => '\\1_\\2'
    );

    return strtolower(preg_replace(array_keys($replacePairs), array_values($replacePairs), $camelCasedWord));
  }
}
