<?php

namespace Drupal\farm_pcsc\Bundle;

/**
 * Provides the PCSC Field Practice 484 bundle class.
 */
class PcscFieldPractice484 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function practiceTypeLabel(): string {
    return '484: Mulching';
  }

  /**
   * {@inheritdoc}
   */
  public function practiceTypeOption(): string {
    return '484, Mulching';
  }

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(int $delta = 1): array {
    $form = parent::buildPracticeForm($delta);
    $form['484_mulch_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Mulch type'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '484_mulch_type'),
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

  /**
   * {@inheritdoc}
   */
  public function buildSupplementalFieldPracticeExport(): array {
    return [
      'Mulch Type' => $this->get('484_mulch_type')->value,
      'Mulch cover (percent of field)' => $this->get('484_mulch_cover')->value,
    ];
  }

}
