<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_pcsc\Traits\ListStringTrait;

/**
 * Provides the PCSC Field Practice 329 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_329",
 *   label = @Translation("PCSC Field Practice 329"),
 * )
 */
class PcscFieldPractice329 extends PcscFieldPracticeBase {

  use ListStringTrait;

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      '329_surface_disturbance' => [
        'type' => 'list_string',
        'label' => $this->t('329: Surface disturbance'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'None',
          'Seed row only',
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
