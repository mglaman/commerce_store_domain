<?php

namespace Drupal\commerce_store_domain\Resolvers;

use Drupal\commerce_store\Resolver\StoreResolverInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class StoreDomainResolver implements StoreResolverInterface {

  /**
   * The current request.
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The store storage.
   *
   * @var \Drupal\commerce_store\StoreStorageInterface
   */
  protected $storage;

  /**
   * Constructs a new DefaultStoreResolver object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(RequestStack $request_stack, EntityTypeManagerInterface $entity_type_manager) {
    $this->request = $request_stack->getCurrentRequest();
    $this->storage = $entity_type_manager->getStorage('commerce_store');
  }

  /**
   * {@inheritdoc}
   */
   public function resolve() {
     $current_host = $this->request->getHost();
     $query = $this->storage->getQuery();
     $query->condition('domain', $current_host);
     $store_ids = $query->execute();
     if (!empty($store_ids)) {
       $store_id = reset($store_ids);
       return $this->storage->load($store_id);
     }
   }

}
