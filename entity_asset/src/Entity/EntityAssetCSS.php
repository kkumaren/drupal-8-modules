<?php

namespace Drupal\entity_asset\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\entity_asset\EntityAssetInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Entity\EntityChangedTrait;


/**
 * Defines the EntityAssetCSS entity.
 *
 * @ingroup entity_asset
 *
 * @ContentEntityType(
 *   id = "entity_asset_css",
 *   label = @Translation("Entity Asset CSS"),
 *   handlers = {
 *     "list_builder" = "Drupal\entity_asset\Controller\EntityAssetCSSListBuilder",
 *     "form" = {
 *       "add" = "Drupal\entity_asset\Form\EntityAssetCSSForm",
 *       "edit" = "Drupal\entity_asset\Form\EntityAssetCSSForm",
 *       "delete" = "Drupal\entity_asset\Form\EntityAssetCSSDeleteForm",
 *     },
 *     "access" = "Drupal\entity_asset\EntityAssetAccessControlHandler",
 *   },
 *   list_cache_contexts = { "user" },
 *   base_table = "entity_asset_css",
 *   admin_permission = "administer entity asset entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "edit-form" = "/admin/content/entity-asset/css/{entity_asset_css}/edit",
 *     "delete-form" = "/admin/content/entity-asset/css/{entity_asset_css}/delete",
 *     "collection" = "/admin/content/entity-asset/css"
 *   },
 *   field_ui_base_route = "entity_asset.entity_asset_css_settings",
 * )
 */
class EntityAssetCSS extends EntityAssetBase implements EntityAssetInterface
{

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   *
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values)
  {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime()
  {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTime()
  {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner()
  {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId()
  {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid)
  {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account)
  {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {
    $fields = parent::EntityField();

    return $fields;
  }

}