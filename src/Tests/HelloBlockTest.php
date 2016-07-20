<?php

namespace Drupal\hello_world\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests the Hello, World block.
 *
 * @group hello_world
 */
class HelloBlockTest extends HelloTestBase {

  public function testHelloBlock() {
    $this->drupalGet('node');
    $this->assertText('WTF.');
  }
}
