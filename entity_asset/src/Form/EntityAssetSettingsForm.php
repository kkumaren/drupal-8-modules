<?php

namespace Drupal\entity_asset\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Class EntityAssetSettingsForm
 * @package Drupal\entity_asset\Form
 */
class EntityAssetSettingsForm extends EntityAssetConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'entity_asset_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['entity_asset_entities_title'] = [
      '#markup' => '<p>' . $this->t('Entities to which assets can be added.') . '</p>',
    ];
    $form['entity_asset_entities'] = [
      '#type' => 'vertical_tabs',
    ];

    $entity_type_labels = [];
    foreach ($this->entityHelper->getSupportedEntityTypes() as $entity_type_id => $entity_type) {
      $entity_type_labels[$entity_type_id] = $entity_type->getLabel() ? : $entity_type_id;
    }
    asort($entity_type_labels);

    foreach ($entity_type_labels as $entity_type_id => $entity_type_label) {
      $enabled_entity_type = $this->config->entityTypeIsEnabled($entity_type_id, 'css');
      $entity_type_has_bundle = $this->entityHelper->entityTypeHasBundle($entity_type_id);

      $form[$entity_type_id] = [
        '#type' => 'details',
        '#title' => $entity_type_label,
        '#description' => '',
        '#group' => 'entity_asset_entities',
      ];

      $form[$entity_type_id][$entity_type_id . '_enabled'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Enable Entity Asset for @entity_type_label <em>(@entity_type_id)</em>', ['@entity_type_label' => strtolower($entity_type_label), '@entity_type_id' => $entity_type_id]),
        '#description' => '',
        '#default_value' => $enabled_entity_type,
      ];

      if(!$entity_type_has_bundle){
        $this->formHelper->setEntityCategory('bundle')
          ->setEntityTypeId($entity_type_id)
          ->setBundleName($entity_type_id)
          ->displayEntitySettings($form['entity_asset_entities']['entities'][$entity_type_id][$entity_type_id . '_settings'], TRUE);
      }
    }

    //drupal_set_message(t('<pre>'.print_r($entity_type_labels, 1).'</pre>'));



    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {



  }

}
