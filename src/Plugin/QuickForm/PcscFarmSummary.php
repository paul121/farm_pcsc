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
      '#ajax' => [
        'callback' => [$this, 'commodityCallback'],
        'wrapper' => 'pcsc-commodity-wrapper',
      ],
    ];

    $commodity_options = [];
    if ($form_state->getValue('plan')) {
      $commodities = \Drupal::entityTypeManager()->getStorage('plan_record')->loadByProperties(['type' => 'pcsc_commodity', 'plan' => $form_state->getValue('plan')]);
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

    $form['commodity'] = $this->buildInlineContainer();
    $form['commodity']['pcsc_farm_commodity_value'] = [
      '#type' => 'number',
      '#title' => $this->t('Farm commodity value'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['commodity']['pcsc_farm_commodity_volume'] = [
      '#type' => 'number',
      '#title' => $this->t('Farm commodity volume'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['commodity']['pcsc_farm_commodity_volume_unit'] = [
      '#type' => 'select',
      '#title' => $this->t('Farm commodity volume unit'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_farm_commodity_volume_unit'),
      '#empty_option' => '',
    ];
    $form['commodity']['pcsc_farm_commodity_volume_unit_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other farm commodity volume unit'),
      '#states' => [
        'visible' => [
          'select[name="pcsc_farm_commodity_volume_unit"]' => ['value' => 'Other (specify)'],
        ],
      ],
    ];

    $form['pcsc_ghg_calculations'] = [
      '#type' => 'select',
      '#title' => $this->t('Farm GHG calculations'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_ghg_calculations'),
      '#empty_option' => '',
    ];
    $form['pcsc_official_ghg_calculations'] = [
      '#type' => 'select',
      '#title' => $this->t('Farm official GHG calculations'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_farm_summary', 'pcsc_official_ghg_calculations'),
      '#empty_option' => '',
    ];

    $form['measurements'] = $this->buildInlineContainer();
    $form['measurements']['pcsc_official_ghg_er'] = [
      '#type' => 'number',
      '#title' => $this->t('Farm official GHG ER'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['measurements']['pcsc_official_carbon_stock'] = [
      '#type' => 'number',
      '#title' => $this->t('Farm official carbon stock'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['measurements']['pcsc_official_co2_er'] = [
      '#type' => 'number',
      '#title' => $this->t('Farm official CO2 ER'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['measurements']['pcsc_official_ch4_er'] = [
      '#type' => 'number',
      '#title' => $this->t('Farm official CH4 ER'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['measurements']['pcsc_official_n20_er'] = [
      '#type' => 'number',
      '#title' => $this->t('Farm official N2O ER'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['measurements']['pcsc_offsets'] = [
      '#type' => 'number',
      '#title' => $this->t('Farm offsets produced'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];
    $form['measurements']['pcsc_insets'] = [
      '#type' => 'number',
      '#title' => $this->t('Farm insets produced'),
      '#min' => 0,
      '#max' => 10000000,
      '#step' => 0.01,
    ];

    return $form;
  }

  /**
   * Ajax callback for practices container.
   */
  public function commodityCallback(array $form, FormStateInterface $form_state) {
    return $form['pcsc_commodity'];
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
