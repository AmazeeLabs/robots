<?php

namespace Drupal\robots;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Robots instruction entity.
 *
 * @see \Drupal\robots\Entity\RobotsInstruction.
 */
class RobotsInstructionAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\robots\Entity\RobotsInstructionInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished robots instruction entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published robots instruction entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit robots instruction entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete robots instruction entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add robots instruction entities');
  }

}
