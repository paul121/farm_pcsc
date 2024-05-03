<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_pcsc\Traits\ListStringTrait;

/**
 * Provides the PCSC Field Practice 328 plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice_328",
 *   label = @Translation("PCSC Field Practice 328"),
 * )
 */
class PcscFieldPractice328 extends PcscFieldPracticeBase {

  use ListStringTrait;

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      '328_crop_type' => [
        'type' => 'list_string',
        'label' => $this->t('328: Conservation crop type'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Brassica',
          'Broadleaf',
          'Cool season',
          'Grass',
          'Legume',
          'Warm season',
        ]),
        'required' => TRUE,
      ],
      '328_change_implemented' => [
        'type' => 'list_string',
        'label' => $this->t('328: Change implemented'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Added perennial crop',
          'Reduced fallow period',
          'Both',
        ]),
        'required' => TRUE,
      ],
      '328_rotation_tillage_type' => [
        'type' => 'list_string',
        'label' => $this->t('328: Conservation crop rotation tillage type'),
        'allowed_values' => farm_pcsc_allowed_values_helper([
          'Conventional (plow, chisel, disk)',
          'No-till, direct seed',
          'Reduced rill',
          'Strip rill',
          'None',
          'Other (specify)',
        ]),
        'required' => TRUE,
      ],
      '328_rotation_tillage_type_other' => [
        'type' => 'string',
        'label' => $this->t('328: Other conservation crop rotation tillage type'),
      ],
      '328_total_rotation_length' => [
        'type' => 'integer',
        'label' => $this->t('328: Total conservation crop rotation length in days'),
        'mix' => 1,
        'max' => 120,
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
