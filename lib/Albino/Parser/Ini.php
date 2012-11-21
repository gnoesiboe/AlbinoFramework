<?php

namespace Albino\Parser;

/**
 * Ini class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Ini extends \Albino\Parser
{

  /**
   * @param string $file
   * @param string $section
   *
   * @return array
   *
   * @throws \Albino\Exception\InvalidConfiguration
   */
  public function parse($file, $section = null)
  {
    $data = parse_ini_file($file, true, INI_SCANNER_NORMAL);

    if (is_null($section) === false)
    {
      if (isset($data[$section]) === false)
      {
        throw new \Albino\Exception\InvalidConfiguration(sprintf('Section \'%s\' doesn\'t exist', $section));
      }

      $return = $data[$section];

      if (isset($return['_extends']) === true)
      {
        $return = $this->_processExtends($data, $return['_extends'], $return);
      }
    }
    else
    {
      $return = $data;
    }

    unset($return['_extends']);

    return $return;
  }

  /**
   * @param array $data
   * @param string $section
   * @param array $return
   *
   * @return array
   *
   * @throws \Albino\Exception\InvalidConfiguration
   */
  protected function _processExtends(array $data, $section, array $return)
  {
    if (isset($data[$section]) === false)
    {
      throw new \Albino\Exception\InvalidConfiguration(sprintf('Section \'%s\' doesn\'t exist', $section));
    }

    $sectionData = $data[$section];

    // check if this section data actually extends another section
    if (is_array($sectionData) && isset($sectionData['_extends']))
    {
      $return = $this->_processExtends($data, $sectionData['_extends'], $return);
    }

    $return = $this->_arrayMergeRecursive($return, $sectionData);

    return $return;
  }
}