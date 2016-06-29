<?php
namespace Drupal\http2;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Modify services for HTTP/2 handling.
 */
class Http2ServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {

    // Add http2 cache context to all render arrays.
    // TODO only apply cache context to affected portions of page.
    $rendererConfig = $container->getParameter('renderer.config');
    $rendererConfig['required_cache_contexts'][] = 'http2';
    $container->setParameter('renderer.config', $rendererConfig);
  }

}
