# Hello World!

A coding demonstration module.

## Requirements

* The text "Hello World!" should appear in bold typeface within a block on the right side of all Hello World Article pages only.
* A list of hyperlinked titles to all nodes that are of Hello World Article type, and are tagged with "Enabled" terms from the Sections vocabulary, should appear below the "Hello World!" text on Hello World Article pages.
* When viewing a Hello World Article on the Drupal site, the phrase "Content starts here!" should appear in an italic typeface as the first line of content on the page. 
* All of this functionality needs to be contained in one Drupal module. The only module's dependencies should be Drupal core modules and the content_type_vocab_hello_world module. 
* Additionally, the Views module cannot be used for this exercise.
* Name the module "hello_world".
* Compatible with Drupal 8.

## Design Process

The Hello, World block will be provided using the Drupal core block API. A custom block type will be provided by the module, and a pre-configured block instance will be included out of the box. The “Hello, World” text will be displayed as the block title, with a custom stylesheet attached to render it in bold.

Hello World Article content will be retrieved using an entity query. Articles will then be loaded in a batch, reducing repeated queries on the database. The links will be then be outputted using the core links template.

An entity view hook will be used to alter the display of Hello World Articles to include the cue text above the body field.

