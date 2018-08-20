<?php

namespace Drupal\entity_asset\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the annotation edit forms.
 *
 * @ingroup entity_asset
 */
class EntityAssetJSForm extends EntityAssetContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $type = NULL) {
    /* @var $entity \Drupal\entity_asset\Entity\EntityAssetJS */
    $form = parent::buildForm($form, $form_state);
    $form['inline']['inline_code']['#attributes']['data-ace-mode'] = 'javascript';
    $entity = $this->entity;

    if(!is_null($type)) {
      $entity->setType($type);
    }

    $form['advanced']['scope'] = [
      '#type' => 'select',
      '#title' => $this->t('Scope'),
      '#required' => TRUE,
      '#weight' => 1,
      '#options' => [
        'header' => $this->t('Header'),
        'footer' => $this->t('Footer'),
      ],
      '#description' => $this->t('The scope of the asset.'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $form_state->setRedirect('entity.entity_asset_js.collection');
    $entity = $this->getEntity();
    $entity->save();
  }

}
