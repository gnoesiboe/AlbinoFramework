<?php

namespace App\Module\Main\Controller;

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
   * Form test
   *
   * @param \Albino\Request $request
   * @return string
   */
  public function formAction(Albino\Request $request)
  {
    $form = new \App\Form\PageForm();
    if ($request->getMethod() === \Albino\Request::METHOD_POST)
    {
      $form->bind($request);
      if ($form->isValid() === true)
      {
        var_dump('valid!');
        echo "<pre>";
        print_r($form->getValues());
        echo "</pre>";
      }
    }

    return $this->_getView()->render(array(
      'form' => $form
    ));
  }

  /**
   * @param \Albino\Request $request
   * @return string
   */
  public function sidebarAction(Albino\Request $request)
  {
    return $this->_getView()
      ->setTemplate($this->_generateTemplatePathFor('Main', 'Sidebar'))
      ->setDecorator(null)
      ->render()
    ;
  }
}