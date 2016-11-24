<?php
namespace Drupal\http2\Cache;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;
use Drupal\Core\Cache\Context\RequestStackCacheContextBase;

/**
 * Class Http2Context.
 */
class Http2CacheContext extends RequestStackCacheContextBase implements CacheContextInterface {

  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return t('Is HTTP/2');
  }

  /**
   * {@inheritdoc}
   */
  public function getContext() {
    $request = $this->requestStack->getCurrentRequest();

    // Apache mod_http2.
    // $_SERVER['HTTP2'] is available with mod_http2 >= 1.4.6.
    // $_SERVER['SERVER_PROTOCOL'] value changed from 'HTTP/2' to 'HTTP/2.0' in
    // mod_http2 1.5.2.
    if ($request->server->get('HTTP2', FALSE) === 'on' || strpos($request->server->get('SERVER_PROTOCOL'), 'HTTP/2') === 0) {
      return '1';
    }

    return '0';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata() {
    return new CacheableMetadata();
  }

}
