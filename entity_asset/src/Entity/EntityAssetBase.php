<?php

namespace Drupal\entity_asset\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\entity_asset\EntityAssetInterface;
use Drupal\Core\Entity\EntityWithPluginCollectionInterface;
use Drupal\Core\Condition\ConditionPluginCollection;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Implements Entity Field API specific enhancements to the Entity class.
 *
 * @ingroup entity_asset
 */
abstract class EntityAssetBase  extends ContentEntityBase  implements EntityWithPluginCollectionInterface{

  /**
   * The conditions collection.
   *
   * @var \Drupal\Core\Condition\ConditionPluginCollection
   */
  protected $conditionsCollection;

  /**
   * The condition plugin manager.
   *
   * @var \Drupal\Core\Executable\ExecutableManagerInterface
   */
  protected $conditionPluginManager;

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections() {
    return ['conditions' => $this->getConditionsCollection()];
  }

  /**
   * {@inheritdoc}
   */
  public function getConditionsCollection() {
    if (!isset($this->conditionsCollection)) {
      $this->conditionsCollection = new ConditionPluginCollection($this->conditionPluginManager(), $this->get('conditions'));
    }
    return $this->conditionsCollection;
  }

  /**
   * Gets the condition plugin manager.
   *
   * @return \Drupal\Core\Executable\ExecutableManagerInterface
   *   The condition plugin manager.
   */
  protected function conditionPluginManager() {
    if (!isset($this->conditionPluginManager)) {
      $this->conditionPluginManager = \Drupal::service('plugin.manager.condition');
    }
    return $this->conditionPluginManager;
  }

  public static function EntityField(){

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the entity.'))
      ->setReadOnly(TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'))
      ->setDescription(t('The label of the asset.'))
      ->setRequired(true)
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue(NULL)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -6,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['type'] = BaseFieldDefinition::create("list_string")
      ->setSetting('allowed_values', ['inline' => t('Inline'), 'external' => t('External')])
      ->setLabel('Asset type')
      ->setRequired(TRUE)
      ->setCardinality(1)
      ->setDisplayOptions('form', array(
        'type' => 'select'
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['external_code'] = BaseFieldDefinition::create('string');
    $fields['preprocess'] = BaseFieldDefinition::create('boolean');


    return $fields;
  }
}