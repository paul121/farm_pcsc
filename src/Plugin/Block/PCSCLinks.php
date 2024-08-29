<?php

namespace Drupal\farm_pcsc\Plugin\Block;

use Drupal\Component\Utility\Html;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Url;
use Drupal\farm_quick\QuickFormInstanceManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a quick links block.
 *
 * @Block(
 *   id = "pcsc_links",
 *   admin_label = @Translation("PCSC Links")
 * )
 */
class PCSCLinks extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The quick form manager service.
   *
   * @var \Drupal\farm_quick\QuickFormInstanceManagerInterface
   */
  protected $quickFormManager;

  /**
   * Constructs a \Drupal\Component\Plugin\PluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\farm_quick\QuickFormInstanceManagerInterface $quick_form_manager
   *   The quick form manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, QuickFormInstanceManagerInterface $quick_form_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->quickFormManager = $quick_form_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('quick_form.instance_manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $quick_forms = [
      'pcsc_producer_enrollment',
      'pcsc_field_enrollment',
      'pcsc_farm_benefit',
      'pcsc_farm_summary',
      'pcsc_field_summary',
    ];
    foreach ($quick_forms as $id) {
      if ($quick_form = $this->quickFormManager->getInstance($id)) {
        $url = Url::fromRoute("farm.quick.$id");
        if ($url->access()) {
          $items[] = [
            'title' => Markup::create(Html::escape($quick_form->getLabel())),
            'description' => Html::escape($quick_form->getDescription()),
            'url' => $url,
          ];
        }
      }
    }

    $output = [
      '#theme' => 'admin_block_content',
      '#content' => $items,
    ];

    return $output;
  }

}
