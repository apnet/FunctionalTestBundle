<?php

/**
 * Test Framework\WebTestCase class
 *
 * @author Andrey F. Mindubaev <covex.mobile@gmail.com>
 * @license http://opensource.org/licenses/MIT  MIT License
 */
namespace Apnet\FunctionalTestBundle\Tests\Framework;

use Apnet\FunctionalTestBundle\Framework\WebTestCase;
use Apnet\FunctionalTestBundle\Framework\Client;

/**
 * Test Framework\WebTestCase class
 */
class WebTestCaseTest extends WebTestCase
{

  /**
   * Test client
   *
   * @return null
   */
  public function testClient()
  {
    $client = self::createClient();

    $this->assertTrue($client instanceof Client);
  }

}
