<?php

namespace Drupal\farm_pcsc\Plugin\QuickForm;

use Drupal\asset\Entity\Asset;
use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_pcsc\Traits\ListStringTrait;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickFormElementsTrait;
use Drupal\plan\Entity\PlanInterface;
use Drupal\plan\Entity\PlanRecord;

/**
 * PCSC Field Enrollment quick form.
 *
 * @QuickForm(
 *   id = "pcsc_field_enrollment",
 *   label = @Translation("Field enrollment"),
 *   description = @Translation("Enroll a field in this PCSC project."),
 *   helpText = @Translation("Use this form to enroll a field in this PCSC project."),
 *   permissions = {
 *     "enroll fields",
 *   }
 * )
 */
class PcscFieldEnrollment extends QuickFormBase {

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
    ];
    $form['geometry'] = [
      '#type' => 'farm_map_input',
      '#title' => $this->t('Geometry'),
      '#required' => TRUE,
    ];

    $form['id'] = $this->buildInlineContainer();
    $form['id']['pcsc_tract_id'] = [
      '#type' => 'number',
      '#title' => $this->t('Tract ID'),
      '#min' => 1,
      '#step' => 1,
      '#required' => TRUE,
    ];
    $form['id']['pcsc_field_id'] = [
      '#type' => 'number',
      '#title' => $this->t('Field ID'),
      '#min' => 1,
      '#step' => 1,
      '#required' => TRUE,
    ];
    $form['id']['pcsc_prior_field_id'] = [
      '#type' => 'number',
      '#title' => $this->t('Prior Field ID'),
      '#min' => 1,
      '#step' => 1,
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
      '#title' => $this->t('Contract start date'),
      '#required' => TRUE,
    ];

    $form['field'] = $this->buildInlineContainer();
    $form['field']['pcsc_land_use'] = [
      '#type' => 'select',
      '#title' => $this->t('Field land use'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field', 'pcsc_land_use'),
      '#required' => TRUE,
    ];
    $form['field']['pcsc_irrigated'] = [
      '#type' => 'select',
      '#title' => $this->t('Field irrigated'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field', 'pcsc_irrigated'),
      '#required' => TRUE,
    ];
    $form['field']['pcsc_tillage'] = [
      '#type' => 'select',
      '#title' => $this->t('Field tillage'),
      '#options' => $this->getListOptions('plan_record', 'pcsc_field', 'pcsc_tillage'),
      '#required' => TRUE,
    ];

    $form['num_practices'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of practices'),
      '#description' => $this->t('If practice details are not known at this time, leave this set to 0. Practices can be added later.'),
      '#options' => farm_pcsc_allowed_values_helper([0, 1, 2, 3, 4, 5, 6, 7]),
      '#default_value' => 0,
      '#ajax' => [
        'callback' => [$this, 'practicesCallback'],
        'wrapper' => 'practices-container',
      ],
    ];
    $form['practices'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'practices-container'],
      '#tree' => TRUE,
    ];
    if (!empty($form_state->getValue('num_practices'))) {
      for ($i = 1; $i <= $form_state->getValue('num_practices'); $i++) {
        $form['practices'][$i] = [
          '#type' => 'details',
          '#title' => $this->t('Practice @number', ['@number' => $i]),
          '#open' => TRUE,
        ];
        $form['practices'][$i]['type'] = [
          '#type' => 'select',
          '#title' => $this->t('Practice type'),
          '#options' => farm_pcsc_practice_type_options(),
          '#required' => TRUE,
          '#ajax' => [
            'callback' => [$this, 'practicesCallback'],
            'wrapper' => 'practices-container',
          ],
        ];
        if (!empty($form_state->getValue(['practices', $i, 'type']))) {
          $practice_classes = farm_pcsc_plan_record_bundle_classes();
          $practice_class = $practice_classes[$form_state->getValue(['practices', $i, 'type'])];
          /** @var \Drupal\farm_pcsc\Bundle\PcscFieldPracticeInterface $practice */
          $practice = $practice_class::create(['type' => $form_state->getValue(['practices', $i, 'type'])]);
          $form['practices'][$i] += $practice->buildPracticeForm($i);
        }
      }
    }
    return $form;
  }

  /**
   * Ajax callback for County field.
   */
  public function countyCallback(array $form, FormStateInterface $form_state) {
    return $form['location']['county_container'];
  }

  /**
   * Ajax callback for practices container.
   */
  public function practicesCallback(array $form, FormStateInterface $form_state) {
    return $form['practices'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Get all submitted values.
    $values = $form_state->getValues();

    // Load the producer.
    $producer = \Drupal::entityTypeManager()->getStorage('plan')->load($values['plan']);

    // Create land asset.
    $land = Asset::create([
      'type' => 'land',
      'land_type' => 'field',
      'name' => $producer->label() . ': ' . $values['pcsc_field_id'],
      'intrinsic_geometry' => $values['geometry'],
      'status' => 'active',
    ]);
    $land->save();

    // Create a plan record for the field.
    $field_values = $values;
    $field_values['type'] = 'pcsc_field';
    $field_values['field'] = $land->id();
    $field_values['pcsc_start_date'] = strtotime($form_state->getValue('pcsc_start_date'));
    unset($field_values['practices']);
    $field = PlanRecord::create($field_values);
    $field->save();

    // Create a plan record for each practice.
    foreach ($values['practices'] as $practice_values) {
      $practice_values['plan'] = $values['plan'];
      $practice_values['pcsc_field'] = $field->id();
      $practice = PlanRecord::create($practice_values);
      $practice->save();
    }

    // Set a message and redirect to the list of fields.
    $entity_url = $field->toUrl()->setAbsolute()->toString();
    $this->messenger()->addStatus($this->t('Field enrolled: <a href=":url">%label</a>', [':url' => $entity_url, '%label' => $field->label()]));
    $form_state->setRedirect('entity.plan.canonical', ['plan' => $form_state->getValue('plan')]);
  }

}
