<?php

namespace Drupal\entity_asset;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ConfigFactory;


/**
 * Class EntityAssetConfig
 * @package Drupal\entity_asset
 */
class EntityAssetConfig {

  /**
   * @var \Drupal\entity_asset\EntityHelper
   */
  protected $entityHelper;

  /**
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $db;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;


  /**
   * EntityAsset constructor.
   * @param \Drupal\entity_asset\EntityHelper $entityHelper
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   * @param \Drupal\Core\Database\Connection $database
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(
    EntityHelper $entityHelper,
    ConfigFactory $configFactory,
    Connection $database,
    EntityTypeManagerInterface $entityTypeManager
  ) {
    $this->entityHelper = $entityHelper;
    $this->configFactory = $configFactory;
    $this->db = $database;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Checks if an entity type is enabled in the entity asset settings.
   *
   * @param string $entity_type_id
   *
   * @param string $asset_type
   *   The asset type (css, js)
   *
   * @return bool
   */
  public function entityTypeIsEnabled($entity_type_id, $asset_type) {
    return in_array($entity_type_id, $this->getSetting('enabled_entity_' . $asset_type, []));
  }

  /**
   * Returns a specific page asset setting or a default value if setting does not
   * exist.
   *
   * @param string $name
   *   Name of the setting, like 'max_links'.
   *
   * @param mixed $default
   *   Value to be returned if the setting does not exist in the configuration.
   *
   * @return mixed
   *   The current setting from configuration or a default value.
   */
  public function getSetting($name, $default = FALSE) {
    $setting = $this->configFactory
      ->get(ENTITY_ASSET_CONFIG_VAR . '.settings')
      ->get($name);
    return NULL !== $setting ? $setting : $default;
  }

  /**
   * Stores a specific entity asset setting in configuration.
   *
   * @param string $name
   *   Setting name, like 'max_links'.
   * @param mixed $setting
   *   The setting to be saved.
   *
   * @return $this
   */
  public function saveSetting($name, $setting) {
    $this->configFactory->getEditable(ENTITY_ASSET_CONFIG_VAR . '.settings')
      ->set($name, $setting)->save();
    return $this;
  }

  /**
   * Gets entity asset settings for an entity bundle, a non-bundle entity type or for
   * all entity types and their bundles.
   *
   * @param string|null $entity_type_id
   *  If set to null, entity asset settings for all entity types and their bundles
   *  are fetched.
   * @param string|null $bundle_name
   *
   * @return array|false
   *  Array of entity asset settings for an entity bundle, a non-bundle entity type
   *  or for all entity types and their bundles.
   *  False if entity type does not exist.
   */
  public function getBundleSettings($entity_type_id = NULL, $bundle_name = NULL) {
    if (NULL !== $entity_type_id) {
      $bundle_name = empty($bundle_name) ? $entity_type_id : $bundle_name;
      $bundle_settings = $this->configFactory
        ->get(ENTITY_ASSET_CONFIG_VAR . ".bundle_settings.$entity_type_id.$bundle_name")
        ->get();
      return !empty($bundle_settings) ? $bundle_settings : FALSE;
    }
    else {
      $config_names = $this->configFactory->listAll(ENTITY_ASSET_CONFIG_VAR . '.bundle_settings.');
      $all_settings = [];
      foreach ($config_names as $config_name) {
        $config_name_parts = explode('.', $config_name);
        $all_settings[$config_name_parts[2]][$config_name_parts[3]] = $this->configFactory->get($config_name)->get();
      }
      return $all_settings;
    }
  }

  /**
   * Gets entity asset settings for an entity instance which overrides the entity asset
   * settings of its bundle, or bundle settings, if they are not overridden.
   *
   * @param string $entity_type_id
   * @param int $id
   *
   * @return array|false
   */
  public function getEntityInstanceSettings($entity_type_id, $id) {
    /*$results = $this->db->select('entity_asset_entities', 'o')
      ->fields('o', ['inclusion_settings'])
      ->condition('o.entity_type', $entity_type_id)
      ->condition('o.entity_id', $id)
      ->execute()
      ->fetchField();

    if (!empty($results)) {
      return unserialize($results);
    }
    else {*/
      $entity = $this->entityTypeManager->getStorage($entity_type_id)
        ->load($id);
      return $this->getBundleSettings(
        $entity_type_id,
        $this->entityHelper->getEntityInstanceBundleName($entity)
      );
    //}
  }



}
