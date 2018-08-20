<?php

namespace Drupal\entity_asset\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Entity form variant for content entity types.
 *
 */
class EntityAssetContentEntityForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $type = NULL) {
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $form['#tree'] = TRUE;
    $form['inline'] = [
      '#type' => 'container',
      '#states' => [
        'visible' => [
          ':input[name="type"]' => ['value' => 'inline'],
        ],
      ],
    ];

    $form['external'] = [
      '#type' => 'container',
      '#states' => [
        'visible' => [
          ':input[name="type"]' => ['value' => 'external'],
        ],
      ],
    ];

    $form['external']['external_code'] = [
      '#type' => 'url',
      '#title' => $this->t('External'),
      '#description' => $this->t('The label of the asset.'),
      '#required' => TRUE,
    ];


    $form['inline']['inline_code'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Code'),
      '#description' => $this->t('The actual code goes in here.'),
      '#rows' => 10,
      '#default_value' => 'hello',
      '#required' => TRUE,
      '#prefix' => '<div>',
      '#suffix' => '<div class="resizable"><div class="ace-editor"></div></div></div>',
    ];

    $form['advanced'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Advanced options'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#tree' => FALSE,
    ];

    $form['advanced']['preprocess'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Preprocess CSS'),
      '#description' => $this->t('If the CSS is preprocessed, and CSS aggregation is enabled, the script file will be aggregated.'),
      '#default_value' => '',//$entity->preprocess,
      '#weight' => 10,
    ];

    $form['#attached']['library'][] = 'entity_asset/ace-editor';

    return $form;
  }
}