<?php

namespace Drupal\farm_pcsc\Plugin\QuickForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\plan\Entity\PlanInterface;
use Drupal\plan\Entity\PlanRecord;

/**
 * PCSC Practice quick form.
 *
 * @QuickForm(
 *   id = "pcsc_practice",
 *   label = @Translation("Practice"),
 *   description = @Translation("Add a practice to a field."),
 *   helpText = @Translation("Use this form to add information about a field's conservation practice.."),
 *   permissions = {
 *     "enroll fields",
 *   }
 * )
 */
class PcscPractice extends QuickFormBase {

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
    $form['pcsc_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Field Enrollment'),
      '#options' => $field_options,
      '#required' => TRUE,
      '#prefix' => '<div id="pcsc-field-wrapper">',
      '#suffix' => '</div>',
    ];
    $form['num_practices'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of practices'),
      '#options' => farm_pcsc_allowed_values_helper([1, 2, 3, 4, 5, 6, 7]),
      '#required' => TRUE,
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
   * Ajax callback for field container.
   */
  public function fieldCallback(array $form, FormStateInterface $form_state) {
    return $form['pcsc_field'];
  }

  /**
   * Ajax callback for practice container.
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

    // Create a plan record for each practice.
    foreach ($values['practices'] as $practice_values) {
      $practice_values['plan'] = $values['plan'];
      $practice_values['pcsc_field'] = $values['pcsc_field'];
      $practice = PlanRecord::create($practice_values);
      $practice->save();
    }

    // Set a message and redirect to the list of practices.
    $this->messenger()->addStatus($this->t('@num_practices practices added.', ['@num_practices' => $values['num_practices']]));
    $form_state->setRedirect('view.pcsc_field_practices.page', ['plan' => $values['plan'], 'asset' => $values['field']]);
  }

}
