<?php

namespace Drupal\farm_pcsc\Bundle;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_pcsc\Traits\ListStringTrait;
use Drupal\plan\Entity\PlanRecord;

/**
 * Base class for PCSC Field Practice plan record types.
 */
class PcscField extends PlanRecord {

  use StringTranslationTrait;
  use ListStringTrait;

  /**
   * {@inheritdoc}
   */
  public function label() {

    // Build label with the referenced field.
    if ($field = $this->get('field')->first()?->entity) {
      return $this->t('Field enrollment: @field', ['@field' => $field->label()]);
    }

    // Fallback to default.
    return parent::label();
  }

}
