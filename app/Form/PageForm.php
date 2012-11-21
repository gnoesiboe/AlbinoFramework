<?php

namespace App\Form;

/**
 * PageForm class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class PageForm extends \Albino\Form
{
  /**
   * Configure the form
   */
  protected function _configure()
  {
    // setup widgets
    $this->_setField('title');
    $this->_setField('description');
    $this->_setField('amount');

    // setup validators
    $this
      ->_setValidator('required', new \Albino\Validator\Required())
      ->_setValidatorFields('required', array('title', 'description', 'amount'))
    ;

    $this
      ->_setValidator('string', new \Albino\Validator\String(array('min_length' => 20)))
      ->_setValidatorFields('string', array('title', 'description'))
    ;

    $this
      ->_setValidator('number', new \Albino\Validator\Number(array('max_amount' => 1)))
      ->_setValidatorFields('number', array('amount'))
    ;
  }
}