<?php
namespace Drupal\http2\Render;

use Drupal\Core\Asset\AttachedAssetsInterface;
use Drupal\Core\Render\HtmlResponseAttachmentsProcessor;

/**
 * Class HtmlResponseAttachmentsProcessor.
 */
class Http2AttachmentsProcessor extends HtmlResponseAttachmentsProcessor {

  /**
   * {@inheritdoc}
   */
  protected function processAssetLibraries(AttachedAssetsInterface $assets, array $placeholders) {
    $variables = [];

    // Don't optimize assets for HTTP/2 requests.
    $request = $this->requestStack->getCurrentRequest();
    $isHttp2 = FALSE;
    // Apache mod_http2.
    if ($request->server->get('SERVER_PROTOCOL') === 'HTTP/2') {
      $isHttp2 = TRUE;
    }

    // Print styles - if present.
    if (isset($placeholders['styles'])) {
      // Optimize CSS if necessary, but only during normal site operation.
      $optimize_css = !defined('MAINTENANCE_MODE') && $this->config->get('css.preprocess') && !$isHttp2;
      $variables['styles'] = $this->cssCollectionRenderer->render($this->assetResolver->getCssAssets($assets, $optimize_css));
    }

    // Print scripts - if any are present.
    if (isset($placeholders['scripts']) || isset($placeholders['scripts_bottom'])) {
      // Optimize JS if necessary, but only during normal site operation.
      $optimize_js = !defined('MAINTENANCE_MODE') && !\Drupal::state()->get('system.maintenance_mode') && $this->config->get('js.preprocess') && !$isHttp2;
      list($js_assets_header, $js_assets_footer) = $this->assetResolver->getJsAssets($assets, $optimize_js);
      $variables['scripts'] = $this->jsCollectionRenderer->render($js_assets_header);
      $variables['scripts_bottom'] = $this->jsCollectionRenderer->render($js_assets_footer);
    }

    return $variables;
  }

}
