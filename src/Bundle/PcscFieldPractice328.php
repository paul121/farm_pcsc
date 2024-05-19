<?php

namespace Drupal\farm_pcsc\Bundle;

use Drupal\Component\Utility\Html;

/**
 * Provides the PCSC Field Practice 328 bundle class.
 */
class PcscFieldPractice328 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function practiceTypeOption(): string {
    return '328, Conservation Crop Rotation';
  }

  /**
   * {@inheritdoc}
   */
  public function buildPracticeForm(int $delta = 1): array {
    $form = parent::buildPracticeForm($delta);
    $form['328_crop_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Conservation crop type'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '328_crop_type'),
      '#required' => TRUE,
    ];
    $form['328_change_implemented'] = [
      '#type' => 'select',
      '#title' => $this->t('Change implemented'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '328_change_implemented'),
      '#required' => TRUE,
    ];

    // Use unique ID because this field may appear for multiple practices.
    $id =  Html::getUniqueId('328_rotation_tillage_type');
    $form['328_rotation_tillage_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Conservation crop rotation tillage type'),
      '#options' => $this->getListOptions('plan_record', $this->bundle(), '328_rotation_tillage_type'),
      '#required' => TRUE,
      '#attributes' => [
        'id' => $id,
      ],
    ];
    $form['328_rotation_tillage_type_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Other conservation crop rotation tillage type'),
      '#states' => [
        'visible' => [
          "select[id=\"$id\"]" => ['value' => 'Other (specify)'],
        ],
      ],
    ];
    $form['328_total_rotation_length'] = [
      '#type' => 'number',
      '#title' => $this->t('Total conservation crop rotation length in days'),
      '#mix' => 1,
      '#max' => 120,
      '#step' => 1,
      '#required' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function buildSupplementalFieldPracticeExport(): array {
    return [
      'Conservation crop type' => $this->get('328_crop_type')->value,
      'Change implemented' => $this->get('328_change_implemented')->value,
      'Conservation crop rotation tillage type' => $this->get('328_rotation_tillage_type')->value,
      'Other conservation crop rotation tillage type' => $this->get('328_rotation_tillage_type_other')->value,
      'Total conservation crop rotation length in days' => $this->get('328_total_rotation_length')->value,
    ];
  }

}
