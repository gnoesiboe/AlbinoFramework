<?php

namespace Albino\Route;

/**
 * Literal class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Literal extends \Albino\Route
{

  /**
   * @param \Albino\Request $request
   * @return bool
   */
  public function match(\Albino\Request $request)
  {
    if ($request->getPath() === $this->getPattern())
    {
      return true;
    }

    return false;
  }
}
