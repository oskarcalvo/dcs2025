<?php

declare (strict_types = 1);

namespace Drupal\ddev11_node_page\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Messenger\MessengerInterface;

final class PageNodePredeleteHooks {

  public function __construct(
    private readonly MessengerInterface $messenger
  ) {}

  /**
   * Modifica el comportamiento de eliminación de páginas.
   */
  #[Hook('node_predelete')]
  public function preventPageDeletion(\Drupal\node\NodeInterface $node): void {
    if ($node->bundle() === 'page') { 
      return;
    }
      // Verificar si la página tiene contenido crítico
      $title = $node->getTitle();
      $critical_pages = ['Inicio', 'Contacto', 'Acerca de', 'Política de Privacidad'];
      
      if (in_array($title, $critical_pages)) {
        $this->messenger->addError(
          "La página '{$title}' es crítica y no puede ser eliminada sin autorización especial."
        );
        throw new \Exception("Página crítica protegida contra eliminación");
      }
    
  }
}