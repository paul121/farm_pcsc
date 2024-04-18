<?php

namespace Drupal\farm_pcsc\Bundle;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_pcsc\Traits\ListOptionsTrait;
use Drupal\plan\Entity\PlanRecord;

/**
 * Base class for PCSC Field Practice plan record types.
 */
abstract class PcscFieldPracticeBase extends PlanRecord implements PcscFieldPracticeInterface {

  use StringTranslationTrait;
  use ListOptionsTrait;

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(): array {
    $form['standard'] = [
      '#type' => 'select',
      '#title' => $this->t('Practice standard'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), 'pcsc_practice_standard'),
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
      '#options' => $this->getListOptions('plan_record', $this->bundle(), 'pcsc_practice_extent_unit'),
      '#required' => TRUE,
    ];
    $form['extent_unit_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other extent unit'),
    ];
    return $form;
  }

}
