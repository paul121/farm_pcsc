<?php

/**
 * @file
 * Provides Views data for farm_pcsc.module.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_pcsc_views_data_alter(array &$data) {

  // Make the practice type label as a views field.
  $data['plan_record']['pcsc_practice_label'] = [
    'title' => t('PCSC Practice Label'),
    'field' => [
      'id' => 'pcsc_practice_label',
      'field_name' => 'id',
      'field' => 'id',
    ],
  ];
}
