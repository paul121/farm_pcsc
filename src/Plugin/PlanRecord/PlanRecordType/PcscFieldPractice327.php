<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType\FarmPlanRecordType;

/**
 * Provides the PCSC Field Practice 327 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_327",
 *   label = @Translation("PCSC Field Practice 327"),
 * )
 */
class PcscFieldPractice327 extends FarmPlanRecordType {

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
      '327_species_category' => [
        'type' => 'list_string',
        'label' => $this->t('328: Species category'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Brassicas',
          'Grasses',
          'Legumes',
          'Non-legume broadleaves',
          'Shrubs',
        ]),
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
