<?php

namespace App\Module\Page\Controller;

use Albino;

/**
 * Index class.
 *
 * @package    App
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Index extends \Albino\Controller
{

  /**
   * @see parent
   */
  public function preExecute()
  {
    parent::preExecute();

    $layout = \App\Factory::createView($this->_generateTemplatePathFor('Main', 'Layout'));
    $this->_getView()->setDecorator($layout);
  }

  /**
   * @param \Albino\Request $request
   * @return string
   */
  public function indexAction(Albino\Request $request)
  {
    $pages = \App\Model\PageTable::getInstance()->getAll();

    return $this->_getView()->render(array(
      'pages' => $pages
    ));
  }

  /**
   * @param \Albino\Request $request
   * @return string
   */
  public function detailAction(Albino\Request $request)
  {
    $page = \App\Model\PageTable::getInstance()->getOneById($request->getParam('page_id'));

    return $this->_getView()->render(array(
      'page' => $page
    ));
  }
}