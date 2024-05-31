<?php

namespace Drupal\farm_pcsc\Bundle;

/**
 * Provides the PCSC Field Practice 329 bundle class.
 */
class PcscFieldPractice329 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function practiceTypeLabel(): string {
    return '329: Residue and Tillage Management, No Till';
  }

  /**
   * {@inheritdoc}
   */
  public function practiceTypeOption(): string {
    return '329, Residue and Tillage Management, No Till';
  }

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(int $delta = 1): array {
    $form = parent::buildPracticeForm($delta);
    $form['329_surface_disturbance'] = [
      '#type' => 'select',
      '#title' => $this->t('Surface disturbance'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '329_surface_disturbance'),
      '#required' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function buildSupplementalFieldPracticeExport(): array {
    return [
      'Surface Disturbance' => $this->get('329_surface_disturbance')->value,
    ];
  }

}
