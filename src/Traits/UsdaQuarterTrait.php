<?php

namespace Drupal\farm_pcsc\Traits;

use Drupal\farm_quick\Traits\QuickFormElementsTrait;

/**
 * Provides helper methods for dealing with USDA Quarters.
 */
trait UsdaQuarterTrait {

  use QuickFormElementsTrait;

  /**
   * Simple helper to change a list_string field widget to options_select.
   *
   * @return array
   *   Returns year and quarter elements for use in a quick form.
   */
  public function usdaYearQuarterDropdowns() {
    $default_year = date('Y');
    switch (date('m')) {
      case 1:
      case 2:
      case 3:
        $default_quarter = 4;
        break;
      case 4:
      case 5:
      case 6:
        $default_quarter = 1;
        break;
      case 7:
      case 8:
      case 9:
        $default_quarter = 2;
        break;
      case 10:
      case 11:
      case 12:
        $default_quarter = 3;
    }
    $form = $this->buildInlineContainer();
    $form['pcsc_year'] = [
      '#type' => 'select',
      '#title' => $this->t('Year'),
      '#options' => farm_pcsc_allowed_values_helper([2024, 2025, 2026, 2027, 2028]),
      '#default_value' => $default_year,
      '#required' => TRUE,
    ];
    $form['pcsc_quarter'] = [
      '#type' => 'select',
      '#title' => $this->t('Quarter'),
      '#options' => [
        4 => $this->t('January-March (USDA Q4)'),
        1 => $this->t('April-June (USDA Q1)'),
        2 => $this->t('July-September (USDA Q2)'),
        3 => $this->t('October-December (USDA Q3)'),
      ],
      '#default_value' => $default_quarter,
      '#required' => TRUE,
    ];
    return $form;
  }

}
