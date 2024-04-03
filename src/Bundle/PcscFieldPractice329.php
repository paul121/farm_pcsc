<?php

namespace Drupal\farm_pcsc\Bundle;

/**
 * Provides the PCSC Field Practice 329 bundle class.
 */
class PcscFieldPractice329 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(): array {
    $form = parent::buildPracticeForm();
    $form['329_surface_disturbance'] = [
      '#type' => 'select',
      '#title' => $this->t('Surface disturbance'),
      '#options' => $this->getListOptions('329_surface_disturbance'),
      '#required' => TRUE,
    ];
    return $form;
  }

}
