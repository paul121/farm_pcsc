<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\entity\BundleFieldDefinition;
use Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType\FarmPlanRecordType;

/**
 * Provides the Field summary plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_summary",
 *   label = @Translation("Field summary"),
 * )
 */
class PcscFieldSummary extends FarmPlanRecordType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    $fields['pcsc_commodity'] = BundleFieldDefinition::create('entity_reference')
      ->setLabel(t('Field Commodity'))
      ->setDescription(t('Relate this field summary with a commodity.'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'plan_record')
      ->setSetting('handler', 'default:plan_record')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'pcsc_commodity' => 'pcsc_commodity',
        ],
        'sort' => [
          'field' => '_none',
        ],
        'auto_create' => FALSE,
        'auto_create_bundle' => '',
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'match_limit' => '10',
          'size' => '60',
          'placeholder' => '',
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'entity_reference_label',
        'settings' => [
          'link' => TRUE,
        ],
      ]);

    $field_info = [
      'pcsc_year' => [
        'type' => 'integer',
        'label' => t('Year'),
        'size' => 'small',
        'min' => 2024,
        'required' => TRUE,
      ],
      'pcsc_quarter' => [
        'type' => 'integer',
        'label' => t('Quarter'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 4,
        'required' => TRUE,
      ],
      'pcsc_practice_complete' => [
        'type' => 'timestamp',
        'label' => $this->t('Date practice complete'),
      ],
      'pcsc_end_date' => [
        'type' => 'timestamp',
        'label' => $this->t('Contract end date'),
      ],
      'pcsc_implementation_cost' => [
        'type' => 'decimal',
        'label' => $this->t('Cost of implementation'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 10000000,
      ],
      'pcsc_implementation_cost_unit' => [
        'type' => 'list_string',
        'label' => $this->t('Cost of implementation unit'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Per acre',
          'Per bushel',
          'Per head',
          'Per linear foot',
          'Per pound',
          'Per ton',
          'Other (specify)',
        ]),
      ],
      'pcsc_implementation_cost_unit_other' => [
        'type' => 'string',
        'label' => $this->t('Other cost unit'),
      ],
      'pcsc_cost_coverage' => [
        'type' => 'integer',
        'label' => $this->t('Cost coverage'),
        'size' => 'tiny',
        'min' => 0,
        'max' => 100,
      ],
      'pcsc_other_field_measurement' => [
        'type' => 'list_string',
        'label' => $this->t('Other field measurement'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Yes',
          'No',
        ]),
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
