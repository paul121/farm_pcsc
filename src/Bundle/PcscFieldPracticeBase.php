<?php

namespace Drupal\farm_pcsc\Bundle;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\plan\Entity\PlanRecord;

/**
 * Base class for PCSC Field Practice plan record types.
 */
abstract class PcscFieldPracticeBase extends PlanRecord implements PcscFieldPracticeInterface {

}
