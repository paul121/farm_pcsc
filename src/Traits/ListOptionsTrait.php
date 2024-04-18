<?php

namespace Drupal\farm_pcsc\Traits;

/**
 * Provides a getListOptions() method for loading allowed values lists.
 */
trait ListOptionsTrait {

  /**
   * Get allowed values for a given field.
   *
   * @param string $entity_type
   *   The entity type.
   * @param string $bundle
   *   The bundle.
   * @param string $field_name
   *   The field machine name.
   *
   * @return array
   *  Returns an array of list options for the field.
   */
  public function getListOptions(string $entity_type, string $bundle, string $field_name): array {
    /** @var \Drupal\Core\Field\FieldDefinitionInterface[] $field_definitions */
    $field_definitions = \Drupal::service('entity_field.manager')->getFieldDefinitions($entity_type, $bundle);
    if (isset($field_definitions[$field_name])) {
      return $field_definitions[$field_name]->getSetting('allowed_values') ?? [];
    }
    return [];
  }

}
