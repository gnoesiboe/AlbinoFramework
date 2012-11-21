<?php

namespace Albino;

/**
 * FormField class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class FormField
{

  /**
   * @var array
   */
  protected $_options = array(
    'default' => '',
    'value' => null
  );

  /**
   * @var array
   */
  protected $_errors = array();

  /**
   * @param array $options
   */
  public function __construct($options = array())
  {
    $this->_options = array_merge($this->_options, $options);
  }

  /**
   * @return string
   */
  public function getValue()
  {
    return is_null($this->_options['value']) === false ? $this->_options['value'] : $this->_options['default'];
  }

  /**
   * @param string $value
   */
  public function setValue($value)
  {
    $this->_options['value'] = $value;
  }

  /**
   * @param array $messages
   */
  public function setErrors(array $messages)
  {
    $this->_errors = $messages;
  }

  /**
   * @param string $message
   */
  public function addError($message)
  {
    $this->_errors[] = $message;
  }

  /**
   * @return bool
   */
  public function hasErrors()
  {
    return count($this->_errors) > 0;
  }

  /**
   * @return array
   */
  public function getErrors()
  {
    return $this->_errors;
  }
}