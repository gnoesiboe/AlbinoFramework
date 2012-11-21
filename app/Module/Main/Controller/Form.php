<?php

namespace App\Module\Main\Controller;

/**
 * Form class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Form extends \Albino\Controller
{

  /**
   * @param \Albino\Request $request
   * @param array $params
   *
   * @return string
   */
  public function errorsAction(\Albino\Request $request, array $params)
  {
    /* @var \Albino\FormField $field */
    $field = $params['field'];
    if ($field->hasErrors() === false)
    {
      return '';
    }

    return $this->_getView()
      ->setTemplate($this->_generateTemplatePathFor('Main', 'FormFieldErrors'))
      ->setDecorator(null)
      ->render(array('field' => $field))
    ;
  }
}