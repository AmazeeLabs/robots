<?php

/**
 * @file
 * Contains robots_instruction.page.inc.
 *
 * Page callback for Robots instruction entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Robots instruction templates.
 *
 * Default template: robots_instruction.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_robots_instruction(array &$variables) {
  // Fetch RobotsInstruction Entity Object.
  $robots_instruction = $variables['elements']['#robots_instruction'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
