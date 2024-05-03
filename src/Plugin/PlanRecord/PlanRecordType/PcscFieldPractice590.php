<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_pcsc\Traits\ListStringTrait;

/**
 * Provides the PCSC Field Practice 590 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_590",
 *   label = @Translation("PCSC Field Practice 590"),
 * )
 */
class PcscFieldPractice590 extends PcscFieldPracticeBase {

  use ListStringTrait;

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      '590_nutrient_type' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient type with CPS 590'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Biosolids',
          'Commercial fertilizers',
          'Compost',
          'EEF (nitrification inhibitor)',
          'EEF (slow or controlled release)',
          'EEF (urease inhibitor)',
          'Green manure',
          'Liquid animal manure',
          'Organic by-products',
          'Organic residues or materials',
          'Solid/semi-solid animal manure',
          'Wastewater',
        ]),
        'required' => TRUE,
      ],
      '590_application_method' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient application method with CPS 590'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Banded',
          'Broadcast',
          'Injection',
          'Irrigation',
          'Surface application',
          'Surface application with tillage',
          'Variable rate',
        ]),
        'required' => TRUE,
      ],
      '590_previous_application_method' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient application method in the previous year'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Banded',
          'Broadcast',
          'Injection',
          'Irrigation',
          'Surface application',
          'Surface application with tillage',
          'Variable rate',
        ]),
        'required' => TRUE,
      ],
      '590_timing' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient application timing with CPS 590'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Single pre-planting',
          'Single post-planting',
          'Split pre- and post-planting',
          'Split post planting',
        ]),
        'required' => TRUE,
      ],
      '590_previous_timing' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient application timing in the previous year'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Single pre-planting',
          'Single post-planting',
          'Split pre- and post-planting',
          'Split post planting',
        ]),
        'required' => TRUE,
      ],
      '590_rate' => [
        'type' => 'integer',
        'label' => $this->t('590: Nutrient application rate with CPS 590'),
        'mix' => 1,
        'max' => 20000,
        'required' => TRUE,
      ],
      '590_rate_unit' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient application rate unit with CPS 590'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Gallons per acre',
          'Pounds per acre',
        ]),
        'required' => TRUE,
      ],
      '590_rate_change' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient application rate change'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Decrease compared to previous year',
          'Increase compared to previous year',
          'No change',
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
