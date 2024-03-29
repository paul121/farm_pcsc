<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType\FarmPlanRecordType;

/**
 * Provides the PCSC Field Practice 340 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_340",
 *   label = @Translation("PCSC Field Practice 340"),
 * )
 */
class PcscFieldPractice340 extends FarmPlanRecordType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'field' => [
        'type' => 'entity_reference',
        'label' => $this->t('Field'),
        'description' => $this->t('Associates the PCSC Farm plan with a Land asset.'),
        'target_type' => 'asset',
        'target_bundle' => 'land',
        'cardinality' => 1,
        'required' => TRUE,
      ],
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
      ],
      '340_planned_management' => [
        'type' => 'list_string',
        'label' => $this->t('340: Cover crop planned management'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Grazing',
          'Haying',
          'Termination',
        ]),
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
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
