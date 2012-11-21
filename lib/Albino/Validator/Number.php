<?php

namespace Albino\Validator;

/**
 * Number class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Number extends \Albino\Validator
{

  /**
   * Configure validator
   */
  protected function _configure()
  {
    parent::_configure();

    $this->_setOption('min_amount', null);
    $this->_setOption('max_amount', null);

    $this->_setMessage('invalid', 'value \'%value%\' is not a number');
    $this->_setMessage('min_amount_invalid', 'The value should be at least %min_amount%');
    $this->_setMessage('max_amount_invalid', 'The value should be at most %max_amount%');
  }

  /**
   * @param array $values     An array containing all valutes that need to be validated
   *
   * @throws \Albino\Exception
   *
   * @return mixed
   */
  public function validate(array $values)
  {
    $errors = array();

    foreach ($values as $fieldIdentifier => $fieldValue)
    {
      // validate if this is numerable
      if (is_numeric($fieldValue) === false)
      {
        $errors[$fieldIdentifier] = strtr($this->_getMessage('invalid'), array(
          '%value%' => $fieldValue
        ));
        continue;
      }

      // validate the minimum amount of the number
      if (is_null($this->_getOption('min_amount')) === false && $fieldValue < $this->_getOption('min_amount'))
      {
        $errors[$fieldIdentifier] = strtr($this->_getMessage('min_amount_invalid'), array(
          '%min_amount%' => $this->_getOption('min_amount')
        ));
        continue;
      }

      // validate the maximum amount of the number
      if (is_null($this->_getOption('max_amount')) === false && $fieldValue > $this->_getOption('max_amount'))
      {
        $errors[$fieldIdentifier] = strtr($this->_getMessage('max_amount_invalid'), array(
          '%max_amount%' => $this->_getOption('max_amount')
        ));
        continue;
      }
    }

    return $errors;
  }
}
