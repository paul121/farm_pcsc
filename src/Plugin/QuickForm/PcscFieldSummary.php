<?php

namespace Drupal\farm_pcsc\Plugin\QuickForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_pcsc\Traits\ListStringTrait;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickFormElementsTrait;
use Drupal\plan\Entity\PlanInterface;
use Drupal\plan\Entity\PlanRecord;

/**
 * PCSC Field Summary quick form.
 *
 * @QuickForm(
 *   id = "pcsc_field_summary",
 *   label = @Translation("Field summary"),
 *   description = @Translation("Record a field summary entry."),
 *   helpText = @Translation("Use this form to record a field summary entry."),
 *   permissions = {
 *     "enroll fields",
 *   }
 * )
 */
class PcscFieldSummary extends QuickFormBase {

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
    $form['field'] = [
      '#type' => 'select',
      '#title' => $this->t('Field'),
      '#options' => $field_options,
      '#required' => TRUE,
      '#wrapper_attributes' => [
        'id' => 'pcsc-field-wrapper',
      ],
      '#ajax' => [
        'callback' => [$this, 'commodityCallback'],
        'wrapper' => 'pcsc-commodity-wrapper',
      ],
    ];

    $commodity_options = [];
    if ($form_state->getValue('field')) {
      $commodities = \Drupal::entityTypeManager()->getStorage('plan_record')->loadByProperties(['type' => 'pcsc_commodity', 'plan' => $form_state->getValue('plan'), 'pcsc_field' => $form_state->getValue('field')]);
      foreach ($commodities as $commodity) {
        $commodity_options[$commodity->id()] = $commodity->label();
      }
    }
    $form['pcsc_commodity'] = [
      '#type' => 'select',
      '#title' => $this->t('Commodity'),
      '#options' => $commodity_options,
      '#required' => TRUE,
      '#wrapper_attributes' => [
        'id' => 'pcsc-commodity-wrapper',
      ],
    ];

    $form['enrollment'] = $this->buildInlineContainer();
    $form['enrollment']['pcsc_year'] = [
      '#type' => 'select',
      '#title' => $this->t('Enrollment year'),
      '#options' => farm_pcsc_allowed_values_helper([2024, 2025, 2026, 2027, 2028]),
      '#default_value' => date('Y'),
      '#required' => TRUE,
    ];
    $form['enrollment']['pcsc_quarter'] = [
      '#type' => 'select',
      '#title' => $this->t('Enrollment quarter'),
      '#options' => farm_pcsc_allowed_values_helper([1, 2, 3, 4]),
      '#default_value' => ceil(date('m') / 3),
      '#required' => TRUE,
    ];

    $form['pcsc_practice_complete'] = [
      '#type' => 'date',
      '#title' => $this->t('Date practice complete'),
      '#required' => TRUE,
    ];

