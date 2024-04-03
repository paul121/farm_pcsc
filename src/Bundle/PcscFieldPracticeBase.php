<?php

namespace Drupal\farm_pcsc\Bundle;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\plan\Entity\PlanRecord;

/**
 * Base class for PCSC Field Practice plan record types.
 */
abstract class PcscFieldPracticeBase extends PlanRecord implements PcscFieldPracticeInterface {

  use StringTranslationTrait;

  /**
   * Get allowed values for a given field.
   *
   * @param string $field_name
   *   The field machine name.
   *
   * @return array
   *  Returns an array of list options for the field.
   */
  public function getListOptions(string $field_name): array {
    /** @var \Drupal\Core\Field\FieldDefinitionInterface[] $field_definitions */
    $field_definitions = \Drupal::service('entity_field.manager')->getFieldDefinitions($this->entityTypeId, $this->bundle());
    if (isset($field_definitions[$field_name])) {
      return $field_definitions[$field_name]->getSetting('allowed_values') ?? [];
    }
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(): array {
    $form['standard'] = [
      '#type' => 'select',
      '#title' => $this->t('Practice standard'),
      '#options' => $this->getListOptions('pcsc_practice_standard'),
      '#required' => TRUE,
    ];
    $form['standard_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other practice standard'),
    ];
    $form['year'] = [
      '#type' => 'number',
      '#title' => $this->t('Planned practice implementation year'),
      '#min' => 2022,
      '#max' => 2030,
      '#step' => 1,
      '#required' => TRUE,
    ];
    $form['extent'] = [
      '#type' => 'number',
      '#title' => $this->t('Extent'),
      '#min' => 0.01,
      '#max' => 100000,
      '#step' => 0.01,
      '#required' => TRUE,
    ];
    $form['extent_unit'] = [
      '#type' => 'select',
      '#title' => $this->t('Extent unit'),
      '#options' => $this->getListOptions('pcsc_practice_extent_unit'),
      '#required' => TRUE,
    ];
    $form['extent_unit_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other extent unit'),
    ];
    return $form;
  }

}
