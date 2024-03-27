<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType\FarmPlanRecordType;

/**
 * Provides the PCSC Field Practice 345 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_345",
 *   label = @Translation("PCSC Field Practice 345"),
 * )
 */
class PcscFieldPractice345 extends FarmPlanRecordType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'field' => [
        'type' => 'entity_reference',
        'label' => $this->t('Field'),
        'description' => $this->t('Associates the PCSC Farm plan with a Land asset.'),
        'target_type' => 'asset',
        'target_bundle' => 'land',
        'cardinality' => 1,
        'required' => TRUE,
      ],
      '345_surface_disturbance' => [
        'type' => 'list_string',
        'label' => $this->t('345: Surface disturbance'),
        'allowed_values' => [
          'None',
          'Seed row/ridge tillage for planting',
          'Shallow across most of the soil surface',
          'Vertical/mulch',
        ],
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
