<?php

namespace Drupal\farm_pcsc\Plugin\QuickForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_pcsc\Traits\ListStringTrait;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickFormElementsTrait;
use Drupal\plan\Entity\PlanInterface;
use Drupal\plan\Entity\PlanRecord;

/**
 * PCSC Commodity Enrollment quick form.
 *
 * @QuickForm(
 *   id = "pcsc_commodity_enrollment",
 *   label = @Translation("Commodity enrollment"),
 *   description = @Translation("Enroll a commodity for a field in this PCSC project."),
 *   helpText = @Translation("Use this form to enroll a commodity for a field in this PCSC project."),
 *   permissions = {
 *     "enroll fields",
 *   }
 * )
 */
class PcscCommodityEnrollment extends QuickFormBase {

  use ListStringTrait;
  use QuickFormElementsTrait;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['quarter'] = $this->buildInlineContainer();
    $form['quarter']['pcsc_year'] = [
      '#type' => 'select',
      '#title' => $this->t('Enrollment year'),
      '#options' => farm_pcsc_allowed_values_helper([2024, 2025, 2026, 2027, 2028]),
      '#default_value' => date('Y'),
      '#required' => TRUE,
    ];
    $form['quarter']['pcsc_quarter'] = [
      '#type' => 'select',
      '#title' => $this->t('Enrollment quarter'),
      '#options' => farm_pcsc_allowed_values_helper([1, 2, 3, 4]),
      '#default_value' => ceil(date('m') / 3),
      '#required' => TRUE,
    ];

    $producers = \Drupal::entityTypeManager()->getStorage('plan')->loadByProperties(['type' => 'pcsc_producer']);
    $producer_options = array_combine(array_keys($producers), array_map(function (PlanInterface $producer) {
      return $producer->label();
    }, $producers));
    $form['plan'] = [
      '#type' => 'select',
      '#title' => $this->t('Producer'),
      '#options' => $producer_options,
      '#required' => TRUE,
      '#ajax' => [
        'callback' => [$this, 'fieldCallback'],
        'wrapper' => 'pcsc-field-wrapper',
      ],
    ];

    $field_options = [];
    if ($form_state->getValue('plan')) {
      $fields = \Drupal::entityTypeManager()->getStorage('plan_record')->loadByProperties(['type' => 'pcsc_field', 'plan' => $form_state->getValue('plan')]);
      foreach ($fields as $field) {
        $field_options[$field->id()] = $field->label();
      }
    }
    $form['pcsc_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Field'),
      '#options' => $field_options,
      '#required' => TRUE,
      '#wrapper_attributes' => [
        'id' => 'pcsc-field-wrapper',
      ],
    ];

    $form['commodity'] = $this->buildInlineContainer();
    $form['commodity']['pcsc_commodity_category'] = [
      '#type' => 'select',
      '#title' => $this->t('Commodity category'),
      '#options' => farm_pcsc_allowed_values_helper([
        'Crops',
        'Livestock',
        'Trees',
        'Crops and livestock',
        'Crops and trees',
        'Livestock and trees',
        'Crops, livestock and trees',
      ]),
      '#required' => TRUE,
    ];
    $commodity_types = farm_pcsc_commodity_type_options();
    $form['commodity']['pcsc_commodity_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Commodity type'),
      '#options' => farm_pcsc_allowed_values_helper($commodity_types),
      '#required' => TRUE,
    ];

    $form['baseline'] = $this->buildInlineContainer();
    $form['baseline']['pcsc_baseline_yield'] = [
      '#type' => 'number',
      '#title' => $this->t('Baseline yield (production per acre)'),
      '#min' => 0.01,
      '#max' => 100000,
      '#step' => 0.01,
      '#required' => TRUE,
    ];
    $form['baseline']['pcsc_baseline_yield_unit'] = [
      '#type' => 'select',
      '#title' => $this->t('Baseline yield unit'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_commodity', 'pcsc_baseline_yield_unit'),
      '#required' => TRUE,
    ];
    $form['baseline']['pcsc_baseline_yield_unit_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other baseline yield unit'),
      '#states' => [
        'visible' => [
          'select[name="pcsc_baseline_yield_unit"]' => ['value' => 'Other (specify)'],
        ],
      ],
    ];
    $form['baseline']['pcsc_baseline_yield_location'] = [
      '#type' => 'select',
      '#title' => $this->t('Baseline yield location'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_commodity', 'pcsc_baseline_yield_location'),
      '#required' => TRUE,
    ];
    $form['baseline']['pcsc_baseline_yield_location_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other baseline yield location'),
      '#states' => [
        'visible' => [
          'select[name="pcsc_baseline_yield_location"]' => ['value' => 'Other (specify)'],
        ],
      ],
    ];

    return $form;
  }

  /**
   * Ajax callback for field container.
   */
  public function fieldCallback(array $form, FormStateInterface $form_state) {
    return $form['pcsc_field'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Create commodity record.
    $record_values = [
      'type' => 'pcsc_commodity',
    ] + $form_state->getValues();
    $commodity = PlanRecord::create($record_values);
    $commodity->save();

    // Set a message and redirect to the list of fields.
    $this->messenger()->addStatus($this->t('Commodity enrolled: @field', ['@field' => $commodity->label()]));
    $form_state->setRedirect('entity.plan.canonical', ['plan' => $form_state->getValue('plan')]);
  }

}
