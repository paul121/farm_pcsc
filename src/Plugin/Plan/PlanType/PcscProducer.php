<?php

namespace Drupal\farm_pcsc\Plugin\Plan\PlanType;

use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;
use Drupal\farm_pcsc\Traits\ListStringTrait;

/**
 * Provides the PCSC Producer plan type.
 *
 * @PlanType(
 *   id = "pcsc_producer",
 *   label = @Translation("PCSC Producer"),
 * )
 */
class PcscProducer extends FarmPlanType {

  use ListStringTrait;

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
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
      'pcsc_farm_id' => [
        'type' => 'integer',
        'label' => $this->t('FSA Farm ID'),
        'min' => 1,
        'required' => TRUE,
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
        'label' => $this->t('Producer start date'),
        'required' => TRUE,
      ],
      'pcsc_underserved' => [
        'type' => 'list_string',
        'label' => $this->t('Underserved status'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Yes, underserved',
          'Yes, small producer',
          'Yes, underserved and small producer',
          'No',
          'I don\'t know',
        ]),
        'required' => TRUE,
      ],
      'pcsc_organic' => [
        'type' => 'list_string',
        'label' => $this->t('Organic farm'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Yes',
          'No',
          'I don\'t know',
        ]),
        'required' => TRUE,
      ],
      'pcsc_organic_fields' => [
        'type' => 'list_string',
        'label' => $this->t('Organic fields'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Yes',
          'No',
          'I don\'t know',
        ]),
        'required' => TRUE,
      ],
      'pcsc_csaf_experience' => [
        'type' => 'list_string',
        'label' => $this->t('CSAF experience'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Yes',
          'No',
          'I don\'t know',
        ]),
        'required' => TRUE,
      ],
      'pcsc_total_forest_area' => [
        'type' => 'integer',
        'label' => $this->t('Total forest area (acres)'),
        'mix' => 1,
        'max' => 100000,
        'required' => TRUE,
      ],
      'pcsc_total_livestock_area' => [
        'type' => 'integer',
        'label' => $this->t('Total livestock area (acres)'),
        'mix' => 1,
        'max' => 100000,
        'required' => TRUE,
      ],
      'pcsc_livestock_type_1' => [
        'type' => 'list_string',
        'label' => $this->t('Livestock type 1'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->livestockTypes()),
      ],
      'pcsc_livestock_avg_head_1' => [
        'type' => 'integer',
        'label' => $this->t('Livestock head (type 1 avg annual)'),
        'mix' => 1,
        'max' => 10000000,
      ],
      'pcsc_livestock_type_2' => [
        'type' => 'list_string',
        'label' => $this->t('Livestock type 2'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->livestockTypes()),
      ],
      'pcsc_livestock_avg_head_2' => [
        'type' => 'integer',
        'label' => $this->t('Livestock head (type 2 avg annual)'),
        'mix' => 1,
        'max' => 10000000,
      ],
      'pcsc_livestock_type_3' => [
        'type' => 'list_string',
        'label' => $this->t('Livestock type 3'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->livestockTypes()),
      ],
      'pcsc_livestock_avg_head_3' => [
        'type' => 'integer',
        'label' => $this->t('Livestock head (type 3 avg annual)'),
        'mix' => 1,
        'max' => 10000000,
      ],
      'pcsc_livestock_type_other' => [
        'type' => 'string',
        'label' => $this->t('Other livestock type'),
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

  /**
   * Livestock type options.
   *
   * @return string[]
   *   Array of livestock types.
   */
  protected function livestockTypes() {
    return [
      'Alpacas',
      'Beef cows',
      'Beefalo',
      'Buffalo or bison',
      'Chickens (broilers)',
      'Chickens (layers)',
      'Dairy cows',
      'Deer',
      'Ducks',
      'Elk',
      'Emus',
      'Equine',
      'Geese',
      'Goats',
      'Honeybees',
      'Llamas',
      'Reindeer',
      'Sheep',
      'Swine',
      'Turkeys',
      'Other (specify)',
    ];
  }

}
