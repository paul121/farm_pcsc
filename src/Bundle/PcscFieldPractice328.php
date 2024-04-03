<?php

namespace Drupal\farm_pcsc\Bundle;

/**
 * Provides the PCSC Field Practice 328 bundle class.
 */
class PcscFieldPractice328 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(int $delta = 1): array {
    $form = parent::buildPracticeForm($delta);
    $form['328_crop_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Conservation crop type'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '328_crop_type'),
      '#required' => TRUE,
    ];
    $form['328_change_implemented'] = [
      '#type' => 'select',
      '#title' => $this->t('Change implemented'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '328_change_implemented'),
      '#required' => TRUE,
    ];
    $form['328_rotation_tillage_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Conservation crop rotation tillage type'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '328_rotation_tillage_type'),
      '#required' => TRUE,
    ];
    $form['328_total_rotation_length'] = [
      '#type' => 'number',
      '#title' => $this->t('Total conservation crop rotation length in days'),
      '#mix' => 1,
      '#max' => 120,
      '#step' => 1,
      '#required' => TRUE,
    ];
    return $form;
  }

}
