<?php

declare(strict_types=1);

namespace Drupal\news_feed\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\news_feed\NewsFeedService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\RendererInterface;

/**
 * Provides a 'Display News feed' Block.
 *
 * @Block(
 *   id = "display_news_feed",
 *   admin_label = @Translation("Display News feed"),
 *   category = @Translation("Display News feed"),
 * )
 */
class DisplayNewsFeed extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * News feed service object.
   *
   * @var \Drupal\news_feed\NewsFeedService
   */
  protected $newsFeedService;

  /**
   * Renderer object.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a DisplayNewsFeed object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\news_feed\NewsFeedService $news_feed_service
   *   News feed service object.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   Renderer object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, NewsFeedService $news_feed_service, RendererInterface $renderer) {
    $this->newsFeedService = $news_feed_service;
    $this->renderer = $renderer;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('news_feed.news_feed_service'),
      $container->get('renderer'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [
      '#theme' => 'news_feed',
      '#news_list' => $this->newsFeedService->getNews(),
    ];

    $this->renderer->addCacheableDependency($build, $this->newsFeedService->getConfig());
    return $build;
  }

}
