<?php

/**
 * @file
 * Provides Views data for farm_pcsc.module.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_pcsc_views_data_alter(array &$data) {

  // Provide the practice type label as a views field.
  $data['plan_record']['pcsc_practice_label'] = [
    'title' => t('PCSC Practice Label'),
    'field' => [
      'id' => 'pcsc_practice_label',
      'field_name' => 'id',
      'field' => 'id',
    ],
  ];

  // Provide the field practice labels as a views field.
  $data['plan_record']['pcsc_field_practice_labels'] = [
    'title' => t('PCSC Field Practice Labels'),
    'field' => [
      'id' => 'pcsc_field_practice_labels',
      'field_name' => 'id',
      'field' => 'id',
    ],
  ];

  // Provide the field commodity labels as a views field.
  $data['plan_record']['pcsc_field_commodity_labels'] = [
    'title' => t('PCSC Field Commodity Labels'),
    'field' => [
      'id' => 'pcsc_field_commodity_labels',
      'field_name' => 'id',
      'field' => 'id',
    ],
  ];

}
