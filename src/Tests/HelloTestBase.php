<?php

namespace Drupal\hello_world\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\taxonomy\Entity\Term;

class HelloTestBase extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'link',
    'menu_ui',
    'node',
    'user',
    'block',
    'field_ui',
    'path',
    'text',
    'taxonomy',
    'content_type_vocab_hello_world',
  ];

  /**
   * Taxonomy terms needed by the tests.
   *
   * @var array
   */
  protected $terms = [];

  /**
   * Nodes needed by the tests.
   *
   * @var array
   */
  protected $nodes = [];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->terms = $this->createTerms();
    $this->nodes = $this->createHelloNodes();
  }

  /**
   * Create taxonomy terms for testing.
   */
  protected function createTerms() {
    $terms['about_us'] = Term::create([
      'name' => 'About Us',
      'vid' => 'sections',
    ])->save();

    $terms['news'] = Term::create([
      'name' => 'News',
      'vid' => 'sections',
    ])->save();

    $terms['misc'] = Term::create([
      'name' => 'Misc',
      'vid' => 'sections',
    ])->save();

    return $terms;
  }

  protected function createHelloNodes() {
    $nodes[] = $this->drupalCreateNode([
      'type' => 'hello_world_article',
      'title' => $this->randomString(),
      'field_sections' => [
        'target_id' => $this->terms['news'],
      ],
    ]);

    $nodes[] = $this->drupalCreateNode([
      'type' => 'hello_world_article',
      'title' => $this->randomString(),
      'field_sections' => [
        'target_id' => $this->terms['news'],
      ],
    ]);

    $nodes[] = $this->drupalCreateNode([
      'type' => 'hello_world_article',
      'title' => $this->randomString(),
      'field_sections' => [
        'target_id' => $this->terms['about_us'],
      ],
    ]);

    $nodes[] = $this->drupalCreateNode([
      'type' => 'hello_world_article',
      'title' => $this->randomString(),
      'field_sections' => [
        'target_id' => $this->terms['misc'],
      ],
    ]);

    return $nodes;
  }

}
