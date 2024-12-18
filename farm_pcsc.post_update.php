<?php

use Drupal\entity\BundleFieldDefinition;

/**
 * @file
 * Update hooks for farm_pcsc.module.
 */

/**
 * Add internal project ID field to producer enrollment.
 */
function farm_pcsc_post_update_add_project_id_field(&$sandbox = NULL) {

  $fields['pcsc_project_id'] = BundleFieldDefinition::create('string')
    ->setRevisionable(TRUE)
    ->setCardinality(1);

  // Install each field definition.
  foreach ($fields as $field_name => $field_definition) {
    \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition(
      $field_name,
      'plan',
      'farm_pcsc',
      $field_definition,
    );
  }
}
