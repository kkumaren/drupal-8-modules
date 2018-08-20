<?php

namespace Drupal\entity_asset\Form;


use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;


/**
 * Form controller for the annotation edit forms.
 *
 * @ingroup entity_asset
 */
class EntityAssetCSSForm extends EntityAssetContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $type = NULL) {
    /* @var $entity \Drupal\entity_asset\Entity\EntityAssetCSS */
    $form = parent::buildForm($form, $form_state);
    $form['inline']['inline_code']['#attributes']['data-ace-mode'] = 'css';
    $entity = $this->entity;

    if(!is_null($type)) {
      $entity->setType($type);
    }
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $form_state->setRedirect('entity.entity_asset_css.collection');
    $entity = $this->getEntity();
    $entity->save();
  }

}
