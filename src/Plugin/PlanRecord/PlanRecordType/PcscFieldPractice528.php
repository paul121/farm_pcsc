<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType\FarmPlanRecordType;

/**
 * Provides the PCSC Field Practice 528 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_528",
 *   label = @Translation("PCSC Field Practice 528"),
 * )
 */
class PcscFieldPractice528 extends FarmPlanRecordType {

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
      '528_grazing_type' => [
        'type' => 'list_string',
        'label' => $this->t('528: Grazing type'),
        'allowed_values' => [
          'Cell grazing',
          'Deferred rotational',
          'Management intensive',
          'Rest-rotation',
        ],
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
