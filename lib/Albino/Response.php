<?php

namespace Albino;

/**
 * Response class.
 *
 * @package    Albino
 * @author     Gijs Nieuwenhuis <gijs.nieuwenhuis@freshheads.com>
 */
class Response extends DataHolder
{

  /**
   * @var int
   */
  const DEFAULT_STATUS_CODE = 200;

  /**
   * @var string
   */
  const DEFAULT_STATUS_TEXT = 'OK';

  /**
   * @var string
   */
  const DEFAULT_PROTOCOL = 'HTTP/1.0';

  /**
   * @param array $data
   */
  public function __construct(array $data = array())
  {
    parent::__construct(array_merge(
      array(
        '_headers'      => $this->_getDefaultHeaders(),
        '_protocol'     => self::DEFAULT_PROTOCOL,
        '_statusCode'   => self::DEFAULT_STATUS_CODE,
        '_statusText'   => self::DEFAULT_STATUS_TEXT,
        '_content'      => ''
      ),
      $data
    ));
  }

  /**
   * @param string $code
   */
  public function setStatusCode($code)
  {
    $this->set('_statusCode', (string) $code);
  }

  /**
   * @param string $text
   */
  public function setStatusText($text)
  {
    $this->set('_statusText', $text);
  }

  /**
   * @param string $key
   * @param string $value
   *
   * @return Response
   */
  public function setHeader($key, $value)
  {
    $headers = $this->get('_headers', array());
    $headers[$key] = $value;
    $this->set('_headers', $headers);

    return $this;
  }

  /**
   * @return array
   */
  public function getHeaders()
  {
    return $this->get('_headers');
  }

  /**
   * @param string $content
   * @return Response
   */
  public function setContent($content)
  {
    $this->set('_content', $content);
    return $this;
  }

  /**
   * @return string
   */
  public function getContent()
  {
    return $this->get('_content');
  }

  /**
   * Outputs the response headers.
   */
  public function sendHeaders()
  {
    $status = $this->get('_protocol') . ' ' . $this->get('_statusCode') . ' ' . $this->get('_statusText');
    header($status);

    foreach ($this->getHeaders() as $name => $value)
    {
      header($name . ': ' . $value);
    }
  }

  /**
   * Outputs the response content.
   */
  public function renderContent()
  {
    echo $this->getContent();
  }

  /**
   * @return array
   */
  protected function _getDefaultHeaders()
  {
    return array(
      'Content-Type' => 'text/html; charset=utf-8'
    );
  }
}