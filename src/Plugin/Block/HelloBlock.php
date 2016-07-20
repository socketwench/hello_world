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
  public function __construct(array $configuration, $plugin_id, $plugin_definition) { //}, QueryFactory $entity_query) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

//  /**
//   * {@inheritdoc}
//   */
//  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
//    return new static(
//      $configuration,
//      $plugin_id,
//      $plugin_definition,
//      $container->get('entity.query')
//    );
//  }

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
    ];

    // Query for published Hello World Articles with the given tags.
    $entity_query = \Drupal::entityQuery('node')
      ->condition('status', '1')
      ->condition('type', 'hello_world_article')
      ->condition('field_sections.entity.name', ['About Us', 'Misc', 'News'], 'IN');

    $result = $entity_query->execute();

    if (empty($result)) {
      // Display an empty message if there are no results.
      $build['hello_block']['#markup'] = t('There are no Hello articles to display.');
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

  public function defaultConfiguration() {
    return parent::defaultConfiguration(); // TODO: Change the autogenerated stub
  }

  public function blockForm($form, FormStateInterface $form_state) {
    return parent::blockForm($form, $form_state); // TODO: Change the autogenerated stub
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state); // TODO: Change the autogenerated stub
  }
}
