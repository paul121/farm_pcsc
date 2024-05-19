<?php

namespace Drupal\farm_pcsc\Bundle;

/**
 * Provides the PCSC Field Practice 345 bundle class.
 */
class PcscFieldPractice345 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function practiceTypeOption(): string {
    return '345, Residue and Tillage Management, Reduced Till';
  }

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(int $delta = 1): array {
    $form = parent::buildPracticeForm($delta);
    $form['345_surface_disturbance'] = [
      '#type' => 'select',
      '#title' => $this->t('Surface disturbance'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '345_surface_disturbance'),
      '#required' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function buildSupplementalFieldPracticeExport(): array {
    return [
      'Surface Disturbance' => $this->get('345_surface_disturbance')->value,
    ];
  }

}
