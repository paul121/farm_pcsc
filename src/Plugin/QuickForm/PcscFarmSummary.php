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
 *   id = "pcsc_farm_summary",
 *   label = @Translation("Farm summary"),
 *   description = @Translation("Record a farm summary entry."),
 *   helpText = @Translation("Use this form to record a farm summary entry."),
 *   permissions = {
 *     "enroll producers",
 *   }
 * )
 */
class PcscFarmSummary extends QuickFormBase {

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

    $form['enrollment'] = $this->buildInlineContainer();
    $form['enrollment']['pcsc_year'] = [
      '#type' => 'select',
      '#title' => $this->t('Year'),
      '#options' => farm_pcsc_allowed_values_helper([2024, 2025, 2026, 2027, 2028]),
      '#default_value' => date('Y'),
      '#required' => TRUE,
    ];
    $form['enrollment']['pcsc_quarter'] = [
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
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_producer_ta_1'),
      '#empty_option' => '',
    ];
    $form['ta']['pcsc_producer_ta_2'] = [
      '#type' => 'select',
      '#title' => $this->t('Producer TA received 2'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_producer_ta_2'),
      '#empty_option' => '',
    ];
    $form['ta']['pcsc_producer_ta_3'] = [
      '#type' => 'select',
      '#title' => $this->t('Producer TA received 3'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_producer_ta_3'),
      '#empty_option' => '',
    ];
    $form['ta']['pcsc_producer_ta_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other producer TA received'),
    ];

    $form['pcsc_incentive_amount'] = [
      '#type' => 'number',
      '#title' => $this->t('Producer incentive amount'),
      '#min' => 0,
      '#max' => 5000000,
      '#step' => 0.01,
    ];

    $form['reason'] = $this->buildInlineContainer();
    $form['reason']['pcsc_incentive_reason_1'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive reason 1'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_incentive_reason_1'),
      '#empty_option' => '',
    ];
    $form['reason']['pcsc_incentive_reason_2'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive reason 2'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_incentive_reason_2'),
      '#empty_option' => '',
    ];
    $form['reason']['pcsc_incentive_reason_3'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive reason 3'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_incentive_reason_3'),
      '#empty_option' => '',
    ];
    $form['reason']['pcsc_incentive_reason_4'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive reason 4'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_incentive_reason_4'),
      '#empty_option' => '',
    ];
    $form['reason']['pcsc_incentive_reason_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other incentive reason'),
    ];

    $form['structure'] = $this->buildInlineContainer();
    $form['structure']['pcsc_incentive_structure_1'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive structure 1'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_incentive_structure_1'),
      '#empty_option' => '',
    ];
    $form['structure']['pcsc_incentive_structure_2'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive structure 2'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_incentive_structure_2'),
      '#empty_option' => '',
    ];
    $form['structure']['pcsc_incentive_structure_3'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive structure 3'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_incentive_structure_3'),
      '#empty_option' => '',
    ];
    $form['structure']['pcsc_incentive_structure_4'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive structure 4'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_incentive_structure_4'),
      '#empty_option' => '',
    ];
    $form['structure']['pcsc_incentive_structure_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other incentive structure'),
    ];

    $form['type'] = $this->buildInlineContainer();
    $form['type']['pcsc_incentive_type_1'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive type 1'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_incentive_type_1'),
      '#empty_option' => '',
    ];
    $form['type']['pcsc_incentive_type_2'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive type 2'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_incentive_type_2'),
      '#empty_option' => '',
    ];
    $form['type']['pcsc_incentive_type_3'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive type 3'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_incentive_type_3'),
      '#empty_option' => '',
    ];
    $form['type']['pcsc_incentive_type_4'] = [
      '#type' => 'select',
      '#title' => $this->t('Incentive type 4'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_incentive_type_4'),
      '#empty_option' => '',
    ];
    $form['type']['pcsc_incentive_type_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other incentive type'),
    ];

    $form['payment'] = $this->buildInlineContainer();
    $form['payment']['pcsc_payment_enrollment'] = [
      '#type' => 'select',
      '#title' => $this->t('Payment on enrollment'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_payment_enrollment'),
      '#empty_option' => '',
    ];
    $form['payment']['pcsc_payment_implementation'] = [
      '#type' => 'select',
      '#title' => $this->t('Payment on implementation'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_payment_implementation'),
      '#empty_option' => '',
    ];
    $form['payment']['pcsc_payment_harvest'] = [
      '#type' => 'select',
      '#title' => $this->t('Payment on harvest'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_payment_harvest'),
      '#empty_option' => '',
    ];
    $form['payment']['pcsc_payment_mmrv'] = [
      '#type' => 'select',
      '#title' => $this->t('Payment on MMRV'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_payment_mmrv'),
      '#empty_option' => '',
    ];
    $form['payment']['pcsc_payment_sale'] = [
      '#type' => 'select',
      '#title' => $this->t('Payment on sale'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_payment_sale'),
      '#empty_option' => '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addWarning($this->t('Not implemented'));

    // Create farm summary.
    $summary_values = [
      'type' => 'pcsc_farm_summary'
    ] + $form_state->getValues();
    $summary = PlanRecord::create($summary_values);
    $summary->save();

    // Set a message and redirect to the list of fields.
    $entity_url = $summary->toUrl()->setAbsolute()->toString();
    $this->messenger()->addStatus($this->t('Farm summary created: <a href=":url">%label</a>', [':url' => $entity_url, '%label' => $summary->label()]));
    $form_state->setRedirect('entity.plan.canonical', ['plan' => $form_state->getValue('plan')]);
  }

}
