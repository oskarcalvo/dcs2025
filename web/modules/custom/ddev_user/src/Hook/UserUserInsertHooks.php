<?php

declare (strict_types = 1);

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

final class UserUserInsertHooks {

  public function __construct(
    private CacheBackendInterface $cache,
    private ConfigFactoryInterface $configFactory,
    private LoggerChannelFactoryInterface $loggerFactory,
    private EntityTypeManagerInterface $entityTypeManager,
  ) {}

  /**
   * Gestiona la configuración automática de nuevos usuarios.
   */
  #[Hook('user_insert')]
  public function configureNewUser(\Drupal\user\UserInterface $user): void {
    $config = $this->configFactory->get('workshop_hooks_d11.settings');
    $logger = $this->loggerFactory->get('workshop_hooks');

    // Asignar rol por defecto basado en el dominio del email
    $email = $user->getEmail();
    $domain = substr(strrchr($email, "@"), 1);

    $trusted_domains = $config->get('trusted_domains') ?? ['ejemplo.com', 'universidad.edu'];
    
    if (in_array($domain, $trusted_domains)) {
      $user->addRole('content_editor');
      $logger->info('Auto-assigned editor role to user @uid from trusted domain @domain', [
        '@uid' => $user->id(),
        '@domain' => $domain,
      ]);
    }

    // Crear perfil de usuario personalizado
    $user_storage = $this->entityTypeManager->getStorage('user');
    if ($user->hasField('field_welcome_message')) {
      $welcome_msg = $config->get('welcome_message') ?? 'Bienvenido al sitio web';
      $user->set('field_welcome_message', $welcome_msg);
    }
  }
  
}
