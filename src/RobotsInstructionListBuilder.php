<?php

namespace Drupal\robots;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Robots instruction entities.
 *
 * @ingroup robots
 */
class RobotsInstructionListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Robots instruction ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\robots\Entity\RobotsInstruction */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.robots_instruction.edit_form',
      ['robots_instruction' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
