<?php

namespace Drupal\farm_pcsc\Bundle;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_pcsc\Traits\ListStringTrait;
use Drupal\plan\Entity\PlanRecord;

/**
 * Base class for PCSC Field Summary plan record types.
 */
class PcscFieldSummary extends PlanRecord {

  use StringTranslationTrait;
  use ListStringTrait;

  /**
   * {@inheritdoc}
   */
  public function label() {

    // Build label with the referenced field.
    if ($commodity = $this->get('pcsc_commodity')->first()?->entity) {
      $quarter = "{$this->get('pcsc_year')->value}-{$this->get('pcsc_quarter')->value}";
      return $this->t('Field Summary @quarter: @commodity', ['@quarter' => $quarter, '@commodity' => $commodity->label()]);
    }

    // Fallback to default.
    return parent::label();
  }

}
