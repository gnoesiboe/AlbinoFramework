<?php

namespace Albino\Route;

/**
 * Variable class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Variable extends \Albino\Route\Regex
{
  /**
   * @param \Albino\Request $request
   * @return bool
   */
  public function match(\Albino\Request $request)
  {
    $regex = $this->_toRegex($this->get('pattern'));
    $this->set('pattern', $regex);

    return parent::match($request);
  }

  /**
   * @param string $pattern
   * @return string
   */
  protected function _toRegex($pattern)
  {
    $regex = preg_replace('#:([^/]+)#i', '(?P<~$1~>.*)', $pattern);

    return '#' . str_replace('~', '', $regex) . '#';
  }
}
