<?php

namespace Albino\Controller\Error;

/**
 * Index class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Index extends \Albino\Controller
{

  /**
   * 404 NOT FOUND action
   */
  public function notFoundAction()
  {
    $response = \App\Factory::createResponse();
    $response->setStatusCode(404);
    $response->setStatusText('NOT FOUND');
    $response->setContent('NOT FOUND');

    return $response;
  }
}