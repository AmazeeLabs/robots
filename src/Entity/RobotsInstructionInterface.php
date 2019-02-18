<?php

namespace Drupal\robots\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Robots instruction entities.
 *
 * @ingroup robots
 */
interface RobotsInstructionInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Robots instruction name.
   *
   * @return string
   *   Name of the Robots instruction.
   */
  public function getName();

  /**
   * Sets the Robots instruction name.
   *
   * @param string $name
   *   The Robots instruction name.
   *
   * @return \Drupal\robots\Entity\RobotsInstructionInterface
   *   The called Robots instruction entity.
   */
  public function setName($name);

  /**
   * Gets the Robots instruction creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Robots instruction.
   */
  public function getCreatedTime();

  /**
   * Sets the Robots instruction creation timestamp.
   *
   * @param int $timestamp
   *   The Robots instruction creation timestamp.
   *
   * @return \Drupal\robots\Entity\RobotsInstructionInterface
   *   The called Robots instruction entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Robots instruction published status indicator.
   *
   * Unpublished Robots instruction are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Robots instruction is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Robots instruction.
   *
   * @param bool $published
   *   TRUE to set this Robots instruction to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\robots\Entity\RobotsInstructionInterface
   *   The called Robots instruction entity.
   */
  public function setPublished($published);

}
