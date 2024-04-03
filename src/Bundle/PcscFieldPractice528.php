<?php

namespace Drupal\farm_pcsc\Bundle;

/**
 * Provides the PCSC Field Practice 528 bundle class.
 */
class PcscFieldPractice528 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(int $delta = 1): array {
    $form = parent::buildPracticeForm($delta);
    $form['528_grazing_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Grazing type'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '528_grazing_type'),
      '#required' => TRUE,
    ];
    return $form;
  }

}
