<?php

namespace App;

/**
 * Router class.
 *
 * @package    <package>
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Router extends \Albino\Router
{

  /**
   * To be implemented by the application.
   */
  public function configure()
  {
    $this->setRoute('homepage', Factory::createRoute('literal', array(
      'pattern'     => '/',
      'module'      => 'Main',
      'controller'  => 'Index',
      'action'      => 'index'
    )));

    $this->setRoute('form_index', Factory::createRoute('literal', array(
      'pattern'     => '/form',
      'module'      => 'Main',
      'controller'  => 'Index',
      'action'      => 'form'
    )));

    $this->setRoute('page_index', Factory::createRoute('literal', array(
      'pattern'     => '/pages',
      'module'      => 'Page',
      'controller'  => 'Index',
      'action'      => 'index'
    )));

    $this->setRoute('page', Factory::createRoute('variable', array(
      'pattern'     => '/pages/:page_id',
      'module'      => 'Page',
      'controller'  => 'Index',
      'action'      => 'detail'
    )));
  }
}