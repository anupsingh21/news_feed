<?php

declare(strict_types=1);

namespace Drupal\news_feed;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\news_feed\Form\NewsFeedAdminForm;
use Drupal\Component\Serialization\Json;
use GuzzleHttp\ClientInterface;

/**
 * News feed service to get data from external source.
 */
class NewsFeedService {

  /**
   * Config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Http client to make api calls.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, ClientInterface $http_client) {
    $this->configFactory = $config_factory;
    $this->httpClient = $http_client;
  }

  /**
   * Get the value based on machine name.
   *
   * @param string $machine_name
   *   Unique machine name.
   *
   * @return array|mixed|null
   *   Return the value from configuration.
   */
  public function get($machine_name) {
    return $this->getConfig()->get($machine_name);
  }

  /**
   * Get the raw configuration object.
   */
  public function getConfig() {
    return $this->configFactory->get(NewsFeedAdminForm::SETTINGS);
  }

  /**
   * Get the new from external endpoint.
   *
   * @return array
   *   return array of news.
   */
  public function getNews(): array {

    // Get default values from the config form.
    $route = $this->get('api_url');
    $count = (int) $this->get('count');

    $response = $this->request('GET', $route);
    // Limit the result based on input in admin form.
    return array_slice($response, 0, $count);
  }

  /**
   * Make the API calls.
   *
   * @param string $method
   *   Http method.
   * @param string $route
   *   Api route to make the call.
   *
   * @return array
   *   response from the endpoint.
   */
  public function request(string $method, string $route) {
    $response = $this->httpClient->request($method, $route);

    if ($response_contents = $response->getBody()->getContents()) {
      return Json::decode($response_contents);
    }

    return [];
  }

}
