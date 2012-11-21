<?php

namespace Albino;

/**
 * Validator class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
abstract class Validator
{

  /**
   * @var string
   */
  const TYPE_STRING = 'string';

  /**
   * @var array
   */
  protected $_options = array();

  /**
   * @var array
   */
  protected $_messages = array();


  /**
   * @param array $options
   * @param array $messages
   */
  public function __construct(array $options = array(), array $messages = array())
  {
    $this->_configure();

    $this->_validateOptions($options);
    $this->_validateMessages($messages);

    $this->_options = array_merge($this->_options, $options);
    $this->_messages = array_merge($this->_messages, $messages);
  }

  /**
   * Method that can be extended to add extra configuration
   */
  protected function _configure()
  {
  }

  /**
   * Validates wether the current validator allowes
   * the provided messages.
   *
   * @param array $messages
   * @throws Exception\InvalidConfiguration
   */
  protected function _validateMessages(array $messages)
  {
    $nonAllowedMessages = array_diff(array_keys($messages), array_keys($this->_messages));
    if (count($nonAllowedMessages) > 0)
    {
      throw new \Albino\Exception\InvalidConfiguration(sprintf('The following messages are not supported: %s', implode(', ', $nonAllowedMessages)));
    }
  }

  /**
   * Validates wether the current validator allowes
   * the provided options.
   *
   * @param array $options
   * @throws Exception\InvalidConfiguration
   */
  protected function _validateOptions(array $options)
  {
    $nonAllowedOptions = array_diff(array_keys($options), array_keys($this->_options));
    if (count($nonAllowedOptions) > 0)
    {
      throw new \Albino\Exception\InvalidConfiguration(sprintf('The following options are not supported: %s', implode(', ', $nonAllowedOptions)));
    }
  }

  /**
   * @abstract
   * @param array $values     An array containing all valutes that need to be validated
   *
   * @throws \Albino\Exception
   *
   * @return array
   */
  abstract public function validate(array $values);

  /**
   * @param string $key
   * @param string $value
   */
  protected function _setMessage($key, $value)
  {
    $this->_messages[$key] = $value;
  }

  /**
   * @param string $key
   * @return mixed
   *
   * @throws Exception\InvalidParameters
   */
  protected function _getMessage($key)
  {
    if ($this->_hasMessage($key) === false)
    {
      throw new \Albino\Exception\InvalidParameters(sprintf('Message \'%s\' doesn\'t exist', $key));
    }

    return $this->_messages[$key];
  }

  /**
   * @param string $key
   * @return bool
   */
  protected function _hasMessage($key)
  {
    return isset($this->_messages[$key]);
  }

  /**
   * @param string $key
   * @param mixed $value
   */
  protected function _setOption($key, $value)
  {
    $this->_options[$key] = $value;
  }

  /**
   * @param string $key
   * @throws Exception\InvalidParameters
   *
   * @return mixed
   */
  protected function _getOption($key)
  {
    if ($this->_hasOption($key) === false)
    {

    }

    return $this->_options[$key];
  }

  /**
   * @param string $key
   * @return bool
   */
  protected function _hasOption($key)
  {
    return isset($this->_options[$key]);
  }
}