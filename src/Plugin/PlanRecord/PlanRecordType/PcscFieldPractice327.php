<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_pcsc\Traits\ListStringTrait;

/**
 * Provides the PCSC Field Practice 327 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_327",
 *   label = @Translation("PCSC Field Practice 327"),
 * )
 */
class PcscFieldPractice327 extends PcscFieldPracticeBase {

  use ListStringTrait;

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      '327_species_category' => [
        'type' => 'list_string',
        'label' => $this->t('327: Species category'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Brassicas',
          'Grasses',
          'Legumes',
          'Non-legume broadleaves',
          'Shrubs',
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
