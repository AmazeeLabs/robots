<?php

namespace Drupal\robots\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Robots instruction entities.
 */
class RobotsInstructionViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
