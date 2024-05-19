<?php

namespace Drupal\farm_pcsc\Bundle;

use Drupal\asset\Entity\AssetInterface;
use Drupal\plan\Entity\PlanRecordInterface;

/**
 * Bundle logic for PCSC field practice bundles.
 */
interface PcscFieldPracticeInterface extends PlanRecordInterface {

  /**
   * Returns the practice type option as expected in PCSC workbooks.
   *
   * @return string
   *   The practice type label.
   */
  public function practiceTypeOption(): string;

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

  /**
   * Helper function to build the supplemental field practice data for export.
   *
   * This only includes the supplemental fields unique to this field practice.
   *
   * @return array
   *   Array of supplement field practices data to include in export.
   */
  public function buildSupplementalFieldPracticeExport(): array;

}
