<?php

namespace Drupal\farm_pcsc\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType\FarmPlanRecordType;

/**
 * Provides the PCSC Field Practice plan record type.
 *
 * @PlanRecordType(
 *   id = "pcsc_field_practice",
 *   label = @Translation("PCSC Field Practice"),
 * )
 */
class PcscFieldPractice extends FarmPlanRecordType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'field' => [
        'type' => 'entity_reference',
        'label' => $this->t('Field'),
        'description' => $this->t('Associates the PCSC Farm plan with a Land asset.'),
        'target_type' => 'asset',
        'target_bundle' => 'land',
        'cardinality' => 1,
        'required' => TRUE,
      ],
      '327_species_category' => [
        'type' => 'list_string',
        'label' => $this->t('328: Species category'),
        'allowed_values' => [
          'Brassicas',
          'Grasses',
          'Legumes',
          'Non-legume broadleaves',
          'Shrubs',
        ],
      ],
      '328_crop_type' => [
        'type' => 'list_string',
        'label' => $this->t('328: Conservation crop type'),
        'allowed_values' => [
          'Brassica',
          'Broadleaf',
          'Cool season',
          'Grass',
          'Legume',
          'Warm season',
        ],
      ],
      '328_change_implemented' => [
        'type' => 'list_string',
        'label' => $this->t('328: Change implemented'),
        'allowed_values' => [
          'Added perennial crop',
          'Reduced fallow period',
          'Both',
        ],
      ],
      '328_rotation_tillage_type' => [
        'type' => 'list_string',
        'label' => $this->t('328: Conservation crop rotation tillage type'),
        'allowed_values' => [
          'Conventional (plow, chisel, disk)',
          'No-till, direct seed',
          'Reduced rill',
          'Strip rill',
          'None',
          'Other (specify)',
        ],
      ],
      '328_total_rotation_length' => [
        'type' => 'integer',
        'label' => $this->t('328: Total conservation crop rotation length in days'),
        'mix' => 1,
        'max' => 120,
      ],
      '329_surface_disturbance' => [
        'type' => 'list_string',
        'label' => $this->t('329: Surface disturbance'),
        'allowed_values' => [
          'None',
          'Seed row only',
        ],
      ],
      '340_species_category' => [
        'type' => 'list_string',
        'label' => $this->t('340: Species category (select most common/extensive type if using more than one)'),
        'allowed_values' => [
          'Brassicas',
          'Forbs',
          'Grasses',
          'Legume',
          'Non-legume broadleaves',
        ],
      ],
      '340_planned_management' => [
        'type' => 'list_string',
        'label' => $this->t('340: Cover crop planned management'),
        'allowed_values' => [
          'Grazing',
          'Haying',
          'Termination',
        ],
      ],
      '340_termination_method' => [
        'type' => 'list_string',
        'label' => $this->t('340: Cover crop termination method'),
        'allowed_values' => [
          'Burning',
          'Herbicide application',
          'Incorporation',
          'Mowing',
          'Rolling/crimping',
          'Winter kill/frost',
        ],
      ],
      '345_surface_disturbance' => [
        'type' => 'list_string',
        'label' => $this->t('345: Surface disturbance'),
        'allowed_values' => [
          'None',
          'Seed row/ridge tillage for planting',
          'Shallow across most of the soil surface',
          'Vertical/mulch',
        ],
      ],
      '484_rotation_tillage_type' => [
        'type' => 'list_string',
        'label' => $this->t('484: Mulch type'),
        'allowed_values' => [
          'Gravel',
          'Natural',
          'Synthetic',
          'Wood',
        ],
      ],
      '484_total_rotation_length' => [
        'type' => 'integer',
        'label' => $this->t('484: Mulch cover (percent of field)'),
        'mix' => 1,
        'max' => 100,
      ],
      '528_grazing_type' => [
        'type' => 'list_string',
        'label' => $this->t('528: Grazing type'),
        'allowed_values' => [
          'Cell grazing',
          'Deferred rotational',
          'Management intensive',
          'Rest-rotation',
        ],
      ],
      '590_nutrient_type' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient type with CPS 590'),
        'allowed_values' => [
          'Biosolids',
          'Commercial fertilizers',
          'Compost',
          'EEF (nitrification inhibitor)',
          'EEF (slow or controlled release)',
          'EEF (urease inhibitor)',
          'Green manure',
          'Liquid animal manure',
          'Organic by-products',
          'Organic residues or materials',
          'Solid/semi-solid animal manure',
          'Wastewater',
        ],
      ],
      '590_application_method' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient application method with CPS 590'),
        'allowed_values' => [
          'Banded',
          'Broadcast',
          'Injection',
          'Irrigation',
          'Surface application',
          'Surface application with tillage',
          'Variable rate',
        ],
      ],
      '590_previous_application_method' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient application method in the previous year'),
        'allowed_values' => [
          'Banded',
          'Broadcast',
          'Injection',
          'Irrigation',
          'Surface application',
          'Surface application with tillage',
          'Variable rate',
        ],
      ],
      '590_timing' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient application timing with CPS 590'),
        'allowed_values' => [
          'Single pre-planting',
          'Single post-planting',
          'Split pre- and post-planting',
          'Split post planting',
        ],
      ],
      '590_previous_timing' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient application timing in the previous year'),
        'allowed_values' => [
          'Single pre-planting',
          'Single post-planting',
          'Split pre- and post-planting',
          'Split post planting',
        ],
      ],
      '590_rate' => [
        'type' => 'integer',
        'label' => $this->t('590: Nutrient application rate with CPS 590'),
        'mix' => 1,
        'max' => 20000,
      ],
      '590_rate_unit' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient application rate unit with CPS 590'),
        'allowed_values' => [
          'Gallons per acre',
          'Pounds per acre',
        ],
      ],
      '590_rate_change' => [
        'type' => 'list_string',
        'label' => $this->t('590: Nutrient application rate change'),
        'allowed_values' => [
          'Decrease compared to previous year',
          'Increase compared to previous year',
          'No change',
        ],
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
