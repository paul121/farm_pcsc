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
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
