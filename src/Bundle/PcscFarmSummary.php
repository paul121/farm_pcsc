<?php

namespace Drupal\farm_pcsc\Bundle;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_pcsc\Traits\ListStringTrait;
use Drupal\plan\Entity\PlanRecord;

/**
 * Base class for PCSC Farm Summary plan record types.
 */
class PcscFarmSummary extends PlanRecord {

  use StringTranslationTrait;
  use ListStringTrait;

  /**
   * {@inheritdoc}
   */
  public function label() {

    // Build label with the referenced field.
    if ($plan = $this->get('plan')->first()?->entity) {
      $quarter = "{$this->get('pcsc_year')->value}-{$this->get('pcsc_quarter')->value}";
      return $this->t('Farm Summary @quarter: @producer', ['@quarter' => $quarter, '@producer' => $plan->label()]);
    }

    // Fallback to default.
    return parent::label();
  }

}
