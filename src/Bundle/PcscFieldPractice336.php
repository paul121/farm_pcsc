<?php

namespace Drupal\farm_pcsc\Bundle;

/**
 * Provides the PCSC Field Practice 336 bundle class.
 * )
 */
class PcscFieldPractice336 extends PcscFieldPracticeBase {

  /**
   * {@inheritdoc}
   */
  public function practiceTypeLabel(): string {
    return '336: Soil Carbon Amendment';
  }

  /**
   * {@inheritdoc}
   */
  public function practiceTypeOption(): string {
    return '336, Soil Carbon Amendment';
  }

}
