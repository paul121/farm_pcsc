<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType\FarmPlanRecordType;
use Drupal\farm_pcsc\Traits\ListStringTrait;

/**
 * Provides the PCSC Commodity plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_commodity",
 *   label = @Translation("PCSC Commodity"),
 * )
 */
class PcscCommodity extends FarmPlanRecordType {

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
      'pcsc_commodity_category' => [
        'type' => 'list_string',
        'label' => $this->t('Commodity category'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Crops',
          'Livestock',
          'Trees',
          'Crops and livestock',
          'Crops and trees',
          'Livestock and trees',
          'Crops, livestock and trees',
        ]),
        'required' => TRUE,
      ],
      'pcsc_commodity_type' => [
        'type' => 'list_string',
        'label' => $this->t('Commodity type'),
        'allowed_values' => farm_pcsc_allowed_values_helper(farm_pcsc_commodity_type_options()),
        'required' => TRUE,
      ],
      'pcsc_baseline_yield' => [
        'type' => 'decimal',
        'label' => t('Baseline yield (production per acre)'),
        'precision' => 8,
        'scale' => 2,
        'min' => 0.01,
        'max' => 100000,
        'required' => TRUE,
      ],
      'pcsc_baseline_yield_unit' => [
        'type' => 'list_string',
        'label' => $this->t('Baseline yield unit'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Animal units per acre',
          'Bushels per acre',
          'Carcass pounds per animal',
          'Head per acre',
          'Hundred-weights (or pounds) per head',
          'Linear feet per acre',
          'Liveweight pounds per animal',
          'Pounds per acre',
          'Tons per acre',
          'Other (specify)',
        ]),
        'required' => TRUE,
      ],
      'pcsc_baseline_yield_unit_other' => [
        'type' => 'string',
        'label' => $this->t('Other baseline yield unit'),
      ],
      'pcsc_baseline_yield_location' => [
        'type' => 'list_string',
        'label' => $this->t('Baseline yield location'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Enrolled field',
          'Whole operation',
          'Other (specify)',
        ]),
        'required' => TRUE,
      ],
      'pcsc_baseline_yield_location_other' => [
        'type' => 'string',
        'label' => $this->t('Other baseline yield location'),
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
