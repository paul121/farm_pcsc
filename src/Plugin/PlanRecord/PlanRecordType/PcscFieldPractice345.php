<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_pcsc\Traits\ListStringTrait;

/**
 * Provides the PCSC Field Practice 345 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_345",
 *   label = @Translation("PCSC Field Practice 345"),
 * )
 */
class PcscFieldPractice345 extends PcscFieldPracticeBase {

  use ListStringTrait;

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      '345_surface_disturbance' => [
        'type' => 'list_string',
        'label' => $this->t('345: Surface disturbance'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'None',
          'Seed row/ridge tillage for planting',
          'Shallow across most of the soil surface',
          'Vertical/mulch',
        ]),
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
