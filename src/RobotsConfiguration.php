<?php

namespace Drupal\robots;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class RobotsConfiguration.
 *
 * Helper as a service to manipulate the configuration exposed
 * by the RobotsTxt module via the Robots UI.
 */
class RobotsConfiguration {

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Drupal\Core\Extension\ModuleHandlerInterface definition.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new RobotsConfiguration object.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ModuleHandlerInterface $module_handler, EntityTypeManagerInterface $entity_type_manager) {
    $this->configFactory = $config_factory;
    $this->moduleHandler = $module_handler;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Gets the RobotsTxt configuration content.
   *
   * @return string
   */
  public function getConfigurationContent() {
    return $this->configFactory->get('robotstxt.settings')->get('content');
  }

  /**
   * Updates the RobotsTxt configuration content based on the Robots settings.
   *
   * @return string
   */
  public function updateConfigurationContent() {
    // Saving in the configuration is preferred to hook_alter()
    // so we are still able to edit it manually.
    $content = '';
    // @todo implement based on the Robots settings:
    // @todo getRobotsInstructions()
    // @todo getSitemapRobotsInstructions()
    $this->configFactory->getEditable('robotstxt.settings')->set('content', $content)->save();
  }

  /**
   * Compares the RobotsTxt configuration with the robots.txt file.
   *
   * RobotsTxt install takes care of copying the file during installation.
   * This helper can be used to check the configuration changes to be applied
   * before deleting the robots.txt file.
   */
  public function compareConfigurationContentWithFile() {
    // @todo implement
  }

  /**
   * Checks if the robots.txt file exists.
   *
   * @return bool
   */
  public function fileExists() {
    return file_exists(DRUPAL_ROOT . '/robots.txt');
  }

  public function deleteFile() {
    // @todo implement
  }

  /**
   * Returns a list of robots.txt instructions from the Robots module.
   *
   * These instructions are provided by RobotsInstruction content entities.
   *
   * @return array
   */
  public function getRobotsInstructions() {
    $result = [];
    // @todo implement
    return $result;
  }

  /**
   * Returns a list of enabled sitemap related modules.
   *
   * In a standard configuration, there should be 0 or 1.
   *
   * @return array
   */
  public function getEnabledSitemapModules() {
    $result = [];
    if ($this->moduleHandler->moduleExists('simple_sitemap')) {
      $result[] = 'simple_sitemap';
    }
    elseif ($this->moduleHandler->moduleExists('sitemap')) {
      $result[] = 'sitemap';
    }
    return $result;
  }

  /**
   * Returns a list of robots.txt instructions based on a sitemap module.
   *
   * @param string $module
   *
   * @return array
   */
  public function getSitemapRobotsInstructions($module) {
    $result = [];
    // @todo implement
    return $result;
  }

}
