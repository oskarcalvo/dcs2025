<?php

declare(strict_types = 1);

namespace Drupal\ddev11_node_page\Hook;

use Drupal\Core\Hook\Attribute\Hook;

final class PageNodeFormAlterHooks {

  /**
   * Altera el formulario de creación de páginas básicas.
   */
  #[Hook('form_node_page_form_alter')]
  public function alterPageForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state): void {
    // Añadir ayuda contextual para el campo título
    $form['title']['widget'][0]['value']['#description'] = 
      'Tip: Los títulos efectivos son claros y descriptivos (máximo 60 caracteres recomendados)';
    
    // Hacer el campo summary obligatorio para páginas
    if (isset($form['body']['widget'][0]['summary'])) {
      $form['body']['widget'][0]['summary']['#required'] = TRUE;
      $form['body']['widget'][0]['summary']['#description'] = 
        'Resumen obligatorio para mejorar el SEO de la página';
    }
  }

}