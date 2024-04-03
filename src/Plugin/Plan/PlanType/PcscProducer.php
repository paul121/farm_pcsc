<?php

namespace Drupal\farm_pcsc\Plugin\Plan\PlanType;

use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;

/**
 * Provides the PCSC Producer plan type.
 *
 * @PlanType(
 *   id = "pcsc_producer",
 *   label = @Translation("PCSC Producer"),
 * )
 */
class PcscProducer extends FarmPlanType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'pcsc_farm_id' => [
        'type' => 'integer',
        'label' => $this->t('USDA Farm ID'),
        'min' => 1,
        'required' => TRUE,
      ],
      'pcsc_state' => [
        'type' => 'list_string',
        'label' => t('State/territory'),
        'allowed_values_function' => 'farm_pcsc_state_field_allowed_values'
      ],
      'pcsc_county' => [
        'type' => 'list_string',
        'label' => t('County'),
        'allowed_values_function' => 'farm_pcsc_county_field_allowed_values'
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
