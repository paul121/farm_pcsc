<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType\FarmPlanRecordType;

/**
 * Provides the PCSC Field Practice 484 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_484",
 *   label = @Translation("PCSC Field Practice 484"),
 * )
 */
class PcscFieldPractice484 extends FarmPlanRecordType {

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
      '484_rotation_tillage_type' => [
        'type' => 'list_string',
        'label' => $this->t('484: Mulch type'),
        'allowed_values' => [
          'Gravel',
          'Natural',
          'Synthetic',
          'Wood',
        ],
      ],
      '484_total_rotation_length' => [
        'type' => 'integer',
        'label' => $this->t('484: Mulch cover (percent of field)'),
        'mix' => 1,
        'max' => 100,
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
