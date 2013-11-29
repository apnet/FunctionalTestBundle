<?php

namespace Apnet\FunctionalTestBundle\Tests\Framework;

use Apnet\FunctionalTestBundle\Framework\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class WebTestCaseTest extends WebTestCase
{

  /**
   * Test client
   *
   * @return null
   */
  public function testClient()
  {
    $client = $this->setupClient();

    $this->assertTrue($client instanceof Client);
  }

}
