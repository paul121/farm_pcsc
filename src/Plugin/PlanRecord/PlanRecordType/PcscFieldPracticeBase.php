<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\entity\BundleFieldDefinition;
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

    $fields['pcsc_field'] = BundleFieldDefinition::create('entity_reference')
      ->setLabel(t('Field Enrollment'))
      ->setDescription(t('Relate this practice with a field enrollment'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'plan_record')
      ->setSetting('handler', 'default:plan_record')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'pcsc_field' => 'pcsc_field',
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
