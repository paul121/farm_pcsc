<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\entity\BundleFieldDefinition;
use Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType\FarmPlanRecordType;

/**
 * Provides the Farm summary plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_farm_summary",
 *   label = @Translation("Farm summary"),
 * )
 */
class PcscFarmSummary extends FarmPlanRecordType {

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
      'pcsc_farm_commodity_value' => [
        'type' => 'decimal',
        'label' => $this->t('Farm commodity value'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 10000000,
      ],
      'pcsc_farm_commodity_volume' => [
        'type' => 'decimal',
        'label' => $this->t('Farm commodity volume'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 10000000,
      ],
      'pcsc_farm_commodity_volume_unit' => [
        'type' => 'list_string',
        'label' => $this->t('Farm commodity volume unit'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Bunches',
          'Bushels',
          'Carcass weight pounds',
          'Cartons',
          'Eggs',
          'Flats',
          'Gallons',
          'Head',
          'Linear feet',
          'Liveweight pounds',
          'Pints',
          'Pounds',
          'Tons',
          'Trees',
          'Other (specify)',
        ]),
      ],
      'pcsc_farm_commodity_volume_unit_other' => [
        'type' => 'string',
        'label' => $this->t('Other farm commodity volume unit'),
      ],
      'pcsc_ghg_calculations' => [
        'type' => 'list_string',
        'label' => $this->t('Farm GHG calculations'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Models',
          'Direct field measurements',
          'Both',
        ]),
      ],
      'pcsc_official_ghg_calculations' => [
        'type' => 'list_string',
        'label' => $this->t('Farm official GHG calculations'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Models',
          'Direct field measurements',
        ]),
      ],
      'pcsc_official_ghg_er' => [
        'type' => 'decimal',
        'label' => $this->t('Farm official GHG ER'),
        'precision' => 10,
        'scale' => 2,
        'min' => -10000000,
        'max' => 10000000,
      ],
      'pcsc_official_carbon_stock' => [
        'type' => 'decimal',
        'label' => $this->t('Farm official carbon stock'),
        'precision' => 10,
        'scale' => 2,
        'min' => -10000000,
        'max' => 10000000,
      ],
      'pcsc_official_co2_er' => [
        'type' => 'decimal',
        'label' => $this->t('Farm official CO2 ER'),
        'precision' => 10,
        'scale' => 2,
        'min' => -10000000,
        'max' => 10000000,
      ],
      'pcsc_official_ch4_er' => [
        'type' => 'decimal',
        'label' => $this->t('Farm official CH4 ER'),
        'precision' => 10,
        'scale' => 2,
        'min' => -10000000,
        'max' => 10000000,
      ],
      'pcsc_official_n20_er' => [
        'type' => 'decimal',
        'label' => $this->t('Farm official N2O ER'),
        'precision' => 10,
        'scale' => 2,
        'min' => -10000000,
        'max' => 10000000,
      ],
      'pcsc_offsets' => [
        'type' => 'decimal',
        'label' => $this->t('Farm offsets produced'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 10000000,
      ],
      'pcsc_insets' => [
        'type' => 'decimal',
        'label' => $this->t('Farm insets produced'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 10000000,
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
