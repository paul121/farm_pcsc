<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_pcsc\Traits\ListStringTrait;

/**
 * Provides the PCSC Field Practice 340 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_340",
 *   label = @Translation("PCSC Field Practice 340"),
 * )
 */
class PcscFieldPractice340 extends PcscFieldPracticeBase {

  use ListStringTrait;

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      '340_species_category' => [
        'type' => 'list_string',
        'label' => $this->t('340: Species category (select most common/extensive type if using more than one)'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Brassicas',
          'Forbs',
          'Grasses',
          'Legume',
          'Non-legume broadleaves',
        ]),
        'required' => TRUE,
      ],
      '340_planned_management' => [
        'type' => 'list_string',
        'label' => $this->t('340: Cover crop planned management'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Grazing',
          'Haying',
          'Termination',
        ]),
        'required' => TRUE,
      ],
      '340_termination_method' => [
        'type' => 'list_string',
        'label' => $this->t('340: Cover crop termination method'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Burning',
          'Herbicide application',
          'Incorporation',
          'Mowing',
          'Rolling/crimping',
          'Winter kill/frost',
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
