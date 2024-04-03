<?php

namespace Drupal\farm_pcsc\Bundle;

use Drupal\asset\Entity\AssetInterface;
use Drupal\plan\Entity\PlanRecordInterface;

/**
 * Bundle logic for PCSC field practice bundles.
 */
interface PcscFieldPracticeInterface extends PlanRecordInterface {

  /**
   * Build a form for the practice.
   *
   * @param int $delta
   *   The practice number.
   *
   * @return array
   *  Returns a form build array.
   */
  public function buildPracticeForm(int $delta = 1): array;

}
