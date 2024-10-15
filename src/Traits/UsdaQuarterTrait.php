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
    $form = $this->buildInlineContainer();
    $form['pcsc_year'] = [
      '#type' => 'select',
      '#title' => $this->t('Year'),
      '#options' => farm_pcsc_allowed_values_helper([2024, 2025, 2026, 2027, 2028]),
      '#default_value' => date('Y'),
      '#required' => TRUE,
    ];
    $form['pcsc_quarter'] = [
      '#type' => 'select',
      '#title' => $this->t('Quarter'),
      '#options' => farm_pcsc_allowed_values_helper([1, 2, 3, 4]),
      '#default_value' => ceil(date('m') / 3),
      '#required' => TRUE,
    ];
    return $form;
  }

}
