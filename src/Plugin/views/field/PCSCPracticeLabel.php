<?php

namespace Drupal\farm_pcsc\Plugin\views\field;

use Drupal\farm_pcsc\Bundle\PcscFieldPracticeInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A field that displays the PCSC practice label.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("pcsc_practice_label")
 */
class PCSCPracticeLabel extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    if ($values->_entity instanceof PcscFieldPracticeInterface) {
      return [
        '#markup' => $values->_entity->practiceTypeLabel(),
      ];
    }
    return [];
  }

}
