<?php

declare(strict_types=1);

namespace Drupal\news_feed\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure admin form for news feed.
 */
class NewsFeedAdminForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'news_feed.admin_settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'news_feed_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['api_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('News feed API URL'),
      '#default_value' => $config->get('api_url'),
    ];

    $form['count'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of news'),
      '#default_value' => $config->get('count'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->config(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('api_url', $form_state->getValue('api_url'))
      ->set('count', $form_state->getValue('count'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
