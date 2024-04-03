<?php

namespace Drupal\farm_pcsc\Bundle;

/**
 * Provides the PCSC Field Practice 340 bundle class.
 * )
 */
class PcscFieldPractice340 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(): array {
    $form = parent::buildPracticeForm();
    $form['340_species_category'] = [
      '#type' => 'select',
      '#title' => $this->t('Species category (select most common/extensive type if using more than one)'),
      '#options' => $this->getListOptions('340_species_category'),
      '#required' => TRUE,
    ];
    $form['340_planned_management'] = [
      '#type' => 'select',
      '#title' => $this->t('Cover crop planned management'),
      '#options' => $this->getListOptions('340_planned_management'),
      '#required' => TRUE,
    ];
    $form['340_termination_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Cover crop termination method'),
      '#options' => $this->getListOptions('340_termination_method'),
      '#required' => TRUE,
    ];
    return $form;
  }

}
