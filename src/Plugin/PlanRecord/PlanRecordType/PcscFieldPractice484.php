<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

/**
 * Provides the PCSC Field Practice 484 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_484",
 *   label = @Translation("PCSC Field Practice 484"),
 * )
 */
class PcscFieldPractice484 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      '484_rotation_tillage_type' => [
        'type' => 'list_string',
        'label' => $this->t('484: Mulch type'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Gravel',
          'Natural',
          'Synthetic',
          'Wood',
        ]),
      ],
      '484_total_rotation_length' => [
        'type' => 'integer',
        'label' => $this->t('484: Mulch cover (percent of field)'),
        'mix' => 1,
        'max' => 100,
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
