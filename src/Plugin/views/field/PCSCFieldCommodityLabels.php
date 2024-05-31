<?php

namespace Drupal\farm_pcsc\Plugin\views\field;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_pcsc\Bundle\PcscField;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A field that displays the reverse reference PCSC commodity labels.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("pcsc_field_commodity_labels")
 */
class PCSCFieldCommodityLabels extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    if ($values->_entity instanceof PcscField) {
      $plan_record = \Drupal::entityTypeManager()->getStorage('plan_record');
      $commodity_ids = $plan_record->getQuery()
        ->accessCheck(TRUE)
        ->condition('type', 'pcsc_commodity')
        ->condition('pcsc_field', $values->_entity->id())
        ->execute();
      if (count($commodity_ids)) {
        /** @var \Drupal\plan\Entity\PlanRecordInterface[] $commodities */
        $commodities = $plan_record->loadMultiple($commodity_ids);
        $labels = array_map(function($commodity) {
          return $commodity->toLink($commodity->get('pcsc_commodity_type')->value)->toRenderable();
        }, $commodities);
        return [
          '#theme' => 'item_list',
          '#list_type' => 'ul',
          '#items' => $labels,
        ];
      }

      return [
        '#markup' => new TranslatableMarkup('No commodities.'),
      ];
    }
    return [];
  }

}
