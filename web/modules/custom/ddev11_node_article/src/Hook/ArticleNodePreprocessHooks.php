<?php

declare(strict_types = 1);

namespace Drupal\ddev11_node_article\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\node\NodeInterface;
use Drupal\Core\Routing\RouteMatchInterface;

#[Hook('preprocess_page', method: 'preprocessPage')]
final class ArticleNodePreprocessHooks {

  public function __construct(
    private readonly RouteMatchInterface $routeMatch
  ) {}

  public function preprocessPage(array &$variables): void
  {
    // Verificar si estamos en una página de nodo
    if ($this->routeMatch->getRouteName() === 'entity.node.canonical') {
      $node = $this->routeMatch->getParameter('node');
      if ($node && $node->getType() === 'article') {
        // Añadir una clase CSS específica para artículos
        $variables['attributes']['class'][] = 'article-page-enhanced';

        // Añadir información adicional al head
        $variables['#attached']['html_head'][] = [
          [
            '#tag' => 'meta',
            '#attributes' => [
              'name' => 'article-type',
              'content' => 'enhanced-article',
            ],
          ],
          'article_meta'
        ];
      }
    }
  }
}
