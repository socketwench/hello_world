<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\Sql\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

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
   * The entity query manager.
   *
   * @var \Drupal\Core\Entity\Query\Sql\QueryFactory
   */
  private $entityQueryManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    // @todo Inject the entity query service?
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
      ->condition('field_sections.entity.name', ['About Us', 'Misc', 'News'], 'IN');

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
        \Drupal\Core\Cache\Cache::PERMANENT => 'Forever',
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
  }
}
