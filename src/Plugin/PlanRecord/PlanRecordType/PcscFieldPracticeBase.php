<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType\FarmPlanRecordType;

/**
 * Case class for PCSC Field Practice plan record types.
 */
abstract class PcscFieldPracticeBase extends FarmPlanRecordType {

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
      'pcsc_practice_standard' => [
        'type' => 'list_string',
        'label' => $this->t('Practice standard'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'NRCS',
          'Other (specify)',
        ]),
        'required' => TRUE,
      ],
      'pcsc_practice_standard_other' => [
        'type' => 'string',
        'label' => $this->t('Other practice standard'),
      ],
      'pcsc_practice_year' => [
        'type' => 'integer',
        'label' => $this->t('Planned practice implementation year'),
        'min' => 2022,
        'max' => 2030,
        'required' => TRUE,
      ],
      'pcsc_practice_extent' => [
        'type' => 'decimal',
        'label' => $this->t('Extent'),
        'min' => 0.01,
        'max' => 100000,
        'required' => TRUE,
      ],
      'pcsc_practice_extent_unit' => [
        'type' => 'list_string',
        'label' => $this->t('Extent unit'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Acres',
          'Head of livestock',
          'Linear feet',
          'Square feet',
          'Other (specify)',
        ]),
        'required' => TRUE,
      ],
      'pcsc_practice_extent_unit_other' => [
        'type' => 'string',
        'label' => $this->t('Other extent unit'),
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
