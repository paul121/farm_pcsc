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
      'pcsc_mmrv_assistance' => [
        'type' => 'list_string',
        'label' => $this->t('MMRV assistance provided'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Yes',
          'No',
          'I don\'t know',
        ]),
      ],
      'pcsc_marketing_assistance' => [
        'type' => 'list_string',
        'label' => $this->t('Marketing assistance provided'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Yes',
          'No',
          'I don\'t know',
        ]),
      ],
      'pcsc_incentive_per_unit' => [
        'type' => 'list_string',
        'label' => $this->t('Incentive per acre or head'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Yes',
          'No',
          'I don\'t know',
        ]),
      ],
      'pcsc_field_commodity_value' => [
        'type' => 'decimal',
        'label' => $this->t('Field commodity value'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 10000000,
      ],
      'pcsc_field_commodity_volume' => [
        'type' => 'decimal',
        'label' => $this->t('Field commodity volume'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 10000000,
      ],
      'pcsc_field_commodity_volume_unit' => [
        'type' => 'list_string',
        'label' => $this->t('Field commodity volume unit'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Bushels',
          'Carcass weight pounds',
          'Gallons',
          'Head',
          'Linear feet',
          'Lightweight pounds',
          'Pounds',
          'Tons',
          'Other (specify)',
        ]),
      ],
      'pcsc_field_commodity_volume_unit_other' => [
        'type' => 'string',
        'label' => $this->t('Other field commodity volume unit'),
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
      'pcsc_ghg_monitoring_1' => [
        'type' => 'list_string',
        'label' => $this->t('Field GHG monitoring 1'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->GhgMonitoringOptions()),
      ],
      'pcsc_ghg_monitoring_2' => [
        'type' => 'list_string',
        'label' => $this->t('Field GHG monitoring 2'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->GhgMonitoringOptions()),
      ],
      'pcsc_ghg_monitoring_3' => [
        'type' => 'list_string',
        'label' => $this->t('Field GHG monitoring 3'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->GhgMonitoringOptions()),
      ],
      'pcsc_ghg_monitoring_other' => [
        'type' => 'string',
        'label' => $this->t('Other field GHG monitoring'),
      ],
      'pcsc_ghg_reporting_1' => [
        'type' => 'list_string',
        'label' => $this->t('Field GHG reporting 1'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->GhgReportingOptions()),
      ],
      'pcsc_ghg_reporting_2' => [
        'type' => 'list_string',
        'label' => $this->t('Field GHG reporting 2'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->GhgReportingOptions()),
      ],
      'pcsc_ghg_reporting_3' => [
        'type' => 'list_string',
        'label' => $this->t('Field GHG reporting 3'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->GhgReportingOptions()),
      ],
      'pcsc_ghg_reporting_other' => [
        'type' => 'string',
        'label' => $this->t('Other field GHG reporting'),
      ],
      'pcsc_ghg_verification_1' => [
        'type' => 'list_string',
        'label' => $this->t('Field GHG verification 1'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->GhgVerificationOptions()),
      ],
      'pcsc_ghg_verification_2' => [
        'type' => 'list_string',
        'label' => $this->t('Field GHG verification 2'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->GhgVerificationOptions()),
      ],
      'pcsc_ghg_verification_3' => [
        'type' => 'list_string',
        'label' => $this->t('Field GHG verification 3'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->GhgVerificationOptions()),
      ],
      'pcsc_ghg_verification_other' => [
        'type' => 'string',
        'label' => $this->t('Other field GHG verification'),
      ],
      'pcsc_ghg_calculations' => [
        'type' => 'list_string',
        'label' => $this->t('Field GHG calculations'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Models',
          'Direct field measurements',
          'Both',
        ]),
      ],
      'pcsc_official_ghg_calculations' => [
        'type' => 'list_string',
        'label' => $this->t('Field official GHG calculations'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Models',
          'Direct field measurements',
        ]),
      ],
      'field_official_ghg_er' => [
        'type' => 'decimal',
        'label' => $this->t('Field official GHG ER'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0.01,
        'max' => 10000000,
      ],
      'pcsc_official_carbon_stock' => [
        'type' => 'decimal',
        'label' => $this->t('Field official carbon stock'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 10000000,
      ],
      'pcsc_official_co2_er' => [
        'type' => 'decimal',
        'label' => $this->t('Field official CO2 ER'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 10000000,
      ],
      'pcsc_official_ch4_er' => [
        'type' => 'decimal',
        'label' => $this->t('Field official CH4 ER'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 10000000,
      ],
      'pcsc_official_n20_er' => [
        'type' => 'decimal',
        'label' => $this->t('Field official N2O ER'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 10000000,
      ],
      'pcsc_offsets' => [
        'type' => 'decimal',
        'label' => $this->t('Field offsets produced'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 10000000,
      ],
      'pcsc_insets' => [
        'type' => 'decimal',
        'label' => $this->t('Field insets produced'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 10000000,
      ],
      'pcsc_other_field_measurement' => [
        'type' => 'list_string',
        'label' => $this->t('Other field measurement'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Yes',
          'No',
          'I don\'t know',
        ]),
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

  /**
   * GHG monitoring options.
   *
   * @return string[]
   *   Returns an array of options.
   */
  protected function GhgMonitoringOptions() {
    return [
      'Drones',
      'Ground-level photos and videos',
      'On-farm inspection',
      'Plot-based sampling (e.g., soil,water)',
      'Producer recods or attestation',
      'Satelllite monitoring or remote sensing',
      'Soil metagenomics',
      'Soil sensors',
      'Water sensors',
      'Other (specify)',
    ];
  }

  /**
   * GHG reporting options.
   *
   * @return string[]
   *   Returns an array of options.
   */
  protected function GhgReportingOptions() {
    return [
      'Automated devices',
      'Email',
      'Mobile app',
      'Paper',
      'Third-party actors',
      'Website',
      'Other (specify)',
    ];
  }

  /**
   * GHG verification options.
   *
   * @return string[]
   *   Returns an array of options.
   */
  protected function GhgVerificationOptions() {
    return [
      'Artificial intelligence',
      'Computer modeling',
      'Grantee audit',
      'Photos',
      'Record audit',
      'Satellite imagery',
      'Site or field visit',
      'Third-party audit',
      'Other (specify)',
    ];
  }

}
