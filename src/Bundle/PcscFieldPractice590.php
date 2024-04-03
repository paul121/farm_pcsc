<?php

namespace Drupal\farm_pcsc\Bundle;

/**
 * Provides the PCSC Field Practice 590 bundle class.
 */
class PcscFieldPractice590 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(int $delta = 1): array {
    $form = parent::buildPracticeForm($delta);
    $form['590_nutrient_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Nutrient type with CPS 590'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '590_nutrient_type'),
      '#required' => TRUE,
    ];
    $form['590_application_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Nutrient application method with CPS 590'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '590_application_method'),
      '#required' => TRUE,
    ];
    $form['590_previous_application_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Nutrient application method in the previous year'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '590_previous_application_method'),
      '#required' => TRUE,
    ];
    $form['590_timing'] = [
      '#type' => 'select',
      '#title' => $this->t('Nutrient application timing with CPS 590'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '590_timing'),
      '#required' => TRUE,
    ];
    $form['590_previous_timing'] = [
      '#type' => 'select',
      '#title' => $this->t('Nutrient application timing in the previous year'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '590_previous_timing'),
      '#required' => TRUE,
    ];
    $form['590_rate'] = [
      '#type' => 'number',
      '#title' => $this->t('Nutrient application rate with CPS 590'),
      '#min' => 1,
      '#max' => 20000,
      '#step' => 1,
      '#required' => TRUE,
    ];
    $form['590_rate_unit'] = [
      '#type' => 'select',
      '#title' => $this->t('Nutrient application rate unit with CPS 590'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '590_rate_unit'),
      '#required' => TRUE,
    ];
    $form['590_rate_change'] = [
      '#type' => 'select',
      '#title' => $this->t('Nutrient application rate change'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '590_rate_change'),
      '#required' => TRUE,
    ];
    return $form;
  }

}
