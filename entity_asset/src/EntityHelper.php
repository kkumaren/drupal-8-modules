<?php

namespace Drupal\entity_asset;

use Drupal\Core\Entity\ContentEntityTypeInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;

/**
 * Class EntityHelper
 * @package Drupal\entity_asset
 */
class EntityHelper {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $db;

  /**
   * EntityHelper constructor.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\Core\Database\Connection $database
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, Connection $database) {
    $this->entityTypeManager = $entityTypeManager;
    $this->db = $database;
  }

  /**
   * Gets an entity's bundle name.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @return string
   */
  public function getEntityInstanceBundleName(EntityInterface $entity) {
    return $entity->getEntityTypeId() === 'menu_link_content'
      // Menu fix.
      ? $entity->getMenuName() : $entity->bundle();
  }

  /**
   * Returns objects of entity types where entities assets can be added.
   *
   * @return array
   *   Objects of entity types where entities assets can be added.
   */
  public function getSupportedEntityTypes() {
    $entity_types = $this->entityTypeManager->getDefinitions();

    foreach ($entity_types as $entity_type_id => $entity_type) {
      if (!$entity_type instanceof ContentEntityTypeInterface
        || !method_exists($entity_type, 'getBundleEntityType')
        || !$entity_type->hasLinkTemplate('canonical')) {
        unset($entity_types[$entity_type_id]);
      }
    }
    return $entity_types;
  }

  /**
   * Checks whether an entity type does not provide bundles.
   *
   * @param string $entity_type_id
   * @return bool
   */
  public function entityTypeHasBundle($entity_type_id) {
    if ($entity_type_id === 'menu_link_content') {
      return FALSE;
    }
    $entity_types = $this->entityTypeManager->getDefinitions();
    return empty($entity_types[$entity_type_id]->getBundleEntityType()) ? TRUE : FALSE;
  }

}
