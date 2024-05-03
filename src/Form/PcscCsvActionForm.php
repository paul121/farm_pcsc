<?php

namespace Drupal\farm_pcsc\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\file\FileRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Provides a PCSC CSV action form.
 *
 * @see \Drupal\farm_export_csv\Plugin\Action\EntityCsv
 * @see \Drupal\Core\Entity\Form\DeleteMultipleForm
 */
class PcscCsvActionForm extends ConfirmFormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $user;

  /**
   * The tempstore factory.
   *
   * @var \Drupal\Core\TempStore\SharedTempStore
   */
  protected $tempStore;

  /**
   * The serializer service.
   *
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  protected $serializer;

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * The file repository service.
   *
   * @var \Drupal\file\FileRepositoryInterface
   */
  protected $fileRepository;

  /**
   * The file URL generator.
   *
   * @var \Drupal\Core\File\FileUrlGeneratorInterface
   */
  protected $fileUrlGenerator;

  /**
   * The entities to export.
   *
   * @var \Drupal\Core\Entity\EntityInterface[]
   */
  protected $entities;

  /**
   * Constructs an PcscCsvActionForm form object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore factory.
   * @param \Symfony\Component\Serializer\SerializerInterface $serializer
   *   The serializer service.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system service.
   * @param \Drupal\file\FileRepositoryInterface $file_repository
   *   The file repository service.
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $file_url_generator
   *   The file URL generator.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, AccountInterface $user, PrivateTempStoreFactory $temp_store_factory, SerializerInterface $serializer, FileSystemInterface $file_system, FileRepositoryInterface $file_repository, FileUrlGeneratorInterface $file_url_generator) {
    $this->entityTypeManager = $entity_type_manager;
    $this->user = $user;
    $this->tempStore = $temp_store_factory->get('pcsc_csv_confirm');
    $this->serializer = $serializer;
    $this->fileSystem = $file_system;
    $this->fileRepository = $file_repository;
    $this->fileUrlGenerator = $file_url_generator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_user'),
      $container->get('tempstore.private'),
      $container->get('serializer'),
      $container->get('file_system'),
      $container->get('file.repository'),
      $container->get('file_url_generator'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_pcsc_csv_action_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->formatPlural(count($this->entities), 'Export a CSV of @count item?', 'Export a CSV of @count items?');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return Url::fromUri('plans/pcsc_producer');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return 'This will export a CSV in a format that is compatible with PCSC workbook sheets.';
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Export');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $entity_type_id = NULL) {

    // Load entities from the temp store and filter out ones that the user
    // doesn't have access to.
    $this->entities = [];
    $inaccessible_entities = [];
    foreach ($this->tempStore->get($this->user->id()) as $entity) {
      if (!$entity->access('view', $this->currentUser())) {
        $inaccessible_entities[] = $entity;
        continue;
      }
      $this->entities[] = $entity;
    }

    // Add warning message for inaccessible entities.
    if (!empty($inaccessible_entities)) {
      $inaccessible_count = count($inaccessible_entities);
      $this->messenger()->addWarning($this->formatPlural($inaccessible_count, 'Can not export @count item because you do not have the necessary permissions.', 'Can not export @count items because you do not have the necessary permissions.'));
    }

    // If we don't have a list of entities, redirect.
    $this->entities = $this->tempStore->get($this->user->id());
    if (empty($this->entities)) {
      return new RedirectResponse($this->getCancelUrl()->setAbsolute()->toString());
    }

    // Delegate to the parent method.
    $form = parent::buildForm($form, $form_state);

    $form['sheet_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Sheet Type'),
      '#description' => $this->t('Select the type of workbook sheet to export.'),
      '#options' => [
        'all' => $this->t('All'),
        'enrollment' => $this->t('Enrollment'),
        'summary' => $this->t('Summary'),
        'supplemental' => $this->t('Supplemental'),
      ],
      '#empty_option' => $this->t('Select one'),
      '#required' => TRUE,
    ];

    $form['enrollment_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Enrollment sheet'),
      '#description' => $this->t('Select the enrollment sheet to export.'),
      '#options' => [
        'producer' => $this->t('Producer Enrollment'),
        'field' => $this->t('Field Enrollment'),
      ],
      '#states' => [
        'visible' => [
          'select[name="sheet_type"]' => ['value' => 'enrollment'],
        ],
        'required' => [
          'select[name="sheet_type"]' => ['value' => 'enrollment'],
        ],
      ],
    ];

    $form['summary_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Summary sheet'),
      '#description' => $this->t('Select the summary sheet to export.'),
      '#options' => [
        'producer' => $this->t('Producer Summary'),
        'field' => $this->t('Field Summary'),
      ],
      '#states' => [
        'visible' => [
          'select[name="sheet_type"]' => ['value' => 'summary'],
        ],
        'required' => [
          'select[name="sheet_type"]' => ['value' => 'summary'],
        ],
      ],
    ];

    $form['supplemental_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Supplemental sheet'),
      '#description' => $this->t('Select the supplemental sheet to export.'),
      '#options' => [
        '528' => $this->t('Prescribed Grazing (528)'),
      ],
      '#states' => [
        'visible' => [
          'select[name="sheet_type"]' => ['value' => 'supplemental'],
        ],
        'required' => [
          'select[name="sheet_type"]' => ['value' => 'supplemental'],
        ],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Determine the sheet to export and build data.
    $data = [];
    $sheet_type = $form_state->getValue('sheet_type');
    $sub_type = $form_state->getValue("{$sheet_type}_type");
    switch ($sheet_type) {
      case 'enrollment':
      case 'summary':
        // Build the function name to build data.
        $function_name = 'export' . implode('', array_map('ucfirst', explode('_', "{$sheet_type}_$sub_type")));
        if (is_callable([$this, $function_name])) {
          $data = $this->{$function_name}();
        }
        break;

      case 'supplemental':
        $data = $this->exportSupplemental($sub_type);

      case 'all':
      default:
        break;
    }

    // Serialize the data as CSV.
    $output = $this->serializer->serialize($data, 'csv');

    // Prepare the file directory.
    $directory = 'private://csv';
    $this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);

    // Create the file.
    $date = date('c');
    $filename = "$sheet_type-$sub_type-$date.csv";
    $destination = "$directory/$filename";
    try {
      $file = $this->fileRepository->writeData($output, $destination);
    }

    // If file creation failed, bail with a warning.
    catch (\Exception $e) {
      $this->messenger()->addWarning($this->t('Could not create file.'));
      return;
    }

    // Make the file temporary.
    $file->status = 0;
    $file->save();

    // Add confirmation message.
    if (count($this->entities)) {
      $this->messenger()->addStatus($this->formatPlural(count($this->entities), 'Exported @count item.', 'Exported @count items'));
    }

    // Show a link to the file.
    $url = $this->fileUrlGenerator->generateAbsoluteString($file->getFileUri());
    $this->messenger()->addMessage($this->t('CSV file created: <a href=":url">%filename</a>', [
      ':url' => $url,
      '%filename' => $file->label(),
    ]));

    // Clean up the temporary storage.
    $this->tempStore->delete($this->currentUser()->id());
  }

  /**
   * Helper function to build producer enrollment data.
   *
   * @return array
   *   The data array.
   */
  public function exportEnrollmentProducer() {
    $data = [];
    foreach ($this->entities as $entity) {
      $data[] = [
        'Farm ID' => $entity->get('pcsc_farm_id')->value,
        'State or territory' => $entity->get('pcsc_state')->value,
        'County' => $entity->get('pcsc_county')->value,
        'Producer data change' => 'No',
        'Producer start date' => date('m/d/Y', $entity->get('pcsc_start_date')->value),
        'Producer name' => $entity->label(),
        'Underserved status' => $entity->get('pcsc_underserved')->value,
        'Total area' => $entity->get('pcsc_producer_total_area')->value,
        'Total crop area' => $entity->get('pcsc_total_crop_area')->value,
        'Total livestock area' => $entity->get('pcsc_total_livestock_area')->value,
        'Total forest area' => $entity->get('pcsc_total_forest_area')->value,
        'Livestock type 1' => $entity->get('pcsc_livestock_type_1')->value,
        'Livestock head (type 1 avg annual)' => $entity->get('pcsc_livestock_avg_head_1')->value,
        'Livestock type 2' => $entity->get('pcsc_livestock_type_2')->value,
        'Livestock head (type 2 avg annual)' => $entity->get('pcsc_livestock_avg_head_2')->value,
        'Livestock type 3' => $entity->get('pcsc_livestock_type_3')->value,
        'Livestock head (type 3 avg annual)' => $entity->get('pcsc_livestock_avg_head_3')->value,
        'Other livestock type' => $entity->get('pcsc_livestock_type_other')->value,
        'Organic farm' => $entity->get('pcsc_organic')->value,
        'Organic fields' => $entity->get('pcsc_organic_fields')->value,
        'Producer motivation' => $entity->get('pcsc_producer_motivation')->value,
        'Producer outreach 1' => $entity->get('pcsc_producer_outreach_1')->value,
        'Producer outreach 2' => $entity->get('pcsc_producer_outreach_2')->value,
        'Producer outreach 3' => $entity->get('pcsc_producer_outreach_3')->value,
        'Other producer outreach' => $entity->get('pcsc_producer_outreach_other')->value,
        'CSAF experience' => $entity->get('pcsc_csaf_experience')->value,
        'CSAF federal funds' => $entity->get('pcsc_csaf_federal_funds')->value,
        'CSAF state or local funds' => $entity->get('pcsc_csaf_local_funds')->value,
        'CSAF nonprofit funds' => $entity->get('pcsc_csaf_nonprofit_funds')->value,
        'CSAF market incentives' => $entity->get('pcsc_csaf_market_incentives')->value,
      ];
    }
    return $data;
  }

  /**
   * Helper function to build field enrollment data.
   *
   * @return array
   *   The data array.
   */
  public function exportEnrollmentField() {
    $data = [];
    foreach ($this->entities as $entity) {
      $farm_id = $entity->get('pcsc_farm_id')->value;
      $fields = $this->entityTypeManager->getStorage('plan_record')->loadByProperties([
        'type' => 'pcsc_field',
        'plan' => $entity->id(),
      ]);
      foreach ($fields as $field) {

        // Build general field enrollment information.
        $row = [
          'Farm ID' => $farm_id,
          'Tract ID' => $field->get('pcsc_tract_id')->value,
          'Field ID' => $field->get('pcsc_field_id')->value,
          'State or Territory' => $field->get('pcsc_state')->value,
          'County' => $field->get('pcsc_county')->value,
          'Prior Field ID (if applicable)' => $field->get('pcsc_prior_field_id')->value,
          'Field data change' => 'No',
          'Contract start date' => date('m/d/Y', $field->get('pcsc_start_date')->value),
          'Total field area' => $field->get('pcsc_field_total_area')->value,
          'Commodity category' => $field->get('pcsc_commodity_category')->value,
          'Commodity type' => $field->get('pcsc_commodity_type')->value,
          'Baseline yield' => $field->get('pcsc_baseline_yield')->value,
          'Baseline yield unit' => $field->get('pcsc_baseline_yield_unit')->value,
          'Other baseline yield unit' => $field->get('pcsc_baseline_yield_unit_other')->value,
          'Baseline yield location' => $field->get('pcsc_baseline_yield_location')->value,
          'Other baseline yield location' => $field->get('pcsc_baseline_yield_location_other')->value,
          'Field land use' => $field->get('pcsc_land_use')->value,
          'Field irrigated' => $field->get('pcsc_irrigated')->value,
          'Field tillage' => $field->get('pcsc_tillage')->value,
          'Practice (combination) past extent - farm' => $field->get('pcsc_farm_past_practice')->value,
          'Field any CSAF practice' => $field->get('pcsc_field_csaf_practice')->value,
          'Practice (combination) past use - this field' => $field->get('pcsc_field_past_practice')->value,
        ];

        // Create placeholder columns for practices.
        for ($i = 1; $i <= 7; $i++) {
          $row['Practice ' . $i . ' type'] = '';
          $row['Practice ' . $i . ' standard'] = '';
          $row['Other practice ' . $i . ' standard'] = '';
          $row['Planned practice ' . $i . ' implementation year'] = '';
          $row['Practice ' . $i . ' extent'] = '';
          $row['Practice ' . $i . ' extent unit'] = '';
          $row['Other practice ' . $i . ' extent unit'] = '';
        }

        // Add information about practices associated with the field.
        $practices = $this->entityTypeManager->getStorage('plan_record')->loadByProperties([
          'type' => array_keys(farm_pcsc_practice_type_bundle_classes()),
          'plan' => $entity->id(),
          'field' => $field->get('field')->first()->entity->id()
        ]);
        $i = 1;
        foreach ($practices as $practice) {
          // Only include 7 practices.
          if ($i > 7) {
            break;
          }

          $practice_types = farm_pcsc_practice_type_options();

          // Populate practice row information.
          $row['Practice ' . $i . ' type'] = $practice_types[$practice->bundle()];
          $row['Practice ' . $i . ' standard'] = $practice->get('pcsc_practice_standard')->value;
          $row['Other practice ' . $i . ' standard'] = $practice->get('pcsc_practice_standard_other')->value;
          $row['Planned practice ' . $i . ' implementation year'] = $practice->get('pcsc_practice_year')->value;
          $row['Practice ' . $i . ' extent'] = $practice->get('pcsc_practice_extent')->value;
          $row['Practice ' . $i . ' extent unit'] = $practice->get('pcsc_practice_extent_unit')->value;
          $row['Other practice ' . $i . ' extent unit'] = $practice->get('pcsc_practice_extent_unit_other')->value;

          // Increment the practice counter.
          $i++;
        }
        $data[] = $row;
      }

    }
    return $data;
  }

  /**
   * Helper function to build 528 supplemental data.
   *
   * @return array
   *   The data array.
   */
  public function exportSupplemental(string $practice_id) {
    $data = [];
    foreach ($this->entities as $entity) {

      // Query all pcsc_fields for the plan.
      $fields = $this->entityTypeManager->getStorage('plan_record')->loadByProperties([
        'type' => 'pcsc_field',
        'plan' => $entity->id(),
      ]);

      // Build data export for each practice.
      $farm_id = $entity->get('pcsc_farm_id')->value;
      /** @var \Drupal\farm_pcsc\Bundle\PcscFieldPracticeInterface[] $practices */
      $practices = $this->entityTypeManager->getStorage('plan_record')->loadByProperties([
        'type' => "pcsc_field_practice_$practice_id",
        'plan' => $entity->id(),
      ]);
      foreach ($practices as $practice) {

        // Check that the practice field exists.
        $field_id = $practice->get('field')?->first()?->entity->id();
        if (!$field_id || !isset($fields[$field_id])) {
          continue;
        }
        $field = $fields[$field_id];

        // Build data.
        $data[] = [
          'Farm ID' => $farm_id,
          'Tract ID' => $field->get('pcsc_tract_id')->value,
          'Field ID' => $field->get('pcsc_field_id')->value,
          'State or territory' => $field->get('pcsc_state')->value,
          'County' => $field->get('pcsc_county')->value,
        ] + $practice->buildSupplementalFieldPracticeExport();
      }

    }
    return $data;
  }

}
