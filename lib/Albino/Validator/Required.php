<?php

namespace Albino\Validator;

/**
 * Required class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Required extends \Albino\Validator
{

  /**
   * @see parent
   */
  protected function _configure()
  {
    parent::_configure();

    $this->_setMessage('required', 'Field \'%identifier%\' is required');
  }

  /**
   * @param array $values     An array containing all valutes that need to be validated
   *
   * @throws \Albino\Exception
   *
   * @return array
   */
  public function validate(array $values)
  {
    $errors = array();

    foreach ($values as $fieldIdentifier => $fieldValue)
    {
      $fieldValue = trim($fieldValue);
      if (is_null($fieldValue) === true || $fieldValue === '')
      {
        $errors[$fieldIdentifier] = strtr($this->_getMessage('required'), array('%identifier%' => $fieldIdentifier));
      }
    }

    return $errors;
  }
}