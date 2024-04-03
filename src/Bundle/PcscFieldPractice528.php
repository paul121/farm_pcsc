<?php

namespace Drupal\farm_pcsc\Bundle;

/**
 * Provides the PCSC Field Practice 528 bundle class.
 */
class PcscFieldPractice528 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(): array {
    $form = parent::buildPracticeForm();
    $form['528_grazing_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Grazing type'),
      '#options' => $this->getListOptions('528_grazing_type'),
      '#required' => TRUE,
    ];
    return $form;
  }

}
