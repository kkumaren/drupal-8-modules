<?php

namespace Drupal\entity_asset\Form;


use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\entity_asset\EntityHelper;
use Drupal\entity_asset\EntityAssetConfig;
use Drupal\Core\Session\AccountProxyInterface;


/**
 * Class FormHelper
 * @package Drupal\entity_asset\Form
 */
class FormHelper {
  use StringTranslationTrait;

  /**
   * @var \Drupal\entity_asset\EntityAssetConfig
   */
  protected $config;

  /**
   * @var \Drupal\entity_asset\EntityHelper
   */
  protected $entityHelper;

  /**
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * @var \Drupal\Core\Form\FormState
   */
  protected $formState;

  /**
   * @var string|null
   */
  protected $entityCategory;

  /**
   * @var string
   */
  protected $entityTypeId;

  /**
   * @var string
   */
  protected $bundleName;

  /**
   * @var string
   */
  protected $instanceId;

  protected static $allowedFormOperations = [
    'default',
    'edit',
    'add',
    'register',
  ];
  /**
   * FormHelper constructor.
   * @param \Drupal\entity_asset\EntityAssetConfig
   * @param \Drupal\entity_asset\EntityHelper $entityHelper
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   */
  public function __construct(
    EntityAssetConfig $config,
    EntityHelper $entityHelper,
    AccountProxyInterface $current_user
  ) {
    $this->config = $config;
    $this->entityHelper = $entityHelper;
    $this->currentUser = $current_user;
  }

  /**
   * @param string $entity_category
   * @return $this
   */
  public function setEntityCategory($entity_category) {
    $this->entityCategory = $entity_category;
    return $this;
  }

  /**
   * @return null|string
   */
  public function getEntityCategory() {
    return $this->entityCategory;
  }

  /**
   * @param string $entity_type_id
   * @return $this
   */
  public function setEntityTypeId($entity_type_id) {
    $this->entityTypeId = $entity_type_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getEntityTypeId() {
    return $this->entityTypeId;
  }

  /**
   * @param string $bundle_name
   * @return $this
   */
  public function setBundleName($bundle_name) {
    $this->bundleName = $bundle_name;
    return $this;
  }

  /**
   * @return string
   */
  public function getBundleName() {
    return $this->bundleName;
  }

  /**
   * @param string $instance_id
   * @return $this
   */
  public function setInstanceId($instance_id) {
    $this->instanceId = $instance_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getInstanceId() {
    return $this->instanceId;
  }

  /**
   * @param array $form_fragment
   * @param bool $multiple
   * @return $this
   */
  public function displayEntitySettings(&$form_fragment, $multiple = FALSE) {
    $prefix = $multiple ? $this->getEntityTypeId() . '_' : '';



    if ($this->getEntityCategory() === 'instance') {
      $bundle_settings = $this->config->getBundleSettings($this->getEntityTypeId(), $this->getBundleName());
      $settings = NULL !== $this->getInstanceId()
        ? $this->config->getEntityInstanceSettings($this->getEntityTypeId(), $this->getInstanceId())
        : $bundle_settings;
    }
    else {
      $settings = $this->config->getBundleSettings($this->getEntityTypeId(), $this->getBundleName());
    }
    return $this;
  }

}