    $form['pcsc_end_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Contract end date'),
      '#required' => TRUE,
    ];

    $form['assistance'] = $this->buildInlineContainer();
    $form['assistance']['pcsc_mmrv_assistance'] = [
      '#type' => 'select',
      '#title' => $this->t('MMRV assistance provided'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_mmrv_assistance'),
      '#empty_option' => '',
    ];
    $form['assistance']['pcsc_marketing_assistance'] = [
      '#type' => 'select',
      '#title' => $this->t('Marketing assistance provided'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_marketing_assistance'),
      '#empty_option' => '',
    ];

    $form['pcsc_incentive_per_unit'] = [
      '#type' => '',
      '#title' => $this->t('Incentive per acre or head'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_incentive_per_unit'),
      '#empty_option' => '',
    ];

    $form['commodity'] = $this->buildInlineContainer();
    $form['commodity']['pcsc_field_commodity_value'] = [
      '#type' => 'number',
      '#title' => $this->t('Field commodity value'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['commodity']['pcsc_field_commodity_volume'] = [
      '#type' => 'number',
      '#title' => $this->t('Field commodity volume'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['commodity']['pcsc_field_commodity_volume_unit'] = [
      '#type' => 'select',
      '#title' => $this->t('Field commodity volume unit'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_field_commodity_volume_unit'),
      '#empty_option' => '',
    ];
    $form['commodity']['pcsc_field_commodity_volume_unit_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other field commodity volume unit'),
    ];

    $form['cost'] = $this->buildInlineContainer();
    $form['cost']['pcsc_implementation_cost'] = [
      '#type' => 'number',
      '#title' => $this->t('Cost of implementation'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['cost']['pcsc_implementation_cost_unit'] = [
      '#type' => 'select',
      '#title' => $this->t('Cost of implementation unit'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_implementation_cost_unit'),
      '#empty_option' => '',
    ];
    $form['cost']['pcsc_implementation_cost_unit_other'] = [
      '#type' => 'string',
      '#title' => $this->t('Other cost unit'),
    ];
    $form['cost']['pcsc_cost_coverage'] = [
      '#type' => 'number',
      '#title' => $this->t('Cost coverage (percent)'),
      '#min' => 0,
      '#max' => 100,
      '#step' => 1,
    ];

    $form['monitoring'] = $this->buildInlineContainer();
    $form['monitoring']['pcsc_ghg_monitoring_1'] = [
      '#type' => 'select',
      '#title' => $this->t('Field GHG monitoring 1'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_ghg_monitoring_1'),
      '#empty_option' => '',
    ];
    $form['monitoring']['pcsc_ghg_monitoring_2'] = [
      '#type' => 'select',
      '#title' => $this->t('Field GHG monitoring 2'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_ghg_monitoring_2'),
      '#empty_option' => '',
    ];
    $form['monitoring']['pcsc_ghg_monitoring_3'] = [
      '#type' => 'select',
      '#title' => $this->t('Field GHG monitoring 3'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_ghg_monitoring_3'),
      '#empty_option' => '',
    ];
    $form['monitoring']['pcsc_ghg_monitoring_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other field GHG monitoring'),
    ];

    $form['reporting'] = $this->buildInlineContainer();
    $form['reporting']['pcsc_ghg_reporting_1'] = [
      '#type' => 'select',
      '#title' => $this->t('Field GHG reporting 1'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_ghg_reporting_1'),
      '#empty_option' => '',
    ];
    $form['reporting']['pcsc_ghg_reporting_2'] = [
      '#type' => 'select',
      '#title' => $this->t('Field GHG reporting 2'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_ghg_reporting_2'),
      '#empty_option' => '',
    ];
    $form['reporting']['pcsc_ghg_reporting_3'] = [
      '#type' => 'select',
      '#title' => $this->t('Field GHG reporting 3'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_ghg_reporting_3'),
      '#empty_option' => '',
    ];
    $form['reporting']['pcsc_ghg_reporting_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other field GHG reporting'),
    ];

    $form['verification'] = $this->buildInlineContainer();
    $form['verification']['pcsc_ghg_verification_1'] = [
      '#type' => 'select',
      '#title' => $this->t('Field GHG verification 1'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_ghg_verification_1'),
      '#empty_option' => '',
    ];
    $form['verification']['pcsc_ghg_verification_2'] = [
      '#type' => 'select',
      '#title' => $this->t('Field GHG verification 2'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_ghg_verification_2'),
      '#empty_option' => '',
    ];
    $form['verification']['pcsc_ghg_verification_3'] = [
      '#type' => 'select',
      '#title' => $this->t('Field GHG verification 3'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_ghg_verification_3'),
      '#empty_option' => '',
    ];
    $form['verification']['pcsc_ghg_verification_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other field GHG verification'),
    ];

    $form['pcsc_ghg_calculations'] = [
      '#type' => 'select',
      '#title' => $this->t('Field GHG calculations'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_ghg_calculations'),
      '#empty_option' => '',
    ];
    $form['pcsc_official_ghg_calculations'] = [
      '#type' => 'select',
      '#title' => $this->t('Field official GHG calculations'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_official_ghg_calculations'),
      '#empty_option' => '',
    ];

    $form['measurements'] = $this->buildInlineContainer();
    $form['measurements']['field_official_ghg_er'] = [
      '#type' => 'number',
      '#title' => $this->t('Field official GHG ER'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['measurements']['pcsc_official_carbon_stock'] = [
      '#type' => 'number',
      '#title' => $this->t('Field official carbon stock'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['measurements']['pcsc_official_co2_er'] = [
      '#type' => 'number',
      '#title' => $this->t('Field official CO2 ER'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['measurements']['pcsc_official_ch4_er'] = [
      '#type' => 'number',
      '#title' => $this->t('Field official CH4 ER'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['measurements']['pcsc_official_n20_er'] = [
      '#type' => 'number',
      '#title' => $this->t('Field official N2O ER'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['measurements']['pcsc_offsets'] = [
      '#type' => 'number',
      '#title' => $this->t('Field offsets produced'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['measurements']['pcsc_insets'] = [
      '#type' => 'number',
      '#title' => $this->t('Field insets produced'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];

    $form['pcsc_other_field_measurement'] = [
      '#type' => 'select',
      '#title' => $this->t('Other field measurement'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field_summary', 'pcsc_other_field_measurement'),
      '#empty_option' => '',
    ];

    return $form;
  }

  /**
   * Ajax callback for field container.
   */
  public function fieldCallback(array $form, FormStateInterface $form_state) {
    return $form['field'];
  }

  /**
   * Ajax callback for commodity container.
   */
  public function commodityCallback(array $form, FormStateInterface $form_state) {
    return $form['pcsc_commodity'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Create commodity record.
    $record_values = [
      'type' => 'pcsc_field_summary',
      'pcsc_practice_complete' => strtotime($form_state->getValue('pcsc_practice_complete')),
      'pcsc_end_date' => strtotime($form_state->getValue('pcsc_end_date')),
    ] + $form_state->getValues();
    $summary = PlanRecord::create($record_values);
    $summary->save();

    // Set a message and redirect to the producer plan.
    $entity_url = $summary->toUrl()->setAbsolute()->toString();
    $this->messenger()->addStatus($this->t('Field summary created: <a href=":url">%label</a>', [':url' => $entity_url, '%label' => $summary->label()]));
    $form_state->setRedirect('entity.plan_record.canonical', ['plan_record' => $form_state->getValue('field')]);
  }

}
