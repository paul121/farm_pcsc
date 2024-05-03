<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_pcsc\Traits\ListStringTrait;

/**
 * Provides the PCSC Field Practice 484 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_484",
 *   label = @Translation("PCSC Field Practice 484"),
 * )
 */
class PcscFieldPractice484 extends PcscFieldPracticeBase {

  use ListStringTrait;

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      '484_mulch_type' => [
        'type' => 'list_string',
        'label' => $this->t('484: Mulch type'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Gravel',
          'Natural',
          'Synthetic',
          'Wood',
        ]),
        'required' => TRUE,
      ],
      '484_mulch_cover' => [
        'type' => 'integer',
        'label' => $this->t('484: Mulch cover (percent of field)'),
        'mix' => 1,
        'max' => 100,
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
