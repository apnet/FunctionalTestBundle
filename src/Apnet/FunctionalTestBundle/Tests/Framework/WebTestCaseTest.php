<?php

/**
 * Test Framework\WebTestCase class and DataFixtures\SingleObjectFixture
 *
 * @author Andrey F. Mindubaev <covex.mobile@gmail.com>
 * @license http://opensource.org/licenses/MIT  MIT License
 */
namespace Apnet\FunctionalTestBundle\Tests\Framework;

use Apnet\FunctionalTestBundle\Framework\WebTestCase;
use Apnet\FunctionalTestBundle\Framework\Client;
use Apnet\FunctionalTestBundle\Tests\DataFixtures;
use Apnet\TestEntityBundle\Entity\Person;

/**
 * Test Framework\WebTestCase class and DataFixtures\SingleObjectFixture
 */
class WebTestCaseTest extends WebTestCase
{

  /**
   * Test client
   *
   * @return null
   */
  public function testClientOne()
  {
    $client = self::createClient();

    $this->assertTrue($client instanceof Client);

    $repository = $client->getContainer()
      ->get("doctrine.orm.default_entity_manager")
      ->getRepository("ApnetTestEntityBundle:Person");

    $all = $repository->findAll();
    /* @var $all Person[] */
    $this->assertEquals(2, sizeof($all));

    $names = array();
    foreach ($all as $person) {
      $names[] = $person->getName();
    }
    sort($names);

    $this->assertEquals(
      array("Mr.First", "Mr.Second"), $names
    );
  }

  /**
   * Test client (cache must not be recreated)
   *
   * @return null
   */
  public function testClientTwo()
  {
    $this->testClientOne();
  }

  /**
   * {@inheritdoc}
   */
  protected static function setDefaultClientUp(Client $client)
  {
    parent::setDefaultClientUp($client);

    $client->loadFixtures(
      array(
        new DataFixtures\Person\MrFirst(),
        new DataFixtures\Person\MrSecond(),
      )
    );
  }
}
