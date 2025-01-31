<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType\FarmPlanRecordType;
use Drupal\farm_pcsc\Traits\ListStringTrait;

/**
 * Provides the PCSC Field plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field",
 *   label = @Translation("PCSC Field"),
 * )
 */
class PcscField extends FarmPlanRecordType {

  use ListStringTrait;

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
      'pcsc_year' => [
        'type' => 'integer',
        'label' => t('Enrollment year'),
        'size' => 'small',
        'min' => 2024,
        'required' => TRUE,
      ],
      'pcsc_quarter' => [
        'type' => 'integer',
        'label' => t('Enrollment quarter'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 4,
        'required' => TRUE,
      ],
      'pcsc_tract_id' => [
        'type' => 'integer',
        'label' => t('USDA Tract ID'),
        'min' => 1,
        'required' => TRUE,
      ],
      'pcsc_field_id' => [
        'type' => 'integer',
        'label' => t('USDA Field ID'),
        'min' => 1,
        'required' => TRUE,
      ],
      'pcsc_prior_field_id' => [
        'type' => 'integer',
        'label' => t('Prior Field ID (if applicable)'),
        'min' => 1,
      ],
      'pcsc_state' => [
        'type' => 'list_string',
        'label' => t('State/territory'),
        'allowed_values_function' => 'farm_pcsc_state_field_allowed_values',
        'required' => TRUE,
      ],
      'pcsc_county' => [
        'type' => 'list_string',
        'label' => t('County'),
        'allowed_values_function' => 'farm_pcsc_county_field_allowed_values',
        'required' => TRUE,
      ],
      'pcsc_start_date' => [
        'type' => 'timestamp',
        'label' => $this->t('Contract start date'),
        'required' => TRUE,
      ],
      'pcsc_field_total_area' => [
        'type' => 'decimal',
        'label' => t('Total field area (acres)'),
        'precision' => 5,
        'scale' => 2,
        'min' => 0.01,
        'max' => 500,
        'required' => TRUE,
      ],
      'pcsc_land_use' => [
        'type' => 'list_string',
        'label' => $this->t('Field land use'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Crop land',
          'Forest land',
          'Non-agriculture',
          'Other agricultural land',
          'Pasture',
          'Range',
        ]),
        'required' => TRUE,
      ],
      'pcsc_irrigated' => [
        'type' => 'list_string',
        'label' => $this->t('Field irrigated'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'No irrigation',
          'Center pivot',
          'Drip-subsurface',
          'Drip-surface',
          'Flood/Border',
          'Furrow/ditch',
          'Lateral/linear sprinklers',
          'Micro-sprinklers',
          'Seepage',
          'Side roll',
          'Solid set sprinklers',
          'Supplemental',
          'Surface',
          'Traveling gun/towline',
          'Wheel line',
          'Other',
        ]),
        'required' => TRUE,
      ],
      'pcsc_tillage' => [
        'type' => 'list_string',
        'label' => $this->t('Field tillage'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'None',
          'Conventional, inversion',
          'Conventional, vertical',
          'No-till, direct seed',
          'Reduced till,inversion',
          'Reduced till, vertical',
          'Strip till',
          'Other',
        ]),
        'required' => TRUE,
      ],
      'pcsc_farm_past_practice' => [
        'type' => 'list_string',
        'label' => $this->t('Practice (combination) past extent - farm'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Never used',
          'Used on less than 25% of operation',
          'Used on 25-50% of operation',
          'Used on 51-75% of operation',
          'Used on 25-50% of operation',
          'Used on more than 75% of operation',
        ]),
        'required' => TRUE,
      ],
      'pcsc_field_csaf_practice' => [
        'type' => 'list_string',
        'label' => $this->t('Field any CSAF practice'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Yes',
          'No',
          'I don\'t know',
        ]),
        'required' => TRUE,
      ],
      'pcsc_field_past_practice' => [
        'type' => 'list_string',
        'label' => $this->t('Practice (combination) past use - this field'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Yes',
          'Some',
          'No',
          'I don\'t know',
        ]),
        'required' => TRUE,
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }

    // Convert list_string form widgets to select lists (not default radios).
    foreach ($field_info as $name => $info) {
      if ($info['type'] = 'list_string') {
        $this->useSelectWidget($fields[$name]);
      }
    }

    return $fields;
  }

}
