<?php

namespace Drupal\farm_pcsc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a PCSC metrics block.
 *
 * @Block(
 *   id = "pcsc_metrics",
 *   admin_label = @Translation("PCSC Metrics")
 * )
 */
class PCSCMetricsBlock extends BlockBase implements ContainerFactoryPluginInterface {

    /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The bundle info service.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $bundleInfo;

  /**
   * Constructs a \Drupal\Component\Plugin\PluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $bundle_info
   *   The bundle info service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, EntityTypeBundleInfoInterface $bundle_info) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->bundleInfo = $bundle_info;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('entity_type.bundle.info')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Add producer stats.
    $producers = $this->entityTypeManager->getStorage('plan')->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'pcsc_producer')
      ->count()
      ->execute();
    $count = new TranslatableMarkup('@count Producers enrolled', ['@count' => $producers]);
    $link = Link::createFromRoute($count, 'view.pcsc_plan_report.page')->toString();
    $output['producers'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $link,
      '#cache' => [
        'tags' => [
          'plan_list',
        ],
      ],
    ];

    // Add field stats.
    $fields = $this->entityTypeManager->getStorage('plan_record')->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'pcsc_field')
      ->exists('plan')
      ->exists('field')
      ->count()
      ->execute();
    $count = new TranslatableMarkup('@count Fields enrolled', ['@count' => $fields]);
    $link = Link::createFromRoute($count, 'view.farm_asset.page_type', ['arg_0' => 'land'])->toString();
    $output['fields'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $link,
      '#cache' => [
        'tags' => [
          'asset_list',
        ],
      ],
    ];

    return $output;
  }

}
