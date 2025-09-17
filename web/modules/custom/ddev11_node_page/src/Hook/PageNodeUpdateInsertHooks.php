<?php

declare (strict_types = 1);

namespace Drupal\ddev11_node_article\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Hook\Order\Order;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;

final class PageNodeUpdateInsertHooks {

  use StringTranslationTrait;

  public function __construct(
    private readonly MessengerInterface $messenger
  ){}

  /**
   * #[Hook('some_hook', order: Order::First)]
   * #[Hook('some_hook', order: Order::Last)]
   * #[Hook('some_hook', order: new OrderBefore(['other_module]))]
   */
  #[Hook('node_update', order: Order::Last)]
  #[Hook('node_insert')]
  public function nodeInsertUpdate (NodeInterface $node) {
    if ($node->bundle() !== 'page') {
      return;
    }
    $this->messenger->addStatus(
      $this->t('Page "@title" was created', [
        '@title' => $node->getTitle()
      ])
    );
  }
}
