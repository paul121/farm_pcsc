<?php

namespace Drupal\farm_pcsc\Bundle;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_pcsc\Traits\ListStringTrait;
use Drupal\plan\Entity\PlanRecord;

/**
 * Base class for PCSC Field Practice plan record types.
 */
abstract class PcscFieldPracticeBase extends PlanRecord implements PcscFieldPracticeInterface {

  use StringTranslationTrait;
  use ListStringTrait;

  /**
   * {@inheritdoc}
   */
  public function label() {

    // Build label with the referenced field and practice type.
    if ($field = $this->get('field')->first()?->entity) {
      return $this->t('%practice: %field', ['%practice' => $this->getBundleLabel(), '%field' => $field->label()]);
    }

    // Fallback to default.
    return parent::label();
  }

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(int $delta = 1): array {
    $form['pcsc_practice_standard'] = [
      '#type' => 'select',
      '#title' => $this->t('Practice standard'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), 'pcsc_practice_standard'),
      '#required' => TRUE,
    ];
    $form['pcsc_practice_standard_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other practice standard'),
      '#states' => [
        'visible' => [
          'select[name="practices[' . $delta . '][pcsc_practice_standard]"]' => ['value' => 'Other (specify)'],
        ],
      ],
    ];
    $form['pcsc_practice_year'] = [
      '#type' => 'number',
      '#title' => $this->t('Planned practice implementation year'),
      '#min' => 2022,
      '#max' => 2030,
      '#step' => 1,
      '#required' => TRUE,
    ];
    $form['pcsc_practice_extent'] = [
      '#type' => 'number',
      '#title' => $this->t('Extent'),
      '#min' => 0.01,
      '#max' => 100000,
      '#step' => 0.01,
      '#required' => TRUE,
    ];
    $form['pcsc_practice_extent_unit'] = [
      '#type' => 'select',
      '#title' => $this->t('Extent unit'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), 'pcsc_practice_extent_unit'),
      '#required' => TRUE,
    ];
    $form['pcsc_practice_extent_unit_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other extent unit'),
      '#states' => [
        'visible' => [
          'select[name="practices[' . $delta . '][pcsc_practice_extent_unit]"]' => ['value' => 'Other (specify)'],
        ],
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function buildSupplementalFieldPracticeExport(): array {
    return [];
  }

}
