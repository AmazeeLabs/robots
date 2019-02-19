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
   * Saving in the configuration is preferred to hook_alter()
   * so we are still able to edit it manually.
   *
   * @return string
   */
  public function updateConfigurationContent() {
    $robotsSettings = $this->configFactory->get('robots.settings');

    $instructions = [];
    // Sitemap instructions.
    if (
      $robotsSettings->get('include_sitemap') === 1 &&
      !empty($robotsSettings->get('sitemap_source'))
    ) {
      $instructions['sitemap'] = $this->getSitemapRobotsInstructions($robotsSettings->get('sitemap_source'));
    }

    // Custom content entities instructions.
    if ($robotsSettings->get('include_sitemap')) {
      $instructions['content_entities'] = $this->getRobotsInstructions();
    }

    // Current instructions.
    $instructions['current_content'] = explode("\n", $this->getConfigurationContent());

    $content = $this->mergeInstructions($instructions);
    $this->configFactory->getEditable('robotstxt.settings')->set('content', $content)->save();
  }

  /**
   * Merges the several instructions sources as a string.
   *
   * @param array $instructions
   *   List of sources containing list of instructions.
   *
   * @return string
   */
  private function mergeInstructions(array $instructions) {
    $result = '';
    // @todo implement content_entities
    $mergedInstructions = [];
    $result = implode("\n", $instructions['current_content']);
    return $result;
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
      $result['simple_sitemap'] = 'Simple Sitemap';
    }
    elseif ($this->moduleHandler->moduleExists('xmlsitemap')) {
      $result['xmlsitemap'] = 'XML Sitemap';
    }
    return $result;
  }

  /**
   * Returns a list of robots.txt instructions based on a sitemap source.
   *
   * @param string $source
   *
   * @return array
   */
  public function getSitemapRobotsInstructions($source) {
    $result = [];
    switch ($source) {
      case 'simple_sitemap':
        $result = $this->getSimpleSitemapLinks();
        break;
      case 'xmlsitemap':
        // @todo implement
        break;
      case 'custom':
        // @todo implement
        break;
    }
    return $result;
  }

  private function getSimpleSitemapLinks() {
    $result = [];
    // @todo use the simple_sitemap.generator service
    // so we can include variants properly.
    // Currently extracting urls from the raw result.
    // Get published sitemap instances.
    $query = \Drupal::database()->select('simple_sitemap', 'sm')
      ->condition('status', 1)
      ->fields('sm')
      ->execute();
    foreach ($query as $row) {
      if (!empty($row->sitemap_string)) {
        $sitemap = simplexml_load_string($row->sitemap_string);
        foreach ($sitemap->url as $url_list) {
          $result[] = (string) $url_list->loc;
        }
      }
    }
    return $result;
  }

}
