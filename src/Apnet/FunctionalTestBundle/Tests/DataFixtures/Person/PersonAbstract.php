<?php

/**
 * Abstract name DataFixture
 *
 * @author Andrey F. Mindubaev <covex.mobile@gmail.com>
 * @license http://opensource.org/licenses/MIT  MIT License
 */
namespace Apnet\FunctionalTestBundle\Tests\DataFixtures\Person;

use Apnet\FunctionalTestBundle\Framework\DataFixtures\SingleObjectFixture;
use Apnet\TestEntityBundle\Entity;

/**
 * Abstract name DataFixture
 */
abstract class PersonAbstract extends SingleObjectFixture
{

  /**
   * @var string
   */
  public $name;

  /**
   * {@inheritdoc}
   */
  public function createObject()
  {
    $name = new Entity\Person();
    $name->setName($this->name);

    return $name;
  }
}
