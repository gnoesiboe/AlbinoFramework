<?php

namespace Albino;

/**
 * Form class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
abstract class Form
{

  /**
   * @var array
   */
  protected $_fields = array();

  /**
   * @var array
   */
  protected $_validators = array();

  /**
   * @var array
   */
  protected $_validatorFields = array();

  /**
   * @var string
   */
  protected $_namespace = null;

  /**
   * @var array
   */
  protected $_errors = array();

  /**
   * @var bool
   */
  protected $_isBound = false;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->_configure();
  }

  /**
   * Configure the form
   */
  abstract protected function _configure();

  /**
   * @param Request $request
   */
  public function bind(Request $request)
  {
    $namespace = $this->getNamespace();

    if (is_null($namespace) === true)
    {
      $this->_doBind($request->getParams());
    }
    else
    {
      $this->_doBind($request->getParam($namespace, array()));
    }

    $this->_isBound = true;
  }

  /**
   * @return bool
   * @throws Exception\WrongOrder
   */
  public function isValid()
  {
    // make sure that the request has been validated
    if ($this->_isBound === false)
    {
      throw new \Albino\Exception\WrongOrder('The request should be bound to the form first');
    }

    return $this->hasErrors() === false;
  }

  /**
   * @return array
   */
  public function getValues()
  {
    $values = array();
    foreach ($this->_fields as $identifier => $field)
    {
      /* @var \Albino\FormField $field */

      $values[$identifier] = $field->getValue();
    }

    return $values;
  }

  /**
   * @param string $identifier
   * @return \Albino\FormField
   * @throws Exception\InvalidParameters
   */
  public function getField($identifier)
  {
    if ($this->_hasField($identifier) === false)
    {
      throw new \Albino\Exception\InvalidParameters(sprintf('No field with identifier \'%s\' exists', $identifier));
    }

    return $this->_fields[$identifier];
  }

  /**
   * @return array
   */
  public function getErrors()
  {
    return $this->_errors;
  }

  /**
   * @return bool
   */
  public function hasErrors()
  {
    return count($this->_errors) > 0;
  }

  /**
   * @param array $params
   */
  protected function _doBind($params)
  {
    // make sure all fields are validated, and not just the fields that params are
    // provided for. Otherwise a required validator, for instance, would not work
    $params = array_merge(
      array_combine(array_keys($this->_fields), array_fill(0, count($this->_fields), null)),
      $params
    );

    $this->_errors = array();
    foreach ($this->_validators as $validatorIdentifier => $validator)
    {
      /* @var \Albino\Validator $validator */

      // send only the fields that are to be validated by this validator
      // to the validator
      $fieldsToValidatie = array_intersect_key(
        $params,
        array_combine($this->_validatorFields[$validatorIdentifier], $this->_validatorFields[$validatorIdentifier])
      );

      // validate the fields and if there are any errors, add them
      // to the errors array
      $validatorErrors = $validator->validate($fieldsToValidatie);
      if (count($validatorErrors) > 0)
      {
        $this->_errors[$validatorIdentifier] = $validatorErrors;
      }
    }

    $this->_setFieldValues($params);
    $this->_distributeErrors($this->_errors);
  }

  /**
   * @param array $errors
   */
  protected function _distributeErrors(array $errors)
  {
    foreach ($errors as $validatorErrors)
    {
      /* @var array $validatorErrors */

      foreach ($validatorErrors as $fieldIdentifier => $message)
      {
        $this->getField($fieldIdentifier)->addError($message);
      }
    }
  }

  /**
   * @param array $params
   */
  protected function _setFieldValues(array $params)
  {
    foreach ($this->_fields as $identifier => $field)
    {
      /* @var \Albino\FormField $field */

      $field->setValue($params[$identifier]);
    }
  }

  /**
   * @return string
   */
  public function getNamespace()
  {
    return $this->_namespace;
  }

  /**
   * @param string $namespace
   */
  protected function _setNamespace($namespace)
  {
    $this->_namespace = $namespace;
  }

  /**
   * @param string $identifier
   * @param array $options
   */
  protected function _setField($identifier, array $options = array())
  {
    $this->_fields[$identifier] = \App\Factory::createFormField($options);
  }

  /**
   * @param string $identifier
   * @return bool
   */
  protected function _hasField($identifier)
  {
    return isset($this->_fields[$identifier]);
  }

  /**
   * @param string $identifier      Unique identifier for the validator within this form
   * @param Validator $validator
   *
   * @return Form
   */
  protected function _setValidator($identifier, \Albino\Validator $validator)
  {
    $this->_validators[$identifier] = $validator;
    return $this;
  }

  /**
   * @param string $identifier
   */
  protected function _removeValidator($identifier)
  {
    unset($this->_validators[$identifier]);
    unset($this->_validatorFields[$identifier]);
  }

  /**
   * @param string $identifier
   * @return bool
   */
  protected function _hasValidator($identifier)
  {
    return array_key_exists($identifier, $this->_validators);
  }

  /**
   * @param string $identifier    The identifier of the set validator for this form
   * @param array $fields         An array with identifiers of the fields that should be validated using this validator
   *
   * @throws Exception\InvalidParameters
   */
  protected function _setValidatorFields($identifier, array $fields)
  {
    if ($this->_hasValidator($identifier) === false)
    {
      throw new \Albino\Exception\InvalidParameters(sprintf('Validator with identifier \'%s\' doesn\'t exist on this form', $identifier));
    }

    $this->_validatorFields[$identifier] = $fields;
  }

  /**
   * @param string $identifier
   * @return array
   * @throws Exception\InvalidParameters
   */
  protected function _getValidatorFields($identifier)
  {
    if ($this->_hasValidator($identifier) === false)
    {
      throw new \Albino\Exception\InvalidParameters(sprintf('Validator with identifier \'%s\' doesn\'t exist on this form', $identifier));
    }

    return $this->_validatorFields[$identifier];
  }
}