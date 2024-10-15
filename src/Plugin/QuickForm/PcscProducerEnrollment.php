<?php

namespace Drupal\farm_pcsc\Plugin\QuickForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_pcsc\Traits\ListStringTrait;
use Drupal\farm_pcsc\Traits\UsdaQuarterTrait;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickFormElementsTrait;
use Drupal\plan\Entity\Plan;

/**
 * PCSC Producer Enrollment quick form.
 *
 * @QuickForm(
 *   id = "pcsc_producer_enrollment",
 *   label = @Translation("Producer enrollment"),
 *   description = @Translation("Enroll a producer in this PCSC project."),
 *   helpText = @Translation("Use this form to enroll a producer in this PCSC project."),
 *   permissions = {
 *     "enroll producers",
 *   }
 * )
 */
class PcscProducerEnrollment extends QuickFormBase {

  use ListStringTrait;
  use QuickFormElementsTrait;
  use UsdaQuarterTrait;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Add fields for year and quarter.
    $form['quarter'] = $this->usdaYearQuarterDropdowns();
    $form['quarter']['year']['#title'] = $this->t('Enrollment year');
    $form['quarter']['quarter']['#title'] = $this->t('Enrollment quarter');

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Producer name'),
      '#required' => TRUE,
    ];
    $form['pcsc_farm_id'] = [
      '#type' => 'number',
      '#title' => $this->t('FSA Farm ID'),
      '#min' => 1,
      '#step' => 1,
      '#required' => TRUE,
    ];

    $form['location'] = $this->buildInlineContainer();
    $state_options = farm_pcsc_state_options();
    $form['location']['pcsc_state'] = [
      '#type' => 'select',
      '#title' => $this->t('State or territory'),
      '#options' => array_combine($state_options, $state_options),
      '#ajax' => [
        'callback' => [$this, 'countyCallback'],
        'wrapper' => 'county-container',
      ],
      '#required' => TRUE,
    ];
    $form['location']['county_container'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'county-container'],
    ];
    $county_options = farm_pcsc_state_county_options($form_state->getValue('pcsc_state') ?? '');
    $form['location']['county_container']['pcsc_county'] = [
      '#type' => 'select',
      '#title' => $this->t('County'),
      '#options' => array_combine($county_options, $county_options),
      '#required' => TRUE,
    ];
    $form['pcsc_start_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Producer start date'),
      '#required' => TRUE,
    ];
    $form['pcsc_underserved'] = [
      '#type' => 'select',
      '#title' => $this->t('Underserved status'),
      '#options' => $this->getListOptions('plan', 'pcsc_producer', 'pcsc_underserved'),
      '#default_value' => 'I don\'t know',
      '#required' => TRUE,
    ];

    $form['organic'] = $this->buildInlineContainer();
    $form['organic']['pcsc_organic'] = [
      '#type' => 'select',
      '#title' => $this->t('Organic farm'),
      '#options' => $this->getListOptions('plan', 'pcsc_producer', 'pcsc_organic'),
      '#default_value' => 'I don\'t know',
      '#required' => TRUE,
    ];
    $form['organic']['pcsc_organic_fields'] = [
      '#type' => 'select',
      '#title' => $this->t('Organic fields'),
      '#options' => $this->getListOptions('plan', 'pcsc_producer', 'pcsc_organic_fields'),
      '#default_value' => 'I don\'t know',
      '#required' => TRUE,
    ];

    $form['pcsc_csaf_experience'] = [
      '#type' => 'select',
      '#title' => $this->t('CSAF experience'),
      '#options' => $this->getListOptions('plan', 'pcsc_producer', 'pcsc_csaf_experience'),
      '#default_value' => 'I don\'t know',
      '#required' => TRUE,
    ];

    $form['areas'] = $this->buildInlineContainer();
    $form['areas']['pcsc_total_forest_area'] = [
      '#type' => 'number',
      '#title' => $this->t('Total forest area (acres)'),
      '#min' => 0,
      '#max' => 100000,
      '#step' => 1,
      '#default_value' => 0,
      '#required' => TRUE,
    ];
    $form['areas']['pcsc_total_livestock_area'] = [
      '#type' => 'number',
      '#title' => $this->t('Total livestock area (acres)'),
      '#min' => 0,
      '#max' => 100000,
      '#step' => 1,
      '#default_value' => 0,
      '#required' => TRUE,
    ];

    $form['livestock1'] = $this->buildInlineContainer();
    $form['livestock1']['pcsc_livestock_type_1'] = [
      '#type' => 'select',
      '#title' => $this->t('Livestock type 1'),
      '#options' => ['' => ''] + $this->getListOptions('plan', 'pcsc_producer', 'pcsc_livestock_type_1'),
    ];
    $form['livestock1']['pcsc_livestock_avg_head_1'] = [
      '#type' => 'number',
      '#title' => $this->t('Livestock head (type 1 avg annual)'),
      '#min' => 1,
      '#max' => 10000000,
      '#step' => 1,
    ];
    $form['livestock2'] = $this->buildInlineContainer();
    $form['livestock2']['pcsc_livestock_type_2'] = [
      '#type' => 'select',
      '#title' => $this->t('Livestock type 2'),
      '#options' => ['' => ''] + $this->getListOptions('plan', 'pcsc_producer', 'pcsc_livestock_type_2'),
    ];
    $form['livestock2']['pcsc_livestock_avg_head_2'] = [
      '#type' => 'number',
      '#title' => $this->t('Livestock head (type 2 avg annual)'),
      '#min' => 1,
      '#max' => 10000000,
      '#step' => 1,
    ];
    $form['livestock3'] = $this->buildInlineContainer();
    $form['livestock3']['pcsc_livestock_type_3'] = [
      '#type' => 'select',
      '#title' => $this->t('Livestock type 3'),
      '#options' => ['' => ''] + $this->getListOptions('plan', 'pcsc_producer', 'pcsc_livestock_type_3'),
    ];
    $form['livestock3']['pcsc_livestock_avg_head_3'] = [
      '#type' => 'number',
      '#title' => $this->t('Livestock head (type 3 avg annual)'),
      '#min' => 1,
      '#max' => 10000000,
      '#step' => 1,
    ];
    $form['pcsc_livestock_type_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other livestock type'),
    ];

    return $form;
  }

  /**
   * Ajax callback for County field.
   */
  public function countyCallback(array $form, FormStateInterface $form_state) {
    return $form['location']['county_container'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Create a plan for the producer.
    $values = $form_state->getValues();
    $values['type'] = 'pcsc_producer';
    $values['pcsc_start_date'] = strtotime($form_state->getValue('pcsc_start_date'));
    $plan = Plan::create($values);
    $plan->save();

    // Set a message and redirect to the plan.
    $this->messenger()->addStatus($this->t('Producer enrolled: @producer', ['@producer' => $plan->label()]));
    $form_state->setRedirect('entity.plan.canonical', ['plan' => $plan->id()]);
  }

}
