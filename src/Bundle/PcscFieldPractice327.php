<?php

namespace Drupal\farm_pcsc\Bundle;

/**
 * Provides the PCSC Field Practice 327 bundle class.
 */
class PcscFieldPractice327 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function practiceTypeLabel(): string {
    return '327: Conservation Cover';
  }

  /**
   * {@inheritdoc}
   */
  public function practiceTypeOption(): string {
    return '327, Conservation Cover';
  }

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(int $delta = 1): array {
    $form = parent::buildPracticeForm($delta);
    $form['327_species_category']= [
      '#type' => 'select',
      '#title' => $this->t('Species category'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '327_species_category'),
      '#required' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function buildSupplementalFieldPracticeExport(): array {
    return [
      'Species category (select most common/extensive type if using more than one)' => $this->get('327_species_category')->value,
    ];
  }

}
