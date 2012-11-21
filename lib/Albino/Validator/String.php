<?php

namespace Albino\Validator;

/**
 * String class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class String extends \Albino\Validator
{

  /**
   * @see parent
   */
  protected function _configure()
  {
    parent::_configure();

    $this->_setOption('min_length', null);
    $this->_setOption('max_length', null);

    $this->_setMessage('min_length_invalid', 'The value should be at least %min_length% characters long');
    $this->_setMessage('max_length_invalid', 'The value should be at most %max_length% characters long');
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
      // validate the minimum length of the string
      if (is_null($this->_getOption('min_length')) === false && strlen($fieldValue) < $this->_getOption('min_length'))
      {
        $errors[$fieldIdentifier] = strtr($this->_getMessage('min_length_invalid'), array(
          '%min_length%' => $this->_getOption('min_length')
        ));
        continue;
      }

      // validate the maximum length of the string
      if (is_null($this->_getOption('max_length')) === false && strlen($fieldValue) > $this->_getOption('max_length'))
      {
        $errors[$fieldIdentifier] = strtr($this->_getMessage('max_length_invalid'), array(
          '%max_length%' => $this->_getOption('max_length')
        ));
        continue;
      }
    }

    return $errors;
  }
}
