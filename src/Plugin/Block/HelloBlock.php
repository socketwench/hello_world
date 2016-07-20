<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\Sql\QueryFactory;
use Drupal\Core\Form\FormStateInterface;

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
  public function __construct(array $configuration, $plugin_id, $plugin_definition, QueryFactory $entity_query) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['hello_block']['#markup'] = 'Implement HelloBlock.';

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
