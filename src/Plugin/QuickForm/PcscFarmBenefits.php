<?php

namespace Drupal\farm_pcsc\Plugin\QuickForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_pcsc\Traits\ListStringTrait;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickFormElementsTrait;
use Drupal\plan\Entity\PlanInterface;
use Drupal\plan\Entity\PlanRecord;

/**
 * PCSC Farm Summary quick form.
 *
 * @QuickForm(
 *   id = "pcsc_farm_benefit",
 *   label = @Translation("Farm benefit"),
 *   description = @Translation("Record a farm benefit entry."),
 *   helpText = @Translation("Use this form to record a farm benefit entry."),
 *   permissions = {
 *     "enroll producers",
 *   }
 * )
 */
class PcscFarmBenefits extends QuickFormBase {

  use ListStringTrait;
  use QuickFormElementsTrait;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $producers = \Drupal::entityTypeManager()->getStorage('plan')->loadByProperties(['type' => 'pcsc_producer']);
    $producer_options = array_combine(array_keys($producers), array_map(function (PlanInterface $producer) {
      return $producer->label();
    }, $producers));
    $form['plan'] = [
      '#type' => 'select',
      '#title' => $this->t('Producer'),
      '#options' => $producer_options,
      '#required' => TRUE,
    ];

    $form['quarter'] = $this->buildInlineContainer();
    $form['quarter']['pcsc_year'] = [
      '#type' => 'select',
      '#title' => $this->t('Year'),
      '#options' => farm_pcsc_allowed_values_helper([2024, 2025, 2026, 2027, 2028]),
      '#default_value' => date('Y'),
      '#required' => TRUE,
    ];
    $form['quarter']['pcsc_quarter'] = [
      '#type' => 'select',
      '#title' => $this->t('Quarter'),
      '#options' => farm_pcsc_allowed_values_helper([1, 2, 3, 4]),
      '#default_value' => ceil(date('m') / 3),
      '#required' => TRUE,
    ];

    $form['ta'] = $this->buildInlineContainer();
    $form['ta']['pcsc_producer_ta_1'] = [
      '#type' => 'select',
      '#title' => $this->t('Producer TA received 1'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_benefit', 'pcsc_producer_ta_1'),
      '#empty_option' => '',
    ];
    $form['ta']['pcsc_producer_ta_2'] = [
      '#type' => 'select',
      '#title' => $this->t('Producer TA received 2'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_benefit', 'pcsc_producer_ta_2'),
      '#empty_option' => '',
    ];
    $form['ta']['pcsc_producer_ta_3'] = [
      '#type' => 'select',
      '#title' => $this->t('Producer TA received 3'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_benefit', 'pcsc_producer_ta_3'),
      '#empty_option' => '',
    ];
    $form['ta']['pcsc_producer_ta_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other producer TA received'),
    ];

    $form['type'] = $this->buildInlineContainer();
    $form['type']['pcsc_incentive_type_1'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive type 1'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_benefit', 'pcsc_incentive_type_1'),
      '#empty_option' => '',
    ];
    $form['type']['pcsc_incentive_type_2'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive type 2'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_benefit', 'pcsc_incentive_type_2'),
      '#empty_option' => '',
    ];
    $form['type']['pcsc_incentive_type_3'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive type 3'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_benefit', 'pcsc_incentive_type_3'),
      '#empty_option' => '',
    ];
    $form['type']['pcsc_incentive_type_4'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive type 4'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_benefit', 'pcsc_incentive_type_4'),
      '#empty_option' => '',
    ];
    $form['type']['pcsc_incentive_type_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other incentive type'),
    ];

    $form['pcsc_incentive_amount'] = [
      '#type' => 'number',
      '#title' => $this->t('Producer incentive amount'),
      '#min' => 0,
      '#max' => 5000000,
      '#step' => 0.01,
      '#field_prefix' => '$',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Create farm benefit.
    $summary_values = [
      'type' => 'pcsc_farm_benefit',
    ] + $form_state->getValues();
    $benefit = PlanRecord::create($summary_values);
    $benefit->save();

    // Set a message and redirect to the list of fields.
    $entity_url = $benefit->toUrl()->setAbsolute()->toString();
    $this->messenger()->addStatus($this->t('Farm benefit created: <a href=":url">%label</a>', [':url' => $entity_url, '%label' => $benefit->label()]));
    $form_state->setRedirect('entity.plan.canonical', ['plan' => $form_state->getValue('plan')]);
  }

}
