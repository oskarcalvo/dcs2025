<?php

declare(strict_types = 1);

namespace Drupal\ddev11_node_article\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\node\NodeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

// Se pueden apilar también hooks para las
// #[Hook('node_update')]
#[Hook('node_insert')]
final class ArticleNodeInsertHooks {

  use StringTranslationTrait;

  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly Connection $database,
    private readonly MessengerInterface $messenger
  ) {}

  /**
   * Implements hook_ENTITY_TYPE_insert() for node entities.
   */
  public function __invoke(NodeInterface $node) {
    if ($node->bundle() !==  'article') {
      return;
    }

    $this->messenger->addStatus(
      $this->t('Article "@title" was created', [
        '@title' => $node->getTitle()
      ])
    );
  }
}