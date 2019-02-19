<?php

namespace Drupal\robots\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\robots\RobotsConfiguration;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class SettingsForm.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * Drupal\robots\RobotsConfiguration definition.
   *
   * @var \Drupal\robots\RobotsConfiguration $robotsConfiguration
   */
  protected $robotsConfiguration;

  /**
   * Class constructor.
   */
  public function __construct(ConfigFactoryInterface $config_factory, RobotsConfiguration $robots_configuration) {
    parent::__construct($config_factory);
    $this->robotsConfiguration = $robots_configuration;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('robots.configuration')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'robots.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'robots_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('robots.settings');
    // Check if robots.txt file is still on the file system
    // and propose to delete it as it will prevent RobotsTxtController access.
    // This is mentioned in the system wide 'Status report' by RobotsTxt,
    // so we are just adding context here with delete and compare features (WIP).

    $availableSitemaps = $this->robotsConfiguration->getEnabledSitemapModules();
    $form['robots_txt_file_status'] = [
      '#type' => 'item',
      '#title' => $this->t('Robots.txt file status'),
      '#description' => $this->getRobotsTxtFileStatus(),
    ];
    $form['include_sitemap'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Include Sitemap'),
      '#description' => $this->getSitemapDescription(),
      '#default_value' => $config->get('include_sitemap'),
      // @todo add option for a custom sitemap.
      '#disabled' =>  count($availableSitemaps) !== 1,
    ];
    $form['sitemap_source'] = [
      '#type' => 'radios',
      '#title' => $this->t('Sitemap source'),
      '#options' => $availableSitemaps,
      '#default_value' => $config->get('sitemap_source'),
      '#states' => [
        'visible' => [
          ':input[name="include_sitemap"]' => ['checked' => TRUE],
        ],
      ],
      // @todo add option for a custom sitemap.
      '#disabled' => count($availableSitemaps) !== 1,
    ];
    $form['include_custom_instructions'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Include custom instructions'),
      '#description' => $this->t('Instructions are created as content. @link.', [
        '@link' => $this->getInstructionsListLink(),
      ]),
      '#default_value' => $config->get('include_custom_instructions'),
    ];
    $form['robots_txt_preview'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Robots.txt preview'),
      // @todo ajax reload based on the settings, with a custom controller.
      '#default_value' => $this->robotsConfiguration->getConfigurationContent(),
      '#rows' => 20,
      '#disabled' => TRUE,
      '#suffix' => $this->getRobotsTxtEditLink(),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('robots.settings')
      ->set('include_sitemap', $form_state->getValue('include_sitemap'))
      ->set('sitemap_source', $form_state->getValue('sitemap_source'))
      ->set('include_custom_instructions', $form_state->getValue('include_custom_instructions'))
      ->save();

    $this->robotsConfiguration->updateConfigurationContent();
  }

  private function getRobotsTxtFileStatus() {
    $result = '';
    if ($this->robotsConfiguration->fileExists()) {
      // @todo implement $this->robotsConfiguration->deleteFile()
      // @todo implement $this->robotsConfiguration->compareConfigurationContentWithFile()
      $result = $this->t('⚠️ The <em>robots.txt</em> file is still on the file system. Override will only take effect when it is deleted.');
    }
    else {
      $result = $this->t('✅ The <em>robots.txt</em> file has been deleted and replaced by the active configuration.');
    }
    return $result;
  }

  private function getRobotsTxtEditLink() {
    // @todo set permissions if they become different.
    $url = Url::fromRoute('robotstxt.admin_settings_form');
    $link = Link::fromTextAndUrl($this->t('Edit robots.txt manually'), $url)->toRenderable();
    return \Drupal::service('renderer')->renderRoot($link);
  }

  private function getSitemapDescription() {
    $result = '';
    $enabledModules = $this->robotsConfiguration->getEnabledSitemapModules();

    if (empty($enabledModules)) {
      $result = $this->t('No Sitemap module was found. You can install @simple_sitemap_link or @xmlsitemap_link.', [
        '@simple_sitemap_link' => 'Simple Sitemap', // @todo link.
        '@xmlsitemap_link' => 'XML Sitemap', // @todo link
      ]);
    }

    if (count($enabledModules) > 1) {
      $result = $this->t("There shouldn't be more than one Sitemap module installed.");
    }

    return $result;
  }

  private function getInstructionsListLink() {
    $url = Url::fromRoute('robots_instruction.list');
    $link = Link::fromTextAndUrl($this->t('View instructions list'), $url)->toRenderable();
    return \Drupal::service('renderer')->renderRoot($link);
  }

}
