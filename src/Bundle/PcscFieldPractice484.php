<?php

namespace Drupal\farm_pcsc\Bundle;

/**
 * Provides the PCSC Field Practice 484 bundle class.
 */
class PcscFieldPractice484 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(): array {
    $form = parent::buildPracticeForm();
    $form['484_mulch_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Mulch type'),
      '#options' => $this->getListOptions('484_mulch_type'),
      '#required' => TRUE,
    ];
    $form['484_mulch_cover'] = [
      '#type' => 'number',
      '#title' => $this->t('Mulch cover (percent of field)'),
      '#min' => 1,
      '#max' => 100,
      '#step' => 1,
      '#required' => TRUE,
    ];
    return $form;
  }

}
