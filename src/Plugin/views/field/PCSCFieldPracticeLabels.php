<?php

namespace Drupal\farm_pcsc\Plugin\views\field;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_pcsc\Bundle\PcscField;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A field that displays the reverse reference PCSC practice labels.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("pcsc_field_practice_labels")
 */
class PCSCFieldPracticeLabels extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    if ($values->_entity instanceof PcscField) {
      $plan_record = \Drupal::entityTypeManager()->getStorage('plan_record');
      $practice_ids = $plan_record->getQuery()
        ->accessCheck(TRUE)
        ->condition('type', 'pcsc_field_practice_', 'STARTS_WITH')
        ->condition('pcsc_field', $values->_entity->id())
        ->execute();
      if (count($practice_ids)) {
        /** @var \Drupal\plan\Entity\PlanRecordInterface[] $practices */
        $practices = $plan_record->loadMultiple($practice_ids);
        $labels = array_map(function($practice) {
          return $practice->toLink($practice->practiceTypeLabel())->toRenderable();
        }, $practices);
        return [
          '#theme' => 'item_list',
          '#list_type' => 'ul',
          '#items' => $labels,
        ];
      }

      return [
        '#markup' => new TranslatableMarkup('No practices.'),
      ];
    }
    return [];
  }

}
