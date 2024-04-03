<?php

namespace Drupal\farm_pcsc\Bundle;

/**
 * Provides the PCSC Field Practice 327 bundle class.
 */
class PcscFieldPractice327 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(): array {
    $form = parent::buildPracticeForm();
    $form['327_species_category']= [
      '#type' => 'select',
      '#title' => $this->t('Species category'),
      '#options' => $this->getListOptions('327_species_category'),
      '#required' => TRUE,
    ];
    return $form;
  }

}
