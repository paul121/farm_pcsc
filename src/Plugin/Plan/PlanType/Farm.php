<?php

namespace Drupal\farm_pcsc\Plugin\Plan\PlanType;

use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;

/**
 * Provides the PCSC Farm plan type.
 *
 * @PlanType(
 *   id = "pcsc_farm",
 *   label = @Translation("PCSC Farm"),
 * )
 */
class Farm extends FarmPlanType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'farm_id' => [
        'type' => 'integer',
        'label' => $this->t('USDA Farm ID'),
        'min' => 1,
        'required' => TRUE,
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
