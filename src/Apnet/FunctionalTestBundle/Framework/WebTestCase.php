<?php

/**
 * WebTestCase
 *
 * @author Andrey F. Mindubaev <covex.mobile@gmail.com>
 * @license http://opensource.org/licenses/MIT  MIT License
 * @link https://github.com/liip/LiipFunctionalTestBundle
 */
namespace Apnet\FunctionalTestBundle\Framework;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * WebTestCase is the base class for functional tests.
 */
abstract class WebTestCase extends BaseWebTestCase
{

  /**
   * Default Client object
   *
   * @var Client
   */
  private static $_client = null;

  /**
   * Creates a Client.
   *
   * $server can be used to pass headers to the client, note that they have
   * to follow the naming format used in $_SERVER.
   *
   * Use 'PHP_AUTH_USER' & 'PHP_AUTH_PW' for user authentication
   *
   * @param array $options An array of options to pass to the createKernel class
   * @param array $server  An array of server parameters
   *
   * @return Client A Client instance
   * @throws \RuntimeException when test.client was redefined somewhere
   */
  protected static function createClient(array $options = array(), array $server = array())
  {
    $options['environment'] = 'test';

    $client = parent::createClient($options, $server);
    if (!($client instanceof Client)) {
      throw new \RuntimeException(
        "'test.client' was redefined to " . get_class($client)
      );
    }
    /* @var $client Client */
    if (is_null(self::$_client)) {
      self::$_client = $client;
      static::setDefaultClientUp(self::$_client);
    }
    return $client;
  }

  /**
   * {@inheritdoc}
   */
  protected function tearDown()
  {
    if (!is_null(self::$_client)) {
      static::tearDefaultClientDown(self::$_client);
    }
    self::$_client = null;
    parent::tearDown();
  }

  /**
   * Sets up default Client
   *
   * This method is called after default Client is created.
   *
   * @param Client $client Client
   *
   * @return null
   */
  protected static function setDefaultClientUp(Client $client)
  {
    $client->setUp();
  }

  /**
   * Tears down the fixture, for example, close a network connection.
   * This method is called before client & kernel destroyed
   *
   * @param Client $client Client
   *
   * @return null
   */
  protected static function tearDefaultClientDown(Client $client)
  {
    $client->tearDown();
  }

}
