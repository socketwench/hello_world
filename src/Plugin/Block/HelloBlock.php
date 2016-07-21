<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'HelloBlock' block.
 *
 * @Block(
 *  id = "hello_block",
 *  admin_label = @Translation("Hello block"),
 * )
 */
class HelloBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    // @todo Inject the entity query service?
    // @todo Inject the entity_type.manager service?
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [
      '#attached' => [
        'library' => [
          'hello_world/hello_world.hello_block',
        ],
      ],
      '#cache' => [
        'max-age' => $this->configuration['cache_max_age'],
      ],
    ];

    // Query for published Hello World Articles with the given tags.
    $entity_query = \Drupal::entityQuery('node')
      ->condition('status', '1')
      ->condition('type', 'hello_world_article')
      ->condition('field_sections.entity.tid', $this->getSelectedSections(), 'IN');

    $result = $entity_query->execute();

    if (empty($result)) {
      // Display an empty message if there are no results.
      $build['hello_block']['#markup'] = $this->configuration['empty_text'];
    }
    else {
      // Use Core's links template, but allow easy override.
      $build['hello_block'] = [
        '#theme' => 'links__hello',
        '#attributes' => [
          'class' => [],
        ],
      ];

      // Load all the nodes at once.
      $nodes = Node::loadMultiple($result);

      foreach ($nodes as $node) {
        // Add to links array.
        $build['hello_block']['#links'][] = [
          'title' => $node->label(),
          'url' => $node->urlInfo(),
        ];
      }
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'cache_max_age' => 60,
      'empty_text' => t('There are no Hello articles to display.'),
      'terms' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['empty_text'] = [
      '#type' => 'textfield',
      '#title' => t('No-results text'),
      '#description' => t('The text to display when no Hello World Articles are found.'),
      '#default_value' => $this->configuration['empty_text'],
      '#size' => 60,
      '#maxlength' => 255,
      '#required' => TRUE,
    ];

    $this->getAllSections();

    $form['terms'] = [
      '#type' => 'checkboxes',
      '#title' => t('Display articles in section'),
      '#description' => t('The sections from which to display Hello World Articles.'),
      '#options' => $this->getAllSections(),
      '#default_value' => $this->configuration['terms'],
      '#required' => TRUE,
    ];

    $form['cache_max_age'] = [
      '#type' => 'select',
      '#title' => t('Cache results'),
      '#default_value' => $this->configuration['cache_max_age'],
      '#description' => t('The period to refresh the links displayed in the block.'),
      '#options' => [
        '0' => 'Never, don\'t cache',
        '60' => '1 minute',
        '300' => '5 minutes',
        '3600' => '1 hour',
        Cache::PERMANENT => 'Forever',
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['empty_text'] = $form_state->getValue('empty_text');
    $this->configuration['cache_max_age'] = $form_state->getValue('cache_max_age');
    $this->configuration['terms'] = $form_state->getValue('terms');
  }

  /**
   * Gets a list of all sections and their keys.
   *
   * @return array
   *   An array of section term labels keyed by tid.
   */
  protected function getAllSections() {
    $terms = \Drupal::service('entity_type.manager')
      ->getStorage("taxonomy_term")
      ->loadTree('sections', 0, 1, TRUE);

    $out = [];

    foreach ($terms as $term) {
      $out[$term->id()] = $term->label();
    }

    return $out;
  }

  /**
   * Get a list of selected section labels.
   *
   * @return array
   *   An array of selected section labels.
   */
  protected function getSelectedSections() {
    $values = array_values($this->configuration['terms']);
    return $values;
  }

}
