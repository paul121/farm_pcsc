<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_pcsc\Traits\ListStringTrait;

/**
 * Provides the PCSC Field Practice 528 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_528",
 *   label = @Translation("PCSC Field Practice 528"),
 * )
 */
class PcscFieldPractice528 extends PcscFieldPracticeBase {

  use ListStringTrait;

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      '528_grazing_type' => [
        'type' => 'list_string',
        'label' => $this->t('528: Grazing type'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Cell grazing',
          'Deferred rotational',
          'Management intensive',
          'Rest-rotation',
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
