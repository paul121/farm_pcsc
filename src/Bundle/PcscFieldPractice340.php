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
  public function buildPracticeForm(int $delta = 1): array {
    $form = parent::buildPracticeForm($delta);
    $form['340_species_category'] = [
      '#type' => 'select',
      '#title' => $this->t('Species category (select most common/extensive type if using more than one)'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '340_species_category'),
      '#required' => TRUE,
    ];
    $form['340_planned_management'] = [
      '#type' => 'select',
      '#title' => $this->t('Cover crop planned management'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '340_planned_management'),
      '#required' => TRUE,
    ];
    $form['340_termination_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Cover crop termination method'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '340_termination_method'),
      '#required' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function buildSupplementalFieldPracticeExport(): array {
    return [
      'Species category (select most common/extensive type # if using more than one)' => $this->get('340_species_category')->value,
      'Cover crop planned management' => $this->get('340_planned_management')->value,
      'Cover crop termination method' => $this->get('340_termination_method')->value,
    ];
  }

}
