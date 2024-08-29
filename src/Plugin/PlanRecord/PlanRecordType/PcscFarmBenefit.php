<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType\FarmPlanRecordType;

/**
 * Provides the Farm benefit plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_farm_benefit",
 *   label = @Translation("Farm benefit"),
 * )
 */
class PcscFarmBenefit extends FarmPlanRecordType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'pcsc_year' => [
        'type' => 'integer',
        'label' => t('Year'),
        'size' => 'small',
        'min' => 2024,
        'required' => TRUE,
      ],
      'pcsc_quarter' => [
        'type' => 'integer',
        'label' => t('Quarter'),
        'size' => 'tiny',
        'min' => 1,
        'max' => 4,
        'required' => TRUE,
      ],
      'pcsc_producer_ta_1' => [
        'type' => 'list_string',
        'label' => $this->t('Producer TA received 1'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->producerTaOptions()),
      ],
      'pcsc_producer_ta_2' => [
        'type' => 'list_string',
        'label' => $this->t('Producer TA received 2'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->producerTaOptions()),
      ],
      'pcsc_producer_ta_3' => [
        'type' => 'list_string',
        'label' => $this->t('Producer TA received 3'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->producerTaOptions()),
      ],
      'pcsc_producer_ta_other' => [
        'type' => 'string',
        'label' => $this->t('Other producer TA received'),
      ],
      'pcsc_incentive_type_1' => [
        'type' => 'list_string',
        'label' => $this->t('Incentive type 1'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->incentiveTypeOptions()),
      ],
      'pcsc_incentive_type_2' => [
        'type' => 'list_string',
        'label' => $this->t('Incentive type 2'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->incentiveTypeOptions()),
      ],
      'pcsc_incentive_type_3' => [
        'type' => 'list_string',
        'label' => $this->t('Incentive type 3'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->incentiveTypeOptions()),
      ],
      'pcsc_incentive_type_4' => [
        'type' => 'list_string',
        'label' => $this->t('Incentive type 4'),
        'allowed_values' => farm_pcsc_allowed_values_helper($this->incentiveTypeOptions()),
      ],
      'pcsc_incentive_type_other' => [
        'type' => 'string',
        'label' => $this->t('Other incentive type'),
      ],
      'pcsc_incentive_amount' => [
        'type' => 'decimal',
        'label' => $this->t('Producer incentive amount'),
        'precision' => 10,
        'scale' => 2,
        'min' => 0,
        'max' => 5000000,
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

  /**
   * Producer TA received options.
   *
   * @return string[]
   *   Returns an array of options.
   */
  protected function producerTaOptions() {
    return [
      'Demonstration plots',
      'Equipment demonstrations',
      'Group field days or in-person field workshops',
      'Hotline',
      'One-on-one enrollment assistance',
      'One-on-one field visits',
      'One-on-one producer mentorship',
      'Producer networks and peer-to-peer groups',
      'Retailer consultation',
      'Social media/digital tools',
      'Train-the trainer opportunities',
      'Virtual meetings or field days',
      'Webinars and videos',
      'Written materials',
      'None',
      'Other (specify)',
    ];
  }

  /**
   * Incentive type options.
   *
   * @return string[]
   *   Returns an array of options.
   */
  protected function incentiveTypeOptions() {
    return [
      'Cash payment',
      'Equipment loan',
      'Guaranteed commodity premium payment',
      'Inputs and supplies',
      'Land rental',
      'Loan',
      'Paid labor',
      'Post-harvest transportation',
      'Tuition or fees for training',
      'Other (specify)',
    ];
  }

}
