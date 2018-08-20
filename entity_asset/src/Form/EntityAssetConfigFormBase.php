<?php

namespace Drupal\entity_asset\Form;


use Drupal\entity_asset\EntityHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\entity_asset\EntityAssetConfig;
use Drupal\Core\Path\PathValidator;
use Drupal\Core\Language\LanguageManagerInterface;

/**
 * Class EntityAssetConfigFormBase
 * @package Drupal\entity_asset\Form
 */
abstract class EntityAssetConfigFormBase extends ConfigFormBase {

  /**
   * @var \Drupal\entity_asset\EntityAssetConfig
   */
  protected $config;

  /**
   * @var \Drupal\entity_asset\Form\FormHelper
   */
  protected $formHelper;

  /**
   * @var \Drupal\entity_asset\EntityHelper
   */
  protected $entityHelper;

  /**
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * EntityAssetFormBase constructor.
   * @param \Drupal\entity_asset\EntityAssetConfig $config
   * @param \Drupal\entity_asset\Form\FormHelper $form_helper
   * @param \Drupal\entity_asset\EntityHelper $entity_helper
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   */
  public function __construct(
    EntityAssetConfig $config,
    FormHelper $form_helper,
    EntityHelper $entity_helper,
    LanguageManagerInterface $language_manager
  ) {
    $this->config = $config;
    $this->formHelper = $form_helper;
    $this->entityHelper = $entity_helper;
    $this->languageManager = $language_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_asset.config'),
      $container->get('entity_asset.form_helper'),
      $container->get('entity_asset.entity_helper'),
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['entity_asset.settings'];
  }



}
