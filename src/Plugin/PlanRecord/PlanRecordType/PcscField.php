<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType\FarmPlanRecordType;

/**
 * Provides the PCSC Field plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field",
 *   label = @Translation("PCSC Field"),
 * )
 */
class PcscField extends FarmPlanRecordType {

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
      'pcsc_tract_id' => [
        'type' => 'integer',
        'label' => t('USDA Tract ID'),
        'min' => 1,
      ],
      'pcsc_field_id' => [
        'type' => 'integer',
        'label' => t('USDA Field ID'),
        'min' => 1,
      ],
      'pcsc_state' => [
        'type' => 'string',
        'label' => t('State/territory'),
      ],
      'pcsc_county' => [
        'type' => 'list_string',
        'label' => t('County'),
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